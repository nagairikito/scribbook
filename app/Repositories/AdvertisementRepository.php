<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\Advertisement;

use function Laravel\Prompts\select;

class AdvertisementRepository extends Repository
{
    public $advertisement;
    
    public function __construct(Advertisement $advertisement) {
        // Modelのインスタンス化
        $this->advertisement = $advertisement;
    }

    /**
     * 広告の登録
     * @param arrau $intputData
     * @param bool $result
     */
    public function registerAdvertisement($inputData) {
        $this->advertisement->advertisement_image_name = $inputData['advertisement_image_name'];
        $this->advertisement->url = $inputData['url'];
        $this->advertisement->blog_id = $inputData['blog_id'];
        $this->advertisement->created_by = $inputData['created_by'];
        $this->advertisement->updated_by = $inputData['updated_by'];
        $result = $this->advertisement->save();

        return $result;
    }

    /**
     * 広告の削除
     * @param int $id
     * @return bool $result
     */
    public function deleteAdvertisement($id) {
        $result = $this->advertisement->where('id', $id)->delete();

        return $result;
    }

    /**
     * ブログに紐づく広告の数取得
     * @param int $blogId
     * @return int $count
     */
    public function getRegisteredAdvertisementCount($blogId) {
        $count = $this->advertisement
        ->join('t_blogs', 't_blogs.id', 't_advertisements.blog_id')
        ->where('t_advertisements.blog_id', $blogId)
        ->count();

        return $count;
    }

    /**
     * ブログに紐づく広告を取得
     * @param int $blogId
     * @return array $advertisement
     */
    public function getAdvertisementByBlogId($blogId) {
        $advertisement = $this->advertisement->where('blog_id', $blogId)->get();

        return !empty($advertisement) ? $advertisement->toArray() : [];
    }

    /**
     * IDによる広告の単一取得
     */
    public function getAdvertisementById($id) {
        $advertisement = $this->advertisement->where('id', $id)->first();

        return !empty($advertisement) ? $advertisement->toArray() : [];
    }
}
