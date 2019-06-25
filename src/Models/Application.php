<?php
namespace Codeman\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
    	'name','surname','mobile','email','course','suggestion','image','materials', 'supplements', 'user_path'
    ];
 }
