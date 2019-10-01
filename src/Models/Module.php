<?php

namespace Codeman\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = [ 'title', 'slug', 'status',  'module_type', 'icon', 'options', 'additional_options', 'relations', 'created_at'];


}
