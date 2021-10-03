<?php

namespace Codeman\Admin\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use App\Models\OrderHistory;
use Illuminate\Support\Facades\Cookie;

class Transaction extends Model
{
    protected $fillable = [
    	'order_id',
    	'payment_id',
    	'merchant',
    	'merchant_id',
    	'amount',
    	'currency',
    	'currency_code',
    	'description',
    	'back_url',
    	'additional_data',
    	'card_holder_id',
    	'status',
    	'status_message',
    	'response_code',
    	'response_message',
    	'transaction_id',
    ];

    // public function order()
    // {
    // 	return $this->belongsTo('\Codeman\Admin\Models\Shop\Order');
    // }

	public function history()
    {
        return $this->morphMany('App\Models\OrderHistory','historiable');
    }

	public static function boot() {
        parent::boot();

        static::created( function($resource) { // before create() method call this

			$user_session_id = Cookie::get('user_session_id');   
			OrderHistory::create([
				'order_id' => $resource->order_id,
				'user_id' => !empty(auth()->id()) ? auth()->id() : null,
				'session_id' => $user_session_id,
				'historiable_id' => $resource->id,
				'historiable_type' => 'Codeman\Admin\Models\Shop\Transaction',
				'additional_info' => $resource,
				'message_info' => $resource->status_message,
			]);
        });

		static::updated( function($resource) { // before update() method call this

			$user_session_id = Cookie::get('user_session_id');   
			OrderHistory::create([
				'order_id' => $resource->order_id,
				'user_id' => !empty(auth()->id()) ? auth()->id() : null,
				'session_id' => $user_session_id,
				'historiable_id' => $resource->id,
				'historiable_type' => 'Codeman\Admin\Models\Shop\Transaction',
				'additional_info' => $resource,
				'message_info' => $resource->status_message,
			]);
        });


    }

	
}
