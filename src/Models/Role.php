<?php

namespace  Codeman\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
	protected $fillable = ['title', 'description', 'order'];

    public function users() {
    	return $this->belongsToMany('Codeman\Admin\Models\User', 'user_role');
    }
}
