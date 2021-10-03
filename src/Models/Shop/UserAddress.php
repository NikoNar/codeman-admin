<?php

namespace Codeman\Admin\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $fillable = [
    	'user_id',
    	'first_name',
    	'last_name',
    	'phone',
    	'email',
    	'country',
    	'city',
    	'state',
    	'address',
    	'address_building',
    	'address_apartment',
    	'zip_code',
    	'type',
		'address_note',
        'city_code',
    ];

	protected $appends = [
        'full_name',
    ];

	public function getFullNameAttribute() {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }

	public function user()
    {
        return $this->belongsTo('App\User');
    }

	public function getCountryByCode($data)
	{
		Config('countries-list'.$data);
	}





	public static function user_address($id)
	{
		return self::select('city','address','address_apartment','address_building')->where('user_id',$id)->first();
	}
}
