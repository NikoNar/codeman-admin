<?php

namespace Codeman\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class EasyFeed extends Model
{
    protected $table = 'easy_feed'; 
    protected $fillable = ['brand_name', 'url'];
}
