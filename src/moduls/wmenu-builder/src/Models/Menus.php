<?php

namespace Codeman\Admin\Menu\Models;

use Codeman\Admin\Models\Language;
use Illuminate\Database\Eloquent\Model;

class Menus extends Model
{
    protected $table = 'menus';

    public function __construct( array $attributes = [] ){
    	//parent::construct( $attributes );
    	$this->table = config('menu.table_prefix') . config('menu.table_name_menus');
    }

    public static function byName($name)
    {
        return self::where('name', '=', $name)->first();
    }

    public static function byNameAndLang($name, $lang)
    {
        return self::where('name', '=', $name)->where('lang', $lang)->first();
    }

    public function menuItems()
    {
        return $this->hasMany('Codeman\Admin\Menu\Models\MenuItems', 'menu');
    }
    public static function getMenuWithItems($name, $lang)
    {
        return self::where('name', '=', $name)->where('lang', $lang)->has('menuItems')
        ->with(['menuItems' => function($q){
            $q->orderBy('sort', 'ASC');
        }])->first();
    }
}
