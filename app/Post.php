<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $casts = ['featured' => 'boolean', 'image' => 'json'];
}
