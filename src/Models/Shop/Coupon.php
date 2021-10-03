<?php

namespace Codeman\Admin\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    
    public $fillable = [
        'user_id',
        'code',
        'discount',
        'type',
        'status',
        'start_date',
        'end_date',
        'customer_email',
        // 'is_multiple_use',
        'usage_limit',
        'items_usage_limit',
        'user_usage_limit',
        'creator_id',
        'message',
        'min_spend_amount',
        'max_spend_amount',
        'exclude_sale_items',
        'individual_use_only',
    ];

    public static function findByCode($code)
    {
        return self::where('code', $code)->first();
    }

    public function discount($total)
    {
        if($this->type == 'amount'){
            return $this->value;
        } elseif ($this->type == 'percent'){
            return ($this->percent_off / 100) * $total;
        } else{
            return 0;
        }
    }

    /**
     * Get all of the products that are assigned this coupon.
     */
    public function products()
    {
        return $this->morphedByMany(\Codeman\Admin\Models\Shop\Product::class, 'couponable');
    }

    /**
     * Get all of the categories that are assigned this coupon.
     */
    public function categories()
    {
        return $this->morphedByMany(\Codeman\Admin\Models\Category::class, 'couponable');
    }

}
