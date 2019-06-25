<?php

namespace Codeman\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    protected $fillable = [
    	'product_part_number','feed_product_id', 'variation_id', 'color', 'size', 'images'
    ];

    public function feed_product()
    {
        return $this->belongsTo('Codeman\Admin\Models\FeedProduct', 'part_number', 'product_part_number' );
    }
}
