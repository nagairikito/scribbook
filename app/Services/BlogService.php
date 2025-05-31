<?php

namespace App\Services;

use App\Models\Advertisement;
use App\Models\User;
use App\Models\Article;
use App\Models\ArticleComment;
use App\Models\FavoriteBlog;
use App\Models\BrowsingHistory;
use App\Repositories\BlogRepository;
use App\Repositories\BlogCommentsRepository;
use App\Repositories\BrowsingHistoryRepository;
use App\Repositories\AccountRepository;
use App\Repositories\AdvertisementRepository;
use App\Repositories\FavoriteBlogRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\Input;

class BlogService extends Service
{
    public $accountRepository;
    public $blogRepository;
    public $blogCommentsRepository;
    public $favoriteBlogRepository;
    public $BrowsingHistoryRepository;
    public $advertisementRepository;

    public function __construct() {

        // Modelのインスタンス化
        $user = new User;
        $blog = new Article;
        $blogComments = new ArticleComment;
        $favoriteBlog = new FavoriteBlog;
        $browsingHistory = new BrowsingHistory;
        $advertisement = new Advertisement;

        // Repositryのインスタンス化
        $this->accountRepository = new AccountRepository($user);
        $this->blogRepository = new BlogRepository($blog);
        $this->blogCommentsRepository = new BlogCommentsRepository($blogComments);
        $this->favoriteBlogRepository = new FavoriteBlogRepository($favoriteBlog);
        $this->BrowsingHistoryRepository = new BrowsingHistoryRepository($browsingHistory);
        $this->advertisementRepository = new AdvertisementRepository($advertisement);

    }
    
    /**
     * ブログ登録
     * @param $inputData
     * @return bool $blogPostStatus
     */
    public function postBlog($inputData) {
        $postFlag = false;

        if(!Auth::user() || Auth::id() != $inputData['user_id']) {
            return $postFlag;
        }

        $created_by = $this->accountRepository->getAccountById($inputData['user_id']);
        if(!$created_by) {
            return $postFlag;
        }

        // $inputData['contents'] = html_entity_decode($inputData['contents']);


        try {
            DB::beginTransaction();

            $checkPostBlog = $this->blogRepository->postBlog($inputData);
            if($checkPostBlog) {
                $postFlag = true;
            }
            $this->storeBase64Image($inputData['base64_texts'], $inputData['image_file_names']);

            DB::commit();
            return $postFlag;

        } catch (\Exception $e) {
            report($e);
            session()->flash('flash_message', 'エラーが発生しました');
        }    
    }

    /**
     * ブログ編集
     * @param $inputData
     * @return $result
     */
    public function editBlog($inputData) {
        $editBlogFlag = false;

        if(!Auth::user() || Auth::id() != $inputData['created_by']) {
            return $editBlogFlag;
        }

        $created_by = $this->accountRepository->getAccountById($inputData['created_by']);
        if(!$created_by) {
            return $editBlogFlag;
        }

        $targetBlog = $this->blogRepository->blogDetail($inputData['id']);
        if(!$targetBlog) {
            return $editBlogFlag;
        }

        DB::beginTransaction();
        try {
            // $this->deleteBlogContentsImageFromStorage($targetBlog[0]['blog_unique_id']);
            $this->updateBase64Image($inputData['base64_texts'], $inputData['image_file_names'], $targetBlog[0]['blog_unique_id']);
            $checkEditBlog = $this->blogRepository->editBlog($inputData);
            if($checkEditBlog) {
                $editBlogFlag = true;
            }
    
            DB::commit();
            return $editBlogFlag;

        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            session()->flash('flash_message', 'エラーが発生しました');
        }    
    }
    
    /**
     * ブログ削除
     * @param $inputData
     * @return $result
     */
    public function deleteBlog($inputData) {
        $deleteBlogFlag = false;

        if(!Auth::user() || Auth::id() != $inputData['created_by']) {
            return $deleteBlogFlag;
        }

        $created_by = $this->accountRepository->getAccountById($inputData['created_by']);
        if(!$created_by) {
            return $deleteBlogFlag;
        }
        
        $targetBlog = $this->blogRepository->blogDetail($inputData['id']);
        if(!$targetBlog) {
            return $deleteBlogFlag;
        }

        DB::beginTransaction();
        try {
            $checkDeleteBlog = $this->blogRepository->deleteBlog($targetBlog[0]['id']);
            if($checkDeleteBlog) {
                $this->deleteBlogContentsImageFromStorage($targetBlog[0]['blog_unique_id']);
                $deleteBlogFlag = true;
            }
    
            DB::commit();
            return $deleteBlogFlag;

        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            session()->flash('flash_message', 'エラーが発生しました');
        }    
    }

