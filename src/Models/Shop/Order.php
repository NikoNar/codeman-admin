<?php

namespace Codeman\Admin\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Codeman\Admin\OrderStatus\StatusEnum;
use App\Models\OrderHistory;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'billing_first_name',
        'billing_last_name',
        'billing_name',
        'billing_phone',
        'billing_email',
        'billing_country',
        'billing_city',
        'billing_state',
        'billing_address',
        'billing_address_building',
        'billing_address_apartment',
        'billing_zip_code',
        'ship_to_another_person',
        'shipping_first_name',
        'pickup_location_id',
        'shipping_last_name',
        'shipping_phone',
        'shipping_email',
        'shipping_country',
        'shipping_city',
        'shipping_state',
        'shipping_address',
        'shipping_address_building',
        'shipping_address_apartment',
        'shipping_zip_code',
        'order_note',
        'shipping_type',
        'shipping_price',
        'shipping_tax',
        'shipping_fee',
        'payment_type',
        'payment_tax',
        'payment_fee',
        'discount_card',
        'discount_percent',
        'subtotal',
        'total',
        'promo_code',
        'status',
        'status_message',
        'is_mail_sent',
        'order_history_status_message',
        'tracking_number',
        'shipping_address_id',
        'cart_discount_rules'
    ];

    protected $appends = [
        'status_label',
        'status_label_class',
        'status_text_color_class',
        'full_name',
    ]; 

    protected $casts = [
        'cart_discount_rules' => 'array',
    ];

    public function getSearchableFields()
    {
        return [
            'billing_first_name',
            'billing_last_name',
            'billing_phone',
            'billing_email',
            'shipping_phone',
            'shipping_email',
            'shipping_country',
            'shipping_city',
            'shipping_state',
            'shipping_address',
            'shipping_type',
            'payment_type',
            'discount_card',
            'subtotal',
            'total',
            'status',
            'created_at'
        ];
    }

    // Relations
    public function transactions()
    {
        return $this->hasMany('Codeman\Admin\Models\Shop\Transaction');
    }
    public function latestTransaction()
    {
    return $this->hasOne('Codeman\Admin\Models\Shop\Transaction')->latestOfMany();
    }

    public function items()
    {
        return $this->hasMany('\Codeman\Admin\Models\Shop\OrderItem');
    }

    public function discountcard()
    {
        return $this->hasOne('Codeman\Admin\Models\Shop\DiscountCard', 'code', 'discount_card');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function payment_method()
    {
        return $this->hasOne('Codeman\Admin\Models\Shop\PaymentMethods', 'id' , 'payment_type');
    }

    public function delivery_option()
    {
        return $this->hasOne('Codeman\Admin\Models\Shop\DeliveryOption','id','shipping_type');
    }

    public function pickuplocation()
    {
        return $this->hasOne('App\Models\PickupLocation','id','pickup_location_id');
    }

    public function order_status_cancelation()
    {
        return $this->hasOne('App\Models\OrderStatusCancellation');
    }


    public function history()
    {
        return $this->morphMany('App\Models\OrderHistory','historiable');
    }
 
    
    public function getFullNameAttribute() {
        return ucfirst($this->billing_first_name) . ' ' . ucfirst($this->billing_last_name);
    }

    public static function boot() {
        parent::boot();

        static::created( function($resource) { // before create() method call this
            OrderHistory::create([
                'order_id' => $resource->id,
                'user_id' => auth()->check() ? auth()->id() : (!empty($resource->user_id) ? $resource->user_id : null),
                'session_id' => $resource->session_id,
                'historiable_id' => $resource->id,
                'historiable_type' => 'Codeman\Admin\Models\Shop\Order',
                'message_info' => $resource->order_history_status_message,
                // 'additional_info' => $resource,
                'additional_info' => '',
            ]); 
        });

        static::updated( function($resource) { // before update() method call this

            $old_status = null;
            $statusMesssage = null;
            if(!empty($resource->changes['status']))
            {
                $old_status = $resource->changes['status'];
                $user = auth()->check() ? auth()->id() : (!empty($resource->user_id) ? $resource->user_id : null);    
                $statusMesssage = 'Order status was changed from <strong>'.(new self)->getStatusLabel($resource->getRawOriginal('status')).' to '.(new self)->getStatusLabel($old_status).'</strong>. ';
                // Additional status info - '.$resource->order_history_status_message;
            }
            
            OrderHistory::create([
                'order_id' => $resource->id,
                'user_id' => auth()->check() ? auth()->id() : (!empty($resource->user_id) ? $resource->user_id : null),
                'session_id' => $resource->session_id,
                'historiable_id' => $resource->id,
                'historiable_type' => 'Codeman\Admin\Models\Shop\Order',
                'message_info' => !empty($old_status) ? $statusMesssage : $resource->order_history_status_message,
                // 'additional_info' => $resource,
                'additional_info' => '',
            ]); 
        });

        static::deleted( function($resource) { // before delete() method call this
            OrderHistory::create([
                'order_id' => $resource->id,
                'user_id' => auth()->check() ? auth()->id() : (!empty($resource->user_id) ? $resource->user_id : null),
                'session_id' => $resource->session_id,
                'historiable_id' => $resource->id,
                'historiable_type' => 'Codeman\Admin\Models\Shop\Order',
                'message_info' => 'Order deleted',
                // 'additional_info' => $resource,
                'additional_info' => '',
            ]); 
        });
    }

    // Attributes Casting functions

    public function getBillingNameAttribute()
    {
        return $this->attributes['billing_first_name'].' '.$this->attributes['billing_last_name'];
    }

    public function getStatusLabelAttribute()
    {
        return StatusEnum::STATUSES[$this->attributes['status']];
    }

    public function getStatusLabelClassAttribute()
    {
        return StatusEnum::ORDER_STATUS_LABEL_CLASSES[$this->attributes['status']];
    }

    public function getStatusTextColorClassAttribute()
    {
        return StatusEnum::ORDER_STATUS_TEXT_COLOR[$this->attributes['status']];
    }

    public static function recalculateOrderPrice($order_id)
    {
        
        $order = self::where('id',$order_id)->with('items')->first();
        $order_subtotal = 0;
        $order_total = 0;
        
        foreach($order->items as $o_item)
        {
            $order_subtotal = (($o_item->sale_price == null) ? $o_item->price : $o_item->sale_price);
            $order_total +=  $order_subtotal;
        }

        $order_total += $order->shipping_price;
        $order->total = $order_total;
        $order->subtotal = $order_subtotal;

        if($order->save())
        {
            return true;
        }
        return false;
    }
  
    public function getStatusLabel($status)
    {
        return StatusEnum::STATUSES[$status];
    }

}
