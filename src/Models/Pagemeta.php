<?php

namespace Codeman\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Pagemeta extends Model
{
    protected $table = 'pagemeta';
    protected $fillable = ['page_id', 'key', 'value'];
    public $timestamps = false;

}
