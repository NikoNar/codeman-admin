<?php

namespace Codeman\Admin\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;


    // protected $fillable = [     // NOT NEEDED FOR SEED
    //         'name' ,
    //         'email',
    //         'email_verified_at',
    //         'password',
    //         'remember_token',
    // ];

    public function roles() {
        return $this->belongsToMany('Codeman\Admin\Models\Role', 'user_role');
    }



    public function hasAnyRole($roles) {

        if(is_array($roles)) {
            foreach ($roles as $role) {
                if($this->hasRole($role)){
                    return true;
                }
            }
        } else {
            if($this->hasRole($roles)){
                return true;
            }
        }

        return false;
    }


    public function hasRole($role) {
        if($this->roles()->where('title', $role)->first()) {
            return true;
        }
         return false;
    }
}
