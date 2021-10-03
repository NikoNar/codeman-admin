<?php

namespace Codeman\Admin\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class Tracker extends Model
{
    protected $table = 'tracker_views';

    protected $fillable = [
    	'resource',
    	'resource_id',
    	'parameter_key',
    	'parameter_value',
    	'model',
    	'url',
    	'user_id',
    	'session_id',
    	'referral_url',
    	'ip_address',
    	'geoip_id',
    	'lang',
    	'time',
    	// 'fingerprint'
    ];

    public function variations()
    {
        return $this->belongsTo('Codeman\Admin\Models\Shop\Variation', 'id', 'recource_id')
        ->where('resource', 'product');
    } 
}
