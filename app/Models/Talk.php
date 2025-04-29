<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Talk extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'message',
        'attached_file_path',
        'created_by',
        'talk_room_id',
        'delete_flag_1',
        'delete_flag_2',
    ];

}
