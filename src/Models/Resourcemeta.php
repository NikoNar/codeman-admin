<?php

namespace Codeman\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Resourcemeta extends Model
{
    protected $fillable = ['resource_id', 'key', 'value', 'order'];
    public $timestamps = false;
}
