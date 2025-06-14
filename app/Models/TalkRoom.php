<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TalkRoom extends Model
{
    protected $table = 't_talk_rooms';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    // protected $fillable = [
    //     'user_id_1',
    //     'user_id_2',
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
