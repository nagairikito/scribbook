<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Talk extends Model
{
    protected $table = 't_talks';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    // protected $fillable = [
    //     'message',
    //     'attached_file_path',
    //     'created_by',
    //     'talk_room_id',
    //     'delete_flag_1',
    //     'delete_flag_2',
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