    /**
     * ブログ全件取得
     * @return $allBlogs
     */
    public function getAllBlogs() {
        $allBlogs = $this->blogRepository->getAllBlogs();
        return $allBlogs;
    }

    /**
     * トピックス取得
     */
    public function getTopics() {
        $topics = $this->blogRepository->getTopics();
        return $topics;
    }

    /**
     * ユーザーIDに紐づくブログからブログIDをもとに1件取得
     * @param $userId
     * @return $blogs
     */
    public function getBlogByUserId($inputData) {

        $loginUser = $this->accountRepository->getAccountById($inputData['user_id']);
        if(!$loginUser) {
            return [];
        }

        $blogs = $this->blogRepository->getBlogByUserId($inputData['id']);
        return $blogs;
    }

    /**
     * ユーザーIDに紐づくブログを全件取得
     * @param $userId
     * @return $blogs
     */
    public function getBlogsByUserId($userId) {

        $targetUser = $this->accountRepository->getAccountById($userId);
        if(!$targetUser) {
            return [];
        }

        $blogs = $this->blogRepository->getBlogsByUserId($userId);
        return $blogs;
    }

    /**
     * ユーザーIDに紐づくお気に入り登録したユーザーのブログ全件取得
     * @param $userId
     * @return $blogs
     */
    public function getBlogPostedByFavoriteUserByUserId($userId) {

        $targetUser = $this->accountRepository->getAccountById($userId);
        if(!$targetUser) {
            return [];
        }

        $getBlogPostedByFavoriteUser = $this->blogRepository->getBlogPostedByFavoriteUserByUserId($userId);
        return $getBlogPostedByFavoriteUser;
    }

    /**
     * ブログ詳細取得
     * @param $id
     * @return $blog
     */
    public function blogDetail($id) {
        $blog = $this->blogRepository->blogDetail($id);
        $blog[0]['contents'] = html_entity_decode($blog[0]['contents']);

        // 閲覧処理（閲覧数増加、閲覧履歴の登録）
        $this->blogRepository->increaseViewCount($id);
        if(Auth::id()) {
            $this->BrowsingHistoryRepository->upsertBrowsingHistory(Auth::id() ,$id);
        }

        $blog[0]['favorite_flag'] = false;
        if(Auth::user()) {
            // $checkFavoriteBlog = $this->favoriteBlogRepository->checkExsitsFavoriteBlogByBlogIdAndUserId(Auth::id(), $id);
            $checkFavoriteBlog = $this->checkExsitsFavoriteBlogByBlogIdAndUserId(Auth::id(), $id);
            if($checkFavoriteBlog) {
                $blog[0]['favorite_flag'] = true;
            }
        }


        return $blog;
    }

    /**
     * ブログに紐づくコメントを取得
     * @param $id
     * @return $comments
     */
    public function getBlogComments($id) {
        $comments = $this->blogCommentsRepository->getBlogComments($id);

        return $comments;
    }

    /**
     * ブログに紐づく広告を取得
     * @param $id
     * @return $comments
     */
    public function getAdvertisementByBlogId($id) {
        $advertisement = $this->advertisementRepository->getAdvertisementByBlogId($id);

        return $advertisement;
    }

    /**
     * 対象ユーザーIDに紐づくお気に入り登録されたブログを全件取得
     * @param $id
     * @return $favoriteBlogs
     */
    public function getAllFavoriteBlogsByUserId($id) {
        if(!Auth::user() || Auth::id() != $id) {
            return [];
        }
        
        $checkExistsUser = $this->accountRepository->getAccountById($id);
        if(!$checkExistsUser) {
            return [];
        }

        $favoriteBlogs = $this->favoriteBlogRepository->getAllFavoriteBlogsByUserId($id);
        return $favoriteBlogs;
    }

