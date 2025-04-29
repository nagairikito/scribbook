<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
        /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'contents',
        'image',
        'created_by'
    ];

}
