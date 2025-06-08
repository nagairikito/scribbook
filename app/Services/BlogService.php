<?php

namespace App\Services;

use App\Repositories\BlogRepository;
use App\Repositories\BlogCommentRepository;
use App\Repositories\BrowsingHistoryRepository;
use App\Repositories\AccountRepository;
use App\Repositories\AdvertisementRepository;
use App\Repositories\FavoriteBlogRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PhpParser\ErrorHandler\Throwing;

class BlogService extends Service
{
    public $accountRepository;
    public $blogRepository;
    public $blogCommentRepository;
    public $favoriteBlogRepository;
    public $BrowsingHistoryRepository;
    public $advertisementRepository;

    public function __construct(AccountRepository $accountRepository, BlogRepository $blogRepository, BlogCommentRepository $blogCommentRepository
                                , FavoriteBlogRepository $favoriteBlogRepository, BrowsingHistoryRepository $browsingHistoryRepository, AdvertisementRepository $advertisementRepository) {
        $this->accountRepository = $accountRepository;
        $this->blogRepository = $blogRepository;
        $this->blogCommentRepository = $blogCommentRepository;
        $this->favoriteBlogRepository = $favoriteBlogRepository;
        $this->BrowsingHistoryRepository = $browsingHistoryRepository;
        $this->advertisementRepository = $advertisementRepository;
    }
    
    /**
     * ブログ登録
     * @param array $inputData
     * @return bool $blogPostStatus
     */
    public function postBlog($inputData) {
        $postFlag = false;

        if(!Auth::user() || Auth::id() != $inputData['user_id']) {
            return $postFlag;
        }

        $created_by = $this->accountRepository->existsAccountById($inputData['user_id']);
        if(!$created_by) {
            return $postFlag;
        }

        DB::beginTransaction();
        try {
            $checkPostBlog = $this->blogRepository->postBlog($inputData);
            if($checkPostBlog) {
                $postFlag = true;
            }
            if($inputData['base64_texts'] != [] || $inputData['base64_texts'] != null || $inputData['image_file_names'] != [] || $inputData['image_file_names'] != null) {
                $this->storeBase64Image($inputData['base64_texts'], $inputData['image_file_names']);
            }

            DB::commit();
            return $postFlag;

        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
        }    
    }