    /**
     * ユーザーIDとブログIDをもとに対象のユーザーが対象のブログがお気に入り登録しているかを判定
     * @param $user_id, $blog_id
     * @return $result
     */
    public function checkExsitsFavoriteBlogByBlogIdAndUserId($user_id, $blog_id) {

        $checkExistsUser = $this->accountRepository->getAccountById($user_id);
        if(!$checkExistsUser) {
            return false;
        }

        $inputData = [
            'user_id' => $user_id,
            'blog_id' => $blog_id,
        ];

        $result = $this->favoriteBlogRepository->checkExsitsFavoriteBlogByBlogIdAndUserId($inputData);
        
        return $result;
    }
    
    /**
     * ブログコメント登録
     * @param $inputData
     * @return $result
     */
    public function postComment($inputData) {
        $postCommentStauts = false;

        try {
            DB::beginTransaction();
    
            if(!Auth::user() || Auth::id() != $inputData['created_by']) {
                return $postCommentStauts;
            }
    
            $loginUser = $this->accountRepository->getAccountById($inputData['created_by']);
            if(!$loginUser) {
                return $postCommentStauts;
            }
    
            $result = $this->blogCommentsRepository->postComment($inputData);
            if($result) {
                $postCommentStauts = true;
            }
    
            DB::commit();
            return $postCommentStauts;

        } catch (\Exception $e) {
            report($e);
            session()->flash('flash_message', 'エラーが発生しました');
        }
    }

    /**
     * ブログお気に入り登録
     * @param $inputData
     * @return $result
     */
    public function registerFavoriteBlog($inputData) {
        $registerFavoriteBlogStatus = false;

        try {
            DB::beginTransaction();
    
            if(!Auth::user() || Auth::id() != $inputData['user_id']) {
                return $registerFavoriteBlogStatus;
            }
    
            $targetUser = $this->accountRepository->getAccountById($inputData['user_id']);
            if(!$targetUser) {
                return $registerFavoriteBlogStatus;
            }
    
            $targetBlog = $this->blogRepository->blogDetail($inputData['blog_id']);
            if(!$targetBlog) {
                return $registerFavoriteBlogStatus;
            }
    
            $checkExsitsFavoriteBlog = $this->favoriteBlogRepository->checkExsitsFavoriteBlogByBlogIdAndUserId($inputData);
            if($checkExsitsFavoriteBlog) {
                return $registerFavoriteBlogStatus;
            }
    
            $result = $this->favoriteBlogRepository->registerFavoriteBlog($inputData);
            if($result == true) {
                $registerFavoriteBlogStatus = true;
            }
        
            DB::commit();
            return $registerFavoriteBlogStatus;

        } catch (\Exception $e) {
            report($e);
            session()->flash('flash_message', 'エラーが発生しました');
        }    
    }

    /**
     * ブログお気に入り登録解除
     * @param $inputData
     * @return $result
     */
    public function deleteFavoriteBlog($inputData) {
        $deleteFavoriteBlogStatus = false;

        try {
            DB::beginTransaction();
    
            if(!Auth::user() || Auth::id() != $inputData['user_id']) {
                return $deleteFavoriteBlogStatus;
            }
    
            $targetUser = $this->accountRepository->getAccountById($inputData['user_id']);
            if(!$targetUser) {
                return $deleteFavoriteBlogStatus;
            }
    
            $targetBlog = $this->blogRepository->blogDetail($inputData['blog_id']);
            if(!$targetBlog) {
                return $deleteFavoriteBlogStatus;
            }
    
            $checkExsitsFavoriteBlog = $this->favoriteBlogRepository->checkExsitsFavoriteBlogByBlogIdAndUserId($inputData);
            if(!$checkExsitsFavoriteBlog) {
                return $deleteFavoriteBlogStatus;
            }
    
            $result = $this->favoriteBlogRepository->deleteFavoriteBlog($inputData);
            if($result == true) {
                $deleteFavoriteBlogStatus = true;
            }
    
            DB::commit();
            return $deleteFavoriteBlogStatus;

        } catch (\Exception $e) {
            report($e);
            session()->flash('flash_message', 'エラーが発生しました');
        }    
    }

