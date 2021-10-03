<?php

namespace Codeman\Admin\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use HasFactory;
    
    protected $fillable = [
    	'user_id',
    	'api_id',
    	'list',
    	'email',
    	'phone',
    	'first_name',
    	'last_name',
    	'active',
    	'unsubscribed_at',
    ];

    public function getDataFilter()
    {
        return [
            [
                'name'  => 'email',
                'label' => 'Email',
                'type'  => 'text',
                'is_relation' => false, 
            ],

        ];
    }
}
