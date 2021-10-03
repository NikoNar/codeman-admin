<?php
namespace Codeman\Admin\Models;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{

    use Notifiable, HasRoles;
    protected $guard = 'admin';

    protected $fillable = [     // NOT NEEDED FOR SEED
        'name' ,
        'email',
        'email_verified_at',
        'password',
        'remember_token',
    ];

    public function cart(){
        return $this->hasMany('\Codeman\Admin\Models\Shop\Cart')->where('cart_type', 'cart');
    }
    public function wishlist(){
        return $this->hasMany('\Codeman\Admin\Models\Shop\Cart')->where('cart_type', 'wishlist');
    }

    public function cartSum(){
        $this->cart()->sum('qty');
    }

    public function orders()
    {
        return $this->hasMany('\Codeman\Admin\Models\Shop\Order');
    }

    public function balance()
    {
        return $this->hasMany('App\Models\UserBalance');
    }

    public function balanceAmount(){
        return $this->balance()->sum('amount'); 
    }

    public function user_addresses()
    {
        return $this->hasMany('Codeman\Admin\Models\Shop\UserAddress');
    }
    
    public function getSearchableFields()
    {
        return [
            'first_name',
            'last_name',
            'email',
            'phone',
            'gender',
            'loyalty_card',
            'created_at'
        ];
    }

    public function getFullNameAttribute() {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }

//    public function roles() {
//        return $this->belongsToMany('Codeman\Admin\Models\Role', 'user_role');
//    }
//
//
//
//    public function hasAnyRole($roles) {
//
//        if(is_array($roles)) {
//            foreach ($roles as $role) {
//                if($this->hasRole($role)){
//                    return true;
//                }
//            }
//        } else {
//            if($this->hasRole($roles)){
//                return true;
//            }
//        }
//
//        return false;
//    }
//
//
//    public function hasRole($role) {
//        if($this->roles()->where('title', $role)->first()) {
//            return true;
//        }
//         return false;
//    }

}

