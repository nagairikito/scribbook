<?php

namespace App\Repositories;

use App\Const\AccountConst;
use Illuminate\Http\Request;
use App\Models\Advertisement;
use App\Models\Article;

use function Laravel\Prompts\select;

class AdvertisementRepository extends Repository
{
    public $advertisement;
    public $article;
    
    public function __construct() {
        // Modelのインスタンス化
        $this->advertisement = new Advertisement;
        $this->article = new Article;

    }

    /**
     * 広告の登録
     */
    public function registerAdvertisement($inputData) {
        $this->advertisement->advertisement_image_name = $inputData['advertisement_image_name'];
        $this->advertisement->url = $inputData['url'];
        $this->advertisement->created_by = $inputData['created_by'];
        $this->advertisement->blog_id = $inputData['blog_id'];
        $result = $this->advertisement->save();

        return $result;
    }

    /**
     * 広告の削除
     */
    public function deleteAdvertisement($id) {
        $result = $this->advertisement->where('id', $id)->delete();

        return $result;
    }

    /**
     * 広告登録済み確認
     */
    public function checkRegisteredAdvertisement($blogId) {
        $result = $this->advertisement
        ->join('articles', 'articles.id', 'advertisements.blog_id')
        ->where('advertisements.blog_id', $blogId)
        ->count();

        return $result;
    }

    /**
     * ブログに紐づく広告を取得
     */
    public function getAdvertisementByBlogId($blogId) {
        $advertisement = $this->advertisement->where('blog_id', $blogId)->get();

        return !empty($advertisement) ? $advertisement->toArray() : [];
    }

    /**
     * IDによるによる広告を取得
     */
    public function getAdvertisementById($id) {
        $advertisement = $this->advertisement->where('id', $id)->get();

        return !empty($advertisement) ? $advertisement->toArray() : [];
    }
}
