<?php

namespace Codeman\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = ['title_en','title_arm', 'slug', 'status', 'thumbnail', 'year', 'images', 'created_at', 'order'];
}
