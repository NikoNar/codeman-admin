<?php

namespace Codeman\Admin\Menu\Controllers;

use Codeman\Admin\Menu\Facades\Menu;
use Codeman\Admin\Models\Category;
use Codeman\Admin\Models\Language;
use Codeman\Admin\Models\Page;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Codeman\Admin\Menu\Models\Menus;
use Codeman\Admin\Menu\Models\MenuItems;

class MenuController extends Controller
{

    public function createnewmenu()
    {
        $menu = new Menus();
        $menu->name = request()->input("menuname");
        $menu->lang = request()->input("language");
        $menu->save();
        return json_encode(array("resp" => $menu->id, "language" =>$menu->lang));
    }

    public function deleteitemmenu()
    {
        $menuitem = MenuItems::find(request()->input("id"));

        $menuitem->delete();
    }

    public function deletemenug()
    {
        $menus = new MenuItems();
        $getall = $menus->getall(request()->input("id"));
        if (count($getall) == 0) {
            $menudelete = Menus::find(request()->input("id"));
            $menudelete->delete();

            return json_encode(array("resp" => "you delete this item"));
        } else {
            return json_encode(array("resp" => "You have to delete all items first", "error" => 1));

        }
    }

    public function updateitem()
    {
        $arraydata = request()->input("arraydata");
        if (is_array($arraydata)) {
            foreach ($arraydata as $value) {
                $menuitem = MenuItems::find($value['id']);
                $menuitem->label = $value['label'];
                $menuitem->link = $value['link'];
                $menuitem->class = $value['class'];
//                $menuitem->lang = $value['language'];

                $menuitem->save();
            }
        } else {
            $menuitem = MenuItems::find(request()->input("id"));
            $menuitem->label = request()->input("label");
            $menuitem->link = request()->input("url");
            $menuitem->class = request()->input("clases");
            $menuitem->save();
        }
    }

    public function addcustommenu()
    {
        if (request()->has('pages')) {
            foreach (request()->pages as $page) {
                $menuitem = new MenuItems();
                $menuitem->label = $page["labelmenu"];
                $menuitem->link = $page["linkmenu"];
                $menuitem->menu = $page["idmenu"];
                $menuitem->sort = MenuItems::getNextSortRoot($page["idmenu"]);
                $menuitem->save();
            }
        } else if (request()->has('categories')) {
            foreach (request()->categories as $category) {
                $menuitem = new MenuItems();
                $menuitem->label = $category["labelmenu"];
                $menuitem->link = $category["linkmenu"];
                $menuitem->menu = $category["idmenu"];
                $menuitem->sort = MenuItems::getNextSortRoot($category["idmenu"]);
                $menuitem->save();
            }
        } else {
            $menuitem = new MenuItems();
            $menuitem->label = request()->input("labelmenu");
            $menuitem->link = request()->input("linkmenu");
            $menuitem->menu = request()->input("idmenu");
            $menuitem->sort = MenuItems::getNextSortRoot(request()->input("idmenu"));
            $menuitem->save();
        };
    }

    public function generatemenucontrol()
    {
        $menu = Menus::find(request()->input("idmenu"));
        $menu->name = request()->input("menuname");
        $menu->lang = request()->input("language");
        $menu->save();
        if (is_array(request()->input("arraydata"))) {
            foreach (request()->input("arraydata") as $value) {

                $menuitem = MenuItems::find($value["id"]);
                $menuitem->parent = $value["parent"];
                $menuitem->sort = $value["sort"];
                $menuitem->depth = $value["depth"];
                $menuitem->save();
            }
        }
        echo json_encode(array("resp" => 1));

    }

    public function translate($id, $lang_id)
    {
        $menu = new Menus();
        $menuitems = new MenuItems();
        $menulist = $menu->select(['id', 'name'])->get();
        $menulist = $menulist->pluck('name', 'id')->prepend('Select menu', 0)->all();
        $pages = Page::all();
        $categories = Category::all();
        $languages = Language::orderBy('order')->pluck('name','code')->toArray();



            $menu = Menus::find($id);
            $menus = $menuitems->getall(request()->input("menu"));

            $data = ['menus' => $menus, 'indmenu' => $menu, 'menulist' => $menulist, "pages" => $pages, '$categories' => $categories];
            return view('admin-panel::menus.index', $data, compact('languages'));



        return view('admin-panel::menus.index', compact('languages', 'menu','menuitems'));

    }
}
