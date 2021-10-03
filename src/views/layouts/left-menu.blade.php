<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="info">
                @if(Auth::check())
                    <p>{{ auth()->user()->name }}</p>
                @endif
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MAIN NAVIGATION</li>
            <li >
                <a href="{!! url('admin/dashboard') !!}">
                    <i class="fa fa-tachometer-alt"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="treeview @if(Request::is('admin/pages*')) active @endif">
                <a href="#">
                    <i class="fa fa-window-restore"></i>
                    <span>Pages</span>
                </a>
                <ul class="treeview-menu">
                    <li class="@if(Request::is('admin/pages')) active @endif">
                        <a href="{!! route('page-index') !!}">
                            <i class="fa fa-list fz-12"></i> View All
                        </a>
                    </li>
                    <li class="@if(Request::is('admin/pages/create')) active @endif">
                        <a href="{!! route('page-create') !!}">
                            <i class="fa fa-plus"></i> Add New
                        </a>
                    </li>
                    @role('SuperAdmin')
                    <li class="@if(Request::is('admin/pages/templates')) active @endif">
                        <a href="{{route('pages.templates')}}">
                            <i class="fa fa-circle-notch"></i> Templates
                        </a>
                    </li>
                    @endrole
                </ul>
            </li>
            @role('SuperAdmin')
            <li class="treeview @if(Request::is('admin/modules*')) active @endif">
                <a href="#">
                    <i class="fa fa-database"></i>
                    <span>Modules</span>
                </a>
                <ul class="treeview-menu">
                    <li class="@if(Request::is('admin/modules')) active @endif">
                        <a href="{!! route('modules.index') !!}">
                            <i class="fa fa-list fz-12"></i> View All
                        </a>
                    </li>
                    <li class="@if(Request::is('admin/modules/create')) active @endif">
                        <a href="{!! route('modules.create') !!}">
                            <i class="fa fa-plus "></i> Create Module
                        </a>
                    </li>
                </ul>
            </li>
            @endrole
            @if(config('services.shop.is_shop'))
            <li class="treeview
            @if(Request::is('admin/products*') ||
                Request::is('admin/product-options*') ||
                Request::is('admin/variations*') ||
                Request::is('admin/warehouses*') ||
                Request::is('admin/product-groups*') ||
                Request::is('admin/product-labels*') )  active @endif">
                <a href="#">
                    <i class="fa fa-shopping-bag"></i>
                    <span>Products</span>
                </a>
                <ul class="treeview-menu">
                    <li class="@if(Request::is('admin/products')) active @endif">
                        <a href="{!! route('products.index') !!}">
                            <i class="fa fa-list fz-12"></i> View All
                        </a>
                    </li>
                    <li class="@if(Request::is('admin/products/create')) active @endif">
                        <a href="{!! route('products.create') !!}">
                            <i class="fa fa-plus "></i> Create Products
                        </a>
                    </li>
                    <li class="@if(Request::is('admin/products/categories*')) active @endif">
                        <a href="{!! route('products.categories') !!}">
                            <i class="fa fa-circle-notch "></i> Categories
                        </a>
                    </li>
                    <li class="@if(Request::is('admin/product-groups*')) active @endif">
                        <a href="{!! route('product-groups.index') !!}">
                            <i class="fa fa-circle-notch "></i> Groups
                        </a>
                    </li>
                    <li class="@if(Request::is('admin/product-labels*')) active @endif">
                        <a href="{!! route('product-labels.index') !!}">
                            <i class="fa fa-circle-notch "></i> Labels
                        </a>
                    </li>
                    <li class="@if(Request::is('admin/variation/subscription')) active @endif">
                        <a href="{!! route('admin.variations.subscription.index') !!}">
                            <i class="fa fa-circle-notch "></i> Subscriptions
                        </a>
                    </li>
                    <li class="treeview @if(Request::is('admin/product-options*')) active @endif">
                        <a href="#">
                            <i class="fa fa-circle-notch "></i> Properties
                        </a>
                        <ul class="treeview-menu">
                            <li class="@if(Request::is('admin/product-options*')) active @endif">
                                <a href="{!! route('product-options.index') !!}">
                                    <i class="fa fa-circle-notch "></i> System Properties
                                </a>
                            </li>
                            <li class="@if(Request::is('admin/shop/properties/filter-colors-group')) active @endif">
                                <a href="{{ route('filter.colors.group') }}">
                                    <i class="fa fa-circle-notch "></i>Filter Colors Group
                                </a>
                            </li>
                            <li class="@if(Request::is('admin/shop/properties/season-colors')) active @endif">
                                <a href="{{ route('season.colors') }}">
                                    <i class="fa fa-circle-notch "></i> Season Colors
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="@if(Request::is('admin/warehouses*')) active @endif">
                        <a href="{!! route('warehouses.index') !!}">
                            <i class="fa fa-circle-notch "></i> Warehouses
                        </a>
                    </li>

                    <li class="@if(Request::is('admin/variations*')) active @endif">
                        <a href="{!! route('variations.index') !!}">
                            <i class="fa fa-circle-notch "></i> Variations
                        </a>
{{--                        <ul class="treeview-menu">--}}
{{--                            <li class="@if(Request::is('admin/variations/sort')) active @endif">--}}
{{--                                <a href="{!! route('variations.sort') !!}">--}}
{{--                                    <i class="fa fa-circle-notch "></i> Sort--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
                    </li>
                </ul>
            </li>
            <li class="@if(Request::is('admin/shop-settings*')) active @endif">
                <a href="{{ route('shop-settings.index') }}">
                    <i class="fa fa-share"></i>
                    <span>Shop Settings</span>
                </a>
            </li>
            <li class="treeview @if(Request::is('admin/lookbooks*')) active @endif">
                <a href="#">
                    <i class="fa fa-share"></i>
                    <span>LookBook</span>
                </a>
                <ul class="treeview-menu">
                    <li class="@if(Request::is('admin/lookbooks')) active @endif">
                        <a href="{!! route('lookbooks.index') !!}">
                            <i class="fa fa-list fz-12"></i> View all
                        </a>
                    </li>
                    <li class="@if(Request::is('admin/lookbooks/create')) active @endif">
                        <a href="{!! route('lookbooks.create') !!}">
                            <i class="fa fa-plus fz-12"></i> Add New
                        </a>
                    </li>
                    {{-- <li class="@if(Request::is('admin/collections/catalog*')) active @endif">
                        <a href="{!! route('admin.collections.catalog') !!}">
                            <i class="fa fa-list fz-12"></i> Catalogs
                        </a>
                    </li>
                    <li class="@if(Request::is('admin/collections/looks*')) active @endif">
                        <a href="{!! route('admin.collections.looks') !!}">
                            <i class="fa fa-list fz-12"></i> Looks
                        </a>
                    </li>
                    <li class="@if(Request::is('admin/collections/videos*')) active @endif">
                        <a href="{!! route('admin.collections.videos') !!}">
                            <i class="fa fa-list fz-12"></i> Videos
                        </a>
                    </li> --}}
                </ul>
            </li>
            <li class="treeview @if(Request::is('admin/looks*')) active @endif">
                <a href="#">
                    <i class="fa fa-theater-masks"></i>
                    <span>Looks</span>
                </a>
                <ul class="treeview-menu">
                    <li class="@if(Request::is('admin/looks')) active @endif">
                        <a href="{!! route('looks.index') !!}">
                            <i class="fa fa-list fz-12"></i> View All
                        </a>
                    </li>
                    <li class="@if(Request::is('admin/looks/create')) active @endif">
                        <a href="{!! route('looks.create') !!}">
                            <i class="fa fa-plus fz-12"></i> Add New
                        </a>
                    </li>
                </ul>
            </li>
            @php
                if (class_exists('Codeman\Admin\Models\Shop\Order')) {
                    $order_model = new Codeman\Admin\Models\Shop\Order();
                    $order_in_review = $order_model->where('status', 'in review')->count();
                }
            @endphp
                <li class=" @if(Request::is('admin/orders*')) active @endif">
                    <a href="{{ route('admin.orders') }}">
                        <i class="fa fa-shopping-cart"></i>
                        <span>Orders <span class="label label-danger pull-right">{!! $order_in_review !!}</span></span>
                    </a>
                </li>
            @endif

            @if (class_exists('Codeman\Admin\Models\Shop\Coupon'))
                <li class="treeview @if(Request::is('admin/marketing*')) active @endif">
                    <a href="#">
                        <i class="fa fa-lightbulb"></i>
                        <span>Marketing</span>
                    </a>
                    <ul class="treeview-menu">
                      <li class="@if(Request::is('admin/marketing/coupon*')) active @endif">
                            <a href="{!! route('coupon.index') !!}">
                                <i class="fa fa-list fz-12"></i> Coupons
                            </a>
                        </li>
                        {{-- <li class="@if(Request::is('admin/marketing/discounts*')) active @endif">
                            <a href="{!! route('coupons.create') !!}">
                                <i class="fa fa-list fz-12"></i> Discounts
                            </a>
                        </li> --}}
                        <li class="@if(Request::is('admin/marketing/search-statistics*')) active @endif">
                            <a href="{!! route('search-statistics.index') !!}">
                                <i class="fa fa-list fz-12"></i> Search Statistics
                            </a>
                        </li>

                        <li class="@if(Request::is('admin/marketing/cart_rules')) active @endif">
                            <a href="{!! route('cart.rules.index') !!}">
                                <i class="fa fa-list fz-12"></i> Cart Rules
                            </a>
                        </li>

                        <li class="@if(Request::is('admin/marketing/catalog_rules')) active @endif">
                            <a href="{!! route('catalog.rules.index') !!}">
                                <i class="fa fa-list fz-12"></i> Catalog Rules
                            </a>
                        </li>

                    </ul>
                </li>
            @endif
            @if(isset($modules) && count($modules) > 0)
                <hr>
                @foreach($modules as $key => $module)
                    <li class="treeview @if(Request::is('admin/'.$module->title.'*')) active @endif">
                        <a href="#">
                            <i class="fa {{$module->icon}}"></i>
                            <span>{{$module->title}}</span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="@if(Request::is('admin/resource'.$module->title)) active @endif">
                                <a href='{!! url("admin/resource/$module->slug") !!}'>
                                    <i class="fa fa-list fz-12"></i> View All
                                </a>
                            </li>
                            <li class="@if(Request::is('admin/resource/'.$module->title.'/create')) active @endif">
                                <a href='{!! url("admin/resource/$module->slug/create") !!}'>
                                    <i class="fa fa-plus"></i> Add New
                                </a>
                            </li>
                            @php
                                $options = json_decode($module->options);
                                if($options == null){
                                    $options = [];
                                }
                            @endphp
                            @if(in_array('categories', $options))
                                <li class="@if(Request::is('admin/resource/'.$module->title.'/categories')) active @endif">
                                    <a href='{!! url("admin/resource/$module->slug/categories") !!}'>
                                        <i class="fa fa-circle-notch"></i> Categories
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endforeach
            @endif
            <li class="treeview @if(Request::is('admin/instagram*')) active @endif">

                <a href="#">
                    <i class=" fab fa-instagram"></i>
                    <span>Instagram</span>
                </a>
                <ul class="treeview-menu">
                    <li class="@if(Request::is('admin/instagram')) active @endif">
                        <a href="{!! route('instagram.index') !!}">
                            <i class="fa fa-list fz-12"></i> View All
                        </a>
                    </li>
                    <li class="@if(Request::is('admin/instagram/create')) active @endif">
                        <a href="{!! route('instagram.create') !!}">
                            <i class="fa fa-plus fz-12"></i> Add New
                        </a>
                    </li>
                </ul>
            </li>
            <hr>
            @hasanyrole('SuperAdmin|Admin')

            <li class="treeview @if(Request::is('admin/users*')) active @endif">

                <a href="#">
                    <i class="fa fa-users"></i>
                    <span>Users</span>
                </a>
                <ul class="treeview-menu">
                    <li class="@if(Request::is('admin/users')) active @endif">
                        <a href="{!! route('user.index') !!}">
                            <i class="fa fa-list fz-12"></i> View All
                        </a>
                    </li>
                    <li class="@if(Request::is('admin/users/create')) active @endif">
                        <a href="{!! route('user.create') !!}">
                            <i class="fa fa-plus fz-12"></i> Add New
                        </a>
                    </li>
                    <li class="@if(Request::is('admin/users/roles')) active @endif">
                        <a href="{!! route('roles.index') !!}">
                            <i class="fa fa-eye"></i> Roles
                        </a>
                    </li>
                </ul>
            </li>
            @endrole

            <li class="@if(Request::is('admin/menus*')) active @endif">
                <a href="{!! route('menu-index') !!}">
                    <i class="fa fa-th"></i> <span>Menus</span>
                </a>
            </li>
            <li class="@if(Request::is('admin/subscribers*')) active @endif">
                <a href="{!! route('subscribers.index') !!}">
                    <i class="fa fa-th"></i> <span>Subscribers</span>
                </a>
            </li>



            <li class="treeview @if(Request::is('admin/media*')) active @endif">
                <a href="#">
                    <i class="fa fa-image"></i> <span>Media</span>
                </a>
                <ul class="treeview-menu">
                    <li class="@if(Request::is('admin/media')) active @endif">
                        <a href="{!! route('image-index') !!}">
                            <i class="fa fa-circle-notch fz-12"></i> Files
                        </a>
                    </li>
                    <li class="@if(Request::is('admin/media/upload')) active @endif">
                        <a href="{!! route('image-upload') !!}">
                            <i class="fa fa-circle-notch fz-12"></i> Upload
                        </a>
                    </li>
                </ul>
            </li>
            <li class="@if(Request::is('admin/translations*')) active @endif">
                <a href="{!! url('admin/translations/view/translate') !!}" target="_blank">
                    <i class="fa fa-language"></i>  <span>Translations</span>
                </a>
            </li>

            <li class="@if(Request::is('admin/settings*')) active @endif">
                <a href="{!! route('setting.index') !!}">
                    <i class="fa fa-cogs"></i>  <span>Settings</span>
                </a>
            </li>
            {{--            <hr>--}}
            {{--            <li class="@if(Request::is('admin/applications*')) active @endif">--}}
            {{--                <a href="{!! route('applications-index') !!}">--}}
            {{--                    <i class="fa fa-address-card"></i><span>Applications</span>  --}}
            {{--                </a>--}}
            {{--            </li>--}}
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
