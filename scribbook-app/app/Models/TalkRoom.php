<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TalkRoom extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id_1',
        'user_id_2',
        'delete_flag_1',
        'delete_flag_2',
    ];

}
