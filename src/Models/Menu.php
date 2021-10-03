<?php

namespace Codeman\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [ 'name', 'lang', 'parent_lang_menu_id', 'item_name', 'item_url', 'item_parent_id', 'item_target_blank', 'item_additional_class' ];
}
