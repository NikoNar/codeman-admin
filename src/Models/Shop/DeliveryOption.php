<?php

namespace Codeman\Admin\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class DeliveryOption extends Model
{
    protected $fillable = [
        'name',
        'title',
        'description',
        'zone_allowed',
        'price',
        'price_fee',
        'status',
        'order',
        'is_default',
        'logic'
    ];

    protected $casts = [
        'zone_allowed' => 'array'
    ];
}
