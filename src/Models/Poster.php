<?php

namespace Codeman\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Poster extends Model
{
    protected $fillable = ['lang','parent_lang_id','title','designer','status','thumbnail','year', 'order'];
}
