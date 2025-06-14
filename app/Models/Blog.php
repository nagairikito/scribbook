<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $table = 't_blogs';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    // protected $fillable = [
    //     'title',
    //     'contents',
    //     'image',
    //     'created_by',
    //     'view_count',
    // ];

    public function attributesToArray()
    {
        $attributes = parent::attributesToArray();

        if ($this->created_at) {
            $attributes['created_at'] = $this->created_at
                ->copy()->setTimezone('Asia/Tokyo')->format('Y/m/d H:i');
        }

        if ($this->updated_at) {
            $attributes['updated_at'] = $this->updated_at
                ->copy()->setTimezone('Asia/Tokyo')->format('Y/m/d H:i');
        }

        return $attributes;
    }

}
