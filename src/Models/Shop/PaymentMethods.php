<?php

namespace Codeman\Admin\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class PaymentMethods extends Model
{
    protected $fillable = [
        'name',
        'title',
        'description',
        'zone_allowed',
        'fee',
        'status',
        'order',
        'is_default',
        'logic'
    ];

    protected $casts = [
        'zone_allowed' => 'array'
    ];
}