    /**
     * 閲覧履歴表示
     */
    public function showBrowsingHistory($inputData) {
        $targetUser = $this->accountRepository->getAccountById($inputData['user_id']);
        if(!$targetUser) {
            return false;
        }

        $browsingHistory = $this->BrowsingHistoryRepository->getBrowsingHisotryByUserId($inputData['user_id']);
        return $browsingHistory;

    }

    /**
     * 広告の登録
     */
    public function registerAdvertisement($inputData) {
        $flag = false;
        // try {
        //     DB::beginTransaction();


            $targetUser = $this->accountRepository->getAccountById($inputData['created_by']);
            if(!$targetUser) {
                return $flag;
            }

            $targetBlog = $this->blogRepository->blogDetail($inputData['blog_id']);
            $RegisteredAdvertisementCount = $this->advertisementRepository->checkRegisteredAdvertisement($inputData['blog_id']);

            if(!$targetBlog || $RegisteredAdvertisementCount > 0) {
                return $flag;
            }

            if($inputData['advertisement_image_file'] || $inputData['advertisement_image_file'] != null) {
                $image_name = $this->upsertAdvertisementImageIntoStorage($inputData['advertisement_image_file'], null);
                $inputData['advertisement_image_name'] = $image_name;
                $flag = $this->advertisementRepository->registerAdvertisement($inputData);
            }


            // DB::commit();
            return $flag;

        // } catch (\Exception $e) {
        //     report($e);
        //     session()->flash('flash_message', 'エラーが発生しました');
        // }    
    }

    /**
     * 広告の削除(DB)
     */
    public function deleteAdvertisement($inputData) {
        $flag = false;
        // try {
        //     DB::beginTransaction();


            $targetUser = $this->accountRepository->getAccountById($inputData['created_by']);
            if(!$targetUser || Auth::id() != $inputData['created_by']) {
                return $flag;
            }

            $targetBlog = $this->blogRepository->blogDetail($inputData['blog_id']);
            $RegisteredAdvertisementCount = $this->advertisementRepository->checkRegisteredAdvertisement($inputData['blog_id']);

            if(!$targetBlog || $RegisteredAdvertisementCount == 0) {
                return $flag;
            }

            $targetAdvertisement = $this->advertisementRepository->getAdvertisementById($inputData['id']);

            if($inputData['advertisement_image_name'] && 
                $inputData['advertisement_image_name'] != null &&
                count($targetAdvertisement) != 0) {
                    $this->deleteAdvertisementImageFromStorage($targetAdvertisement[0]['advertisement_image_name']);
                    $this->advertisementRepository->deleteAdvertisement($targetAdvertisement[0]['id']);
                    $flag = true;
            }

            // DB::commit();
            return $flag;

        // } catch (\Exception $e) {
        //     report($e);
        //     session()->flash('flash_message', 'エラーが発生しました');
        // }    
    }

    /**
     * 広告画像保存,Storageに保存
     * @param $iconImageFile
     * @return $result
     */
    public function upsertAdvertisementImageIntoStorage($iconImageFile, $oldImageName) {
        try {
            DB::beginTransaction();
    
            if($oldImageName && $oldImageName != null) {
                $this->deleteAdvertisementImageFromStorage($oldImageName);
            }

            $newImageName = 'noImage.png';
            if($iconImageFile && $iconImageFile!= null) {
                $originalName = $iconImageFile->getClientOriginalName();
                $newImageName = date('Ymd_His') . '_' . $originalName;
        
                // Storage::disk('public')->putFileAs('advertisement_image_file', $iconImageFile->file('advertisement_image_file'), $newImageName);    
                Storage::disk('public')->putFileAs('advertisement_images', $iconImageFile, $newImageName);    
            }
    
            DB::commit();
            return $newImageName;

        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            session()->flash('flash_message', 'エラーが発生しました');
        }
    }

    /**
     * 広告画像削除,Storageから削除
     * @param $targetImageName
     * @return $result
     */
    public function deleteAdvertisementImageFromStorage($targetImageName) {
        DB::beginTransaction();
        try {
            if($targetImageName != null && $targetImageName != 'noImage.png') {
                $targetImageNameExists = Storage::disk('public')->exists('advertisement_images/'. $targetImageName);
                if($targetImageNameExists) {
                    Storage::disk('public')->delete('advertisement_images/'. $targetImageName);
                }
            }
    
            DB::commit();
            
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            session()->flash('flash_message', 'エラーが発生しました');
        }
    }

