<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavoriteBlog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'blog_id',
    ];

}