    /**
     * ブログ編集
     * @param array $inputData
     * @return bool $result
     */
    public function editBlog($inputData) {
        $editBlogFlag = false;

        if(!Auth::user() || Auth::id() != $inputData['created_by']) {
            return $editBlogFlag;
        }

        $created_by = $this->accountRepository->existsAccountById($inputData['created_by']);
        if(!$created_by) {
            return $editBlogFlag;
        }

        $targetBlog = $this->blogRepository->getBlogDetail($inputData['id']);
        if(!$targetBlog) {
            return $editBlogFlag;
        }

        DB::beginTransaction();
        try {
            // $this->deleteBlogContentsImageFromStorage($targetBlog[0]['blog_unique_id']);
            $this->updateBase64Image($inputData['base64_texts'], $inputData['image_file_names'], $targetBlog['blog_unique_id']);
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
     * @param array $inputData
     * @return bool $result
     */
    public function deleteBlog($inputData) {
        $deleteBlogFlag = false;

        if(!Auth::user() || Auth::id() != $inputData['created_by']) {
            return $deleteBlogFlag;
        }

        $existsCreatedUserFlag = $this->accountRepository->existsAccountById($inputData['created_by']);
        if(!$existsCreatedUserFlag) {
            return $deleteBlogFlag;
        }
        
        $targetBlog = $this->blogRepository->getBlogDetail($inputData['id']);
        if(!$targetBlog) {
            return $deleteBlogFlag;
        }

        DB::beginTransaction();
        try {
            $checkDeleteBlog = $this->blogRepository->deleteBlog($targetBlog['id']);
            if($checkDeleteBlog) {
                $this->deleteBlogContentsImageFromStorage($targetBlog['blog_unique_id']);
                $deleteBlogFlag = true;
            }
    
            DB::commit();
            return $deleteBlogFlag;

        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
        }    
    }

    /**
     * ブログ全件取得
     * @return array $allBlogs
     */
    public function getAllBlogs() {
        $allBlogs = $this->blogRepository->getAllBlogs();
        return $allBlogs;
    }

    /**
     * トピックス取得
     * @return array $topics
     */
    public function getTopics() {
        $topics = $this->blogRepository->getTopics();
        return $topics;
    }

    /**
     * ブログIDをもとにユーザーIDに紐づくブログを単一取得
     * @param array $userId
     * @return array $blogs
     */
    public function getBlogByBlogIdAndUserId($inputData) {

        $loginUser = $this->accountRepository->getAccountById($inputData['user_id']);
        if(!$loginUser) {
            return [];
        }

        $blogs = $this->blogRepository->getBlogWithUserById($inputData['id']);
        return $blogs;
    }

    /**
     * ユーザーIDに紐づくブログを全件取得
     * @param int $userId
     * @return array $blogs
     */
    public function getBlogsByUserId($userId) {

        $targetUser = $this->accountRepository->existsAccountById($userId);
        if(!$targetUser) {
            return [];
        }

        $blogs = $this->blogRepository->getBlogsByUserId($userId);
        return $blogs;
    }

    /**
     * ユーザーIDをもとにお気に入り登録したユーザーのブログ全件取得
     * @param int $userId
     * @return array $blogs
     */
    public function getBlogPostedByFavoriteUserByUserId($userId) {

        $targetUser = $this->accountRepository->existsAccountById($userId);
        if(!$targetUser) {
            return [];
        }

        $getBlogPostedByFavoriteUser = $this->blogRepository->getBlogPostedByFavoriteUserByUserId($userId);
        return $getBlogPostedByFavoriteUser;
    }

    /**
     * ブログ詳細取得
     * @param int $id
     * @return array $blog
     */
    public function getBlogDetail($id) {
        $blog = $this->blogRepository->getBlogDetail($id);
        $blog['contents'] = html_entity_decode($blog['contents']);

        // 閲覧処理（閲覧数増加、閲覧履歴の登録）
        $this->blogRepository->increaseViewCount($blog['id']);
        if(Auth::id()) {
            $this->BrowsingHistoryRepository->upsertBrowsingHistory(Auth::id() ,$blog['id']);
        }

        $blog['favorite_flag'] = false;
        if(Auth::user()) {
            $checkFavoriteBlog = $this->checkExsitsFavoriteBlogByBlogIdAndUserId(Auth::id(), $blog['id']);
            if($checkFavoriteBlog) {
                $blog['favorite_flag'] = true;
            }
        }

        return $blog;
    }

    /**
     * ブログに紐づくコメントを取得
     * @param int $id
     * @return array $comments
     */
    public function getBlogComments($id) {
        $comments = $this->blogCommentRepository->getBlogComments($id);

        return $comments;
    }

    /**
     * ブログに紐づく広告を取得
     * @param int $id
     * @return array $comments
     */
    public function getAdvertisementByBlogId($id) {
        $advertisement = $this->advertisementRepository->getAdvertisementByBlogId($id);

        return $advertisement;
    }

    /**
     * 対象ユーザーIDに紐づくお気に入り登録されたブログを全件取得
     * @param int $id
     * @return array $favoriteBlogs
     */
    public function getAllFavoriteBlogsByUserId($id) {
        if(!Auth::user() || Auth::id() != $id) {
            return [];
        }
        
        $checkExistsUser = $this->accountRepository->existsAccountById($id);
        if(!$checkExistsUser) {
            return [];
        }

        $favoriteBlogs = $this->favoriteBlogRepository->getAllFavoriteBlogsByUserId($id);
        return $favoriteBlogs;
    }

    /**
     * ユーザーIDとブログIDをもとに対象のユーザーが対象のブログがお気に入り登録しているかを判定
     * @param int $userId
     * @param int $blogId
     * @return bool $result
     */
    public function checkExsitsFavoriteBlogByBlogIdAndUserId($userId, $blogId) {

        $checkExistsUser = $this->accountRepository->getAccountById($userId);
        if(!$checkExistsUser) {
            return false;
        }

        $inputData = [
            'user_id' => $userId,
            'blog_id' => $blogId,
        ];
        $result = $this->favoriteBlogRepository->checkExsitsFavoriteBlogByBlogIdAndUserId($inputData);
        
        return $result;
    }
    
    /**
     * ブログコメント登録
     * @param array $inputData
     * @return bool $result
     */
    public function postComment($inputData) {
        $postCommentStauts = false;

    
        if(!Auth::user() || Auth::id() != $inputData['created_by']) {
            return $postCommentStauts;
        }

        $loginUser = $this->accountRepository->existsAccountById($inputData['created_by']);
        if(!$loginUser) {
            return $postCommentStauts;
        }
    
        DB::beginTransaction();
        try {
            $result = $this->blogCommentRepository->postComment($inputData);
            if($result) {
                $postCommentStauts = true;
            }
    
            DB::commit();
            return $postCommentStauts;

        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
        }
    }

    /**
     * ブログお気に入り登録
     * @param array $inputData
     * @return bool $result
     */
    public function registerFavoriteBlog($inputData) {
        $registerFavoriteBlogStatus = false;

        if(!Auth::user() || Auth::id() != $inputData['user_id']) {
            return $registerFavoriteBlogStatus;
        }

        $targetUser = $this->accountRepository->existsAccountById($inputData['user_id']);
        if(!$targetUser) {
            return $registerFavoriteBlogStatus;
        }

        $targetBlog = $this->blogRepository->getBlogDetail($inputData['blog_id']);
        if(!$targetBlog) {
            return $registerFavoriteBlogStatus;
        }

        $exsitsFavoriteBlogFlag = $this->favoriteBlogRepository->checkExsitsFavoriteBlogByBlogIdAndUserId($inputData);
        if($exsitsFavoriteBlogFlag) {
            return $registerFavoriteBlogStatus;
        }
    
        DB::beginTransaction();
        try {    
            $result = $this->favoriteBlogRepository->registerFavoriteBlog($inputData);
            if($result == true) {
                $registerFavoriteBlogStatus = true;
            }
        
            DB::commit();
            return $registerFavoriteBlogStatus;

        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
        }    
    }

    /**
     * ブログお気に入り登録解除
     * @param $inputData
     * @return $result
     */
    public function deleteFavoriteBlog($inputData) {
        $deleteFavoriteBlogStatus = false;

        if(!Auth::user() || Auth::id() != $inputData['user_id']) {
            return $deleteFavoriteBlogStatus;
        }

        $targetUser = $this->accountRepository->existsAccountById($inputData['user_id']);
        if(!$targetUser) {
            return $deleteFavoriteBlogStatus;
        }

        $targetBlog = $this->blogRepository->getBlogDetail($inputData['blog_id']);
        if(!$targetBlog) {
            return $deleteFavoriteBlogStatus;
        }

        $checkExsitsFavoriteBlog = $this->favoriteBlogRepository->checkExsitsFavoriteBlogByBlogIdAndUserId($inputData);
        if(!$checkExsitsFavoriteBlog) {
            return $deleteFavoriteBlogStatus;
        }
    
        DB::beginTransaction();
        try {    
            $result = $this->favoriteBlogRepository->deleteFavoriteBlog($inputData);
            if($result == true) {
                $deleteFavoriteBlogStatus = true;
            }
    
            DB::commit();
            return $deleteFavoriteBlogStatus;

        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
        }    
    }

    /**
     * 閲覧履歴表示
     * @param array $inputData
     * @return array $browsingHistory
     */
    public function getBrowsingHistory($inputData) {
        $targetUser = $this->accountRepository->existsAccountById($inputData['user_id']);
        if(!$targetUser) {
            return false;
        }

        $browsingHistory = $this->BrowsingHistoryRepository->getBrowsingHisotryByUserId($inputData['user_id']);
        return $browsingHistory;

    }

    /**
     * 広告の登録
     * @param array $inputData
     * @return bool $flag
     */
    public function registerAdvertisement($inputData) {
        $flag = false;

        $targetUser = $this->accountRepository->existsAccountById($inputData['created_by']);
        if(!$targetUser) {
            return $flag;
        }

        $targetBlog = $this->blogRepository->getBlogDetail($inputData['blog_id']);
        $RegisteredAdvertisementCount = $this->advertisementRepository->getRegisteredAdvertisementCount($targetBlog['id']);

        if(!$targetBlog || $RegisteredAdvertisementCount > 0) {
            return $flag;
        }

        DB::beginTransaction();
        try {
            if($inputData['advertisement_image_file'] || $inputData['advertisement_image_file'] != null) {
                $image_name = $this->upsertAdvertisementImageIntoStorage($inputData['advertisement_image_file'], null);
                $inputData['advertisement_image_name'] = $image_name;
                $flag = $this->advertisementRepository->registerAdvertisement($inputData);
            }

            DB::commit();
            return $flag;

        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
        }    
    }

    /**
     * 広告の削除(DB)
     * @param array $inputData
     * @return bool $flag
     */
    public function deleteAdvertisement($inputData) {
        $flag = false;

        $targetUser = $this->accountRepository->existsAccountById($inputData['created_by']);
        if(!$targetUser || Auth::id() != $inputData['created_by']) {
            return $flag;
        }

        $targetBlog = $this->blogRepository->getBlogDetail($inputData['blog_id']);
        $RegisteredAdvertisementCount = $this->advertisementRepository->getRegisteredAdvertisementCount($targetBlog['id']);

        if(!$targetBlog || $RegisteredAdvertisementCount == 0) {
            return $flag;
        }

        $targetAdvertisement = $this->advertisementRepository->getAdvertisementById($inputData['id']);

        DB::beginTransaction();
        try {
            if($inputData['advertisement_image_name'] && 
                $inputData['advertisement_image_name'] != null &&
                count($targetAdvertisement) != 0) {
                    $deleteImageFlag = $this->deleteAdvertisementImageFromStorage($targetAdvertisement['advertisement_image_name']);
                    $deleteDbFlag = $this->advertisementRepository->deleteAdvertisement($targetAdvertisement['id']);
                    if($deleteImageFlag == false || $deleteDbFlag == false) {
                        throw new \Exception('広告の削除に失敗しました');
                    }
                    $flag = true;
            }

            DB::commit();
            return $flag;

        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
        }    
    }

    /**
     * 広告画像保存,Storageに保存
     * 画像保存後にStorageに保存されている画像名を返す
     * @param object $iconImageFile
     * @param string $oldImageName
     * @return string $newImageName
     */
    public function upsertAdvertisementImageIntoStorage($iconImageFile, $oldImageName) {
        DB::beginTransaction();
        try {    
            if($oldImageName && $oldImageName != null) {
                $deleteResult = $this->deleteAdvertisementImageFromStorage($oldImageName);
                if($deleteResult == false) {
                    throw new \Exception('エラーが発生しました。');
                }
            }

            $newImageName = 'noImage.png';
            if($iconImageFile && $iconImageFile!= null) {
                $originalName = $iconImageFile->getClientOriginalName();
                $newImageName = date('Ymd_His') . '_' . $originalName;
        
                // Storage::disk('public')->putFileAs('advertisement_image_file', $iconImageFile->file('advertisement_image_file'), $newImageName);    
                $registerResult = Storage::disk('public')->putFileAs('advertisement_images', $iconImageFile, $newImageName);
                if($registerResult == false) {
                    throw new \Exception('ファイルの登録に失敗しました。');
                }
            }
    
            DB::commit();
            return $newImageName;

        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            throw $e;
        }
    }

    /**
     * 広告画像削除,Storageから削除
     * @param string $targetImageName
     * @return bool
     */
    public function deleteAdvertisementImageFromStorage($targetImageName) {
        DB::beginTransaction();
        try {
            if($targetImageName != null && $targetImageName != 'noImage.png') {
                $targetImageNameExists = Storage::disk('public')->exists('advertisement_images/'. $targetImageName);
                if($targetImageNameExists) {
                    $result = Storage::disk('public')->delete('advertisement_images/'. $targetImageName);
                    if($result == false) {
                        throw new \Exception('画像の削除に失敗しました。');
                    }
                }
            }
    
            DB::commit();
            return $result;
            
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            throw $e;
        }
    }

    /**
     * ブログコンテンツ画像保存,Storageに保存(更新用)
     * @param array $imageBase64Texts
     * @param object $imageNames
     * @param string $prevBlogUniqueId
     * @return void
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
                    $result = Storage::disk('public')->put($filePath, $imageData);
                    if($result == false) {
                        throw new \Exception('画像の保存に失敗しました');
                    }
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
                            $changeNameFlag = Storage::disk('public')->move($fileName, 'blog_contents_images/' . $imageNames[$index]);
                            if($changeNameFlag == false) {
                                throw new \Exception('画像名の更新に失敗しました');
                            }
                        }
                    }
                }
            }
            $this->deleteBlogContentsImageFromStorage($prevBlogUniqueId);
        }
    }

    /**
     * ブログコンテンツ画像保存,Storageに保存(新規用)
     * @param array $targetImageName
     * @param string $imageNames
     * @return void
     */
    public function storeBase64Image($imageBase64Texts, $imageNames)
    {
        if($imageBase64Texts == null || count($imageBase64Texts) > 0) {
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
                $result = Storage::disk('public')->put($filePath, $imageData);
                if($result == false) {
                    throw new \Exception('画像の保存に失敗しました');
                }
            }
        }
    }

    /**
     * ブログコンテンツ画像削除,Storageから削除
     * @param string $targetImageName
     * @return void
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
                    $result = Storage::disk('public')->delete($targetImageFileName);
                    if($result == false) {
                        throw new \Exception('ファイルの削除に失敗しました。');
                    }
                }
            }
        }
    }
}