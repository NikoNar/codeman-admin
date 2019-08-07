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
        $language_id = Language::where('code', $lang)->first()->id;
        return self::where('name', '=', $name)->where('language_id', $language_id)->first();
    }

}
