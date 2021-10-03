<?php

namespace Codeman\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Language extends Model
{
    use SoftDeletes;
    protected $table  = 'languages';
    protected $fillable = [
        'name',
        'code',
        'script',
        'native',
        'regional',
        'order',
        'flag'
    ];

    public function categories()
    {
        return $this->morphToMany('Codeman\Admin\Models\Category', 'categorisable');
    }
}
