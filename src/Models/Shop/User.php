<?php

namespace Codeman\Admin\Models\Shop;

use GuzzleHttp\Psr7\Request;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    // protected $connection = 'mysql';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'email', 
        'password',
        'first_name',
        'last_name',
        'phone',
        'dob',
        'gender',
        'receive_newsletter',
        'receive_sms',
        'provider',
        'provider_id',
        'loyalty_card',
        'user_birthdate',
    ];

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
    
    protected $appends = [
        'full_name',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getFullNameAttribute() {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }

    public function getNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function cart(){
        return $this->hasMany('App\Models\Cart')->where('cart_type', 'cart');
    }

    public function wishlist(){
        return $this->hasMany('App\Models\Cart')->where('cart_type', 'wishlist');
    }
    
    public function cartSum(){
        $this->cart()->sum('qty');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }
}
