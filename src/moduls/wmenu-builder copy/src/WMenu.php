<?php

namespace Codeman\Admin\Menu;

use App\Http\Requests;
use Codeman\Admin\Models\Language;
use Codeman\Admin\Models\Page;
use Codeman\Admin\Menu\Models\Menus;
use Codeman\Admin\Menu\Models\MenuItems;
use Illuminate\Support\Facades\DB;

class WMenu
{

    public function render()
    {
        $menu = new Menus();
        $menuitems = new MenuItems();
        $menulist = $menu->select(DB::raw('CONCAT(name,"(", lang, ")") AS name, id'))->get();
        $menulist = $menulist->pluck('name', 'id')->prepend('Select menu', 0)->all();

        $languages = Language::orderBy('order')->pluck('name','code')->toArray();

//        if ((request()->has("action") && empty(request()->input("menu"))) || request()->input("menu") == '0') {
//            return view('vendor.harimayco-menu.menu-html')->with(["menulist" => $menulist, 'languages' => $languages]);
//        } else {
//            $menu = Menus::find(request()->input("menu"));
//            $menus = $menuitems->getall(request()->input("menu"));
//            $pages = Page::where('lnaguage_id', $menu->lang)->get();
//
//            $data = ['menus' => $menus, 'indmenu' => $menu, 'menulist' => $menulist, "pages" => $pages];
//            return view('vendor.harimayco-menu.menu-html', $data, compact('languages'));
//        }

        if ((request()->has("action") && empty(request()->input("menu"))) || request()->input("menu") == '0') {
            return view('vendor.harimayco-menu.menu-html')->with(["menulist" => $menulist, 'languages' => $languages]);
        } else {
            if(!empty(request()->input("language"))){
                $original = Menus::find(request()->input("menu"));
                $menu = Menus::where('lang', request()->input("language"))->where(function($q) use($original){
                  $q->where('parent_lang_id', request()->input("menu"))->orWhere('id', $original->parent_lang_id);
                })->first();
                if(!$menu){
                    $menu = Menus::where('parent_lang_id', $original->parent_lang_id)->where('lang', request()->input("language"))->first();
                }
//                $menus = $menuitems->getall(request()->input("menu"));
                if(!$menu){
                    $menu = new Menus();
                    $menu->name = $original->name;
                    $menu->parent_lang_id = $original->parent_lang_id ? $original->parent_lang_id : $original->id  ;
                    $menu->lang = request()->input("language");
                    $menu->save();
                    $menus = null;
                }
                $menus = $menuitems->getall($menu->id);

            } else {
                $menu = Menus::find(request()->input("menu"));
                $menus = $menuitems->getall(request()->input("menu"));
            }
            if($menu){
                $pages = Page::where('lang', $menu->lang)->get();
            } else {
                $pages = null;
            }
            $data = ['menus' => $menus, 'indmenu' => $menu, 'menulist' => $menulist, "pages" => $pages];
            return view('vendor.harimayco-menu.menu-html', $data, compact('languages'));
        }

    }

    public function scripts()
    {
        return view('vendor.harimayco-menu.scripts');
    }

    public function select($name = "menu", $menulist = array())
    {
        $html = '<select name="' . $name . '">';

        foreach ($menulist as $key => $val) {
            $active = '';
            if (request()->input('menu') == $key) {
                $active = 'selected="selected"';
            }
            $html .= '<option ' . $active . ' value="' . $key . '">' . $val . '</option>';
        }
        $html .= '</select>';
        return $html;
    }

    public static function getByName($name)
    {
        if(Menus::byName($name)){
            $menu_id = Menus::byName($name)->id;
            return self::get($menu_id);
        }
    }

    public static function getByNameAndLang($name, $lang)
    {
        if(Menus::byNameAndLang($name, $lang)){
            $menu_id = Menus::byNameAndLang($name, $lang)->id;
//            dd(self::get($menu_id));
            return self::get($menu_id);
        }
    }

    public static function get($menu_id)
    {
        $menuItem = new MenuItems;
        $menu_list = $menuItem->getall($menu_id);

        $roots = $menu_list->where('menu', (integer) $menu_id)->where('parent', 0);

        $items = self::tree($roots, $menu_list);
        return $items;
    }

    private static function tree($items, $all_items)
    {
        $data_arr = array();
        $i = 0;
        foreach ($items as $item) {
            $data_arr[$i] = $item->toArray();
            $find = $all_items->where('parent', $item->id);

            $data_arr[$i]['child'] = array();

            if ($find->count()) {
                $data_arr[$i]['child'] = self::tree($find, $all_items);
            }

            $i++;
        }

        return $data_arr;
    }

}