    /**
     * ブログコンテンツ画像保存,Storageに保存(新規用)
     * @param $targetImageName
     * @return $result
     */
    public function updateBase64Image($imageBase64Texts, $imageNames, $prevBlogUniqueId)
    {
        if(count($imageBase64Texts) > 0) {
            foreach($imageBase64Texts as $index => $imageBase64Data) {

                if(preg_match('/^data:image\/[a-zA-Z]+;base64,/', $imageBase64Texts[$index])) {
                    // 例: data:image/png;base64,iVBORw0KGgoAAAANS...
                    $base64Image = $imageBase64Texts[$index];

                    // 正規表現でMIMEタイプとBase64本体を抽出
                    if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches)) {
                        $extension = strtolower($matches[1]); // 例: png, jpeg
                        $base64Data = substr($base64Image, strpos($base64Image, ',') + 1);
                    } else {
                        return response()->json(['error' => 'Invalid base64 format.'], 400);
                    }

                    // Base64デコード
                    $imageData = base64_decode($base64Data);
                    if ($imageData === false) {
                        return response()->json(['error' => 'Failed to decode base64.'], 400);
                    }

                    // ファイル名と保存パス生成
                    $filePath = 'blog_contents_images/' . $imageNames[$index];

                    // 保存（storage/app/public/images に保存）
                    Storage::disk('public')->put($filePath, $imageData);
                } else {
                    $parts = explode('_', $imageNames[$index]);
                    $remainingFileName = implode('_', array_slice($parts, 2));

                    $allFiles = Storage::disk('public')->allFiles('blog_contents_images');
                    $targetImageFileNames = array_filter($allFiles, function($file) use ($prevBlogUniqueId) {
                        if(str_contains($file, $prevBlogUniqueId)) {
                            return $file;
                        }
                    });
                    $targetImageFileName = array_filter($targetImageFileNames, function($file) use ($remainingFileName) {
                        if(str_contains($file, $remainingFileName)) {
                            return $file;
                        }
                    });

                    if($targetImageFileName) {
                        foreach($targetImageFileName as $fileName) {
                            Storage::disk('public')->move($fileName, 'blog_contents_images/' . $imageNames[$index]);
                        }
                    }
                }
            }
            $this->deleteBlogContentsImageFromStorage($prevBlogUniqueId);
        }
    }

    /**
     * ブログコンテンツ画像保存,Storageに保存
     * @param $targetImageName
     * @return $result
     */
    public function storeBase64Image($imageBase64Texts, $imageNames)
    {
        if(count($imageBase64Texts) > 0) {
            foreach($imageBase64Texts as $index => $imageBase64Data) {
                // 例: data:image/png;base64,iVBORw0KGgoAAAANS...
                $base64Image = $imageBase64Texts[$index];

                // 正規表現でMIMEタイプとBase64本体を抽出
                if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches)) {
                    $extension = strtolower($matches[1]); // 例: png, jpeg
                    $base64Data = substr($base64Image, strpos($base64Image, ',') + 1);
                } else {
                    return response()->json(['error' => 'Invalid base64 format.'], 400);
                }

                // Base64デコード
                $imageData = base64_decode($base64Data);
                if ($imageData === false) {
                    return response()->json(['error' => 'Failed to decode base64.'], 400);
                }

                // ファイル名と保存パス生成
                $filePath = 'blog_contents_images/' . $imageNames[$index];

                // 保存（storage/app/public/images に保存）
                Storage::disk('public')->put($filePath, $imageData);

            }
        }
    }

    /**
     * ブログコンテンツ画像削除,Storageから削除
     * @param $targetImageName
     * @return $result
     */
    public function deleteBlogContentsImageFromStorage($blogUniqueId) {
        if($blogUniqueId != null && $blogUniqueId != '') {
            $allFiles = Storage::disk('public')->allFiles('blog_contents_images');
            $targetImageFileNames = array_filter($allFiles, function($file) use ($blogUniqueId) {
                if(str_contains($file, $blogUniqueId)) {
                    return $file;
                }
            });

            if($targetImageFileNames) {
                foreach($targetImageFileNames as $targetImageFileName) {
                    Storage::disk('public')->delete($targetImageFileName);
                }
            }
        }
    }
}