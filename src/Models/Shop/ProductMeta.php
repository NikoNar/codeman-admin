<?php

namespace Codeman\Admin\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductMeta extends Model
{
    use HasFactory;

	public $timestamps = false;
    protected $table = 'productmetas';
    
    protected $fillable = [
    	'product_id', 
    	'key', 
    	'value', 
    	'group', 
    	'order', 
    ];

    public function product()
    {
    	return belongsTo('Codeman\Admin\Models\Shop\Product');
    }
}
