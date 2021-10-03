<?php

namespace Codeman\Admin\Http\Controllers\Shop\Admin;

use App\Http\Controllers\Controller;
use Codeman\Admin\Http\Requests\Shop\CouponRequest as Request;
use App\Models\User;
use Codeman\Admin\Models\Shop\Product;
use Codeman\Admin\Models\Shop\Variation;
use Codeman\Admin\Models\Category;
use Codeman\Admin\Models\Shop\Coupon;

class CouponsController extends Controller
{
    public function __construct(Coupon $model)
    {
        $this->middleware('admin');
        $this->model = $model;
        $this->module = 'coupons';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // //TODO implement user permissions
        // if(!auth()->user()->can('create-'.$module) && !auth()->user()->hasAnyRole('SuperAdmin|Admin')){
        //     abort(403);
        // }

        if(request()->ajax()){
            return $this->getResources(request());
        }

        return view('admin-panel::shop.coupons.index', [
            // 'resources' => $this->CRUD->getAll($this->module), 
            // 'categories' => $categories,
            // 'products' => $products,
            'resources' => isset($resources) ? $resources : null, 
            'model' => $this->model, 
            'module' => $this->module, 
            // 'dates' => $this->getDatesOfResources($this->model), 
            // 'languages' => $this->languages,
            'data_filters' => config()->get('admin-data-filters') && config()->get('admin-data-filters')['coupons'] ? config()->get('admin-data-filters')['coupons'] : null,
            'data_filters_json' => config()->get('admin-data-filters') && config()->get('admin-data-filters')['coupons'] ? json_encode(config()->get('admin-data-filters')['coupons']) : null,
        ]);
    }


    public function getResources($request)
    {
        $dataFilters = $request->has('filters') ? $request->get('filters') : null;

        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');

        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnName = $columnIndex_arr[0]['column'];
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchQuery = $search_arr['value']; // Search value

        $categorySearchValue = [];
        // $categoryArray_index = array_search('categories', array_column($columnName_arr, 'data'));
        // if($categoryArray_index !== false){
        //     $categoryColumn = $columnName_arr[$categoryArray_index];
        //     $categorySearchValue = $categoryColumn['search']['value'];
        //     $categorySearchValue = $categorySearchValue ? explode(',', $categorySearchValue) : null;
        // }

        $productSearchValue = null;
        // $productArray_index = array_search('product.title', array_column($columnName_arr, 'data'));
        // if($productArray_index !== false){
        //     $productColumn = $columnName_arr[$productArray_index];
        //     $productSearchValue = $productColumn['search']['value'];
        // }

        // Total records
        $totalRecords = $this->model->select('count(*) as allcount')->count();
        if($rowperpage == -1){
            $start = 0;
            $rowperpage = $totalRecords;
        }
        // $totalRecordswithFilter = $this->variation->select('count(*) as allcount')
        // ->where('title', 'like', '%' .$searchQuery . '%')
        // ->count();
        // Fetch records
        $records = $this->model;        


        // if($productSearchValue && !empty($productSearchValue)){
        //     $records = $records->whereHas(
        //         'product', function($q) use ($productSearchValue) {
        //             return $q->where('products.id', $productSearchValue);
        //             // ->where('cm_categories.lang', $this->def_lang->code);
        //         }
        //     );
        // }

        // $records = $records->with([
        //     'product' => function($q){
        //         $q->select('id', 'title')->orderBy('order', 'DESC')->orderBy('created_at', 'DESC');
        //     }
        // ]);

        if($searchQuery && !empty($searchQuery)){
            $searchFields = $this->variation->getSearchableFields();
            $relations_fields = [];

            $records = $records->where(function($query) use($searchFields, $searchQuery, $relations_fields, $request) {
                foreach ($searchFields as $field){
                    $field = explode('.', $field);
                    if(count($field) == 1){
                        $query->orWhere($field[0], 'like', "%{$searchQuery}%");
                        if($request->has('language')){
                            $query->where('lang', $request->get('language'));
                        }
                    }
                }
            });

            $records = $records->orWhere(function($query) use($searchFields, $searchQuery, $relations_fields, $request) {
                foreach ($searchFields as $field){
                    $field = explode('.', $field);
                    if(count($field) == 2){
                        $relation_name = $field[0];
                        $relation_field = $field[1];
                        $query->with($relation_name)->orWhereHas($relation_name, function($q) use ($relation_field, $searchQuery)
                        {
                            $q->select($relation_field)
                            ->where($relation_field, 'like', '%'.$searchQuery.'%');
                            // ->where('lang', $request->get('language')); 
                        });
                    }
                }
            });
        }

        if($rowperpage == -1){
            $start = 0;
            $rowperpage = $totalRecords;
        }
        $records = $records
        // ->where([ 'lang' => $this->def_lang->code ]) // TODO //Missing Lang column from variation table
        ->orderBy($columnName, $columnSortOrder);
        $totalRecordswithFilter = $records->count();

        $records = $records->skip($start)
        ->take($rowperpage)
        ->get()
        ->toArray();

        $response = array(
           "draw" => intval($draw),
           "iTotalRecords" => $totalRecords,
           "iTotalDisplayRecords" => $totalRecordswithFilter,
           "aaData" => $records
        );

        return response()->json($response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_status(Request $request, $id)
    {
        $status_options = [
            'pending',
            'in review',
            'processing',
            'completed',
            'cancelled',
            'refound',
            'failed',
        ];
        if($request->has('status') && in_array($request->get('status'), $status_options)){
            $this->model->find($id)->update(['status' => $request->get('status')]);
            return redirect()->back()->with('success', 'Order status has been updated to <strong class="text-uppercase">'.$request->get('status').'</strong>!');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Product $product_model, Variation $variation_model, Category $category_model)
    {
        $relations = [];
        
        $relations['products'] = $product_model
        ->where('status', 'published')
        ->where('parent_lang_id', null)
        ->orderBy('title', 'ASC')
        ->pluck('title', 'id')
        ->toArray();

        $relations['variations'] = $variation_model->getVariationsGroupedByColor()
        ->get()
        ->pluck('title', 'id')
        ->toArray();

        $relations['categories'] =  $category_model
        ->where('type', 'products')
        ->where('parent_lang_id', null)
        ->orderBy('title', 'desc')
        ->orderBy('parent_id', 'DESC')
        ->pluck('title', 'id')
        ->toArray();

        return view('admin-panel::shop.coupons.create_edit')->with([
            'module' => $this->module, 
            'options' => [],
            'status_options' => [
                'active' => 'Active',
                'disabled' => 'Disabled',
                'used' => 'Used',
                'scheduled' => 'Scheduled'
            ],
            'additional_options' => [
                [
                    'type' => 'select',
                    'label' => 'Coupon Type',
                    'name' => 'type',
                    'type_options' => [
                        'percent:Percentage Discount (%)',
                        'fixed_cart:Fixed discount on Cart',
                        'fixed_product:Fixed discount per Product',
                    ],
                    'info' => 'Select coupon type.',
                    'location' => 'default',
                    'tab' => 'general'
                ],

                [
                    'type' => 'input',
                    'input_type' => 'number',
                    'label' => 'Coupon amount',
                    'name' => 'discount',
                    'info' => 'Enter an amount of discount without any currency. 
                    In case if you selected a percentage discount enter only the value without any percent symbol. The maximum value for percentage discount is 100, which means FREE.',
                    'location' => 'default',
                    'tab' => 'general'
                ],
                
                [
                    'type' => 'input',
                    'input_type' => 'number',
                    'label' => 'Coupon usage limit',
                    'name' => 'usage_limit',
                    'info' => 'Enter a value for how many times this coupon can be used. Upon reaching this amount, the coupon will be canceled. <b>Leave blank for UNLIMITED usage.</b>',
                    'location' => 'default',
                    'tab' => 'restrictions'
                ],
                [
                    'type' => 'text',
                    'input_type' => 'number',
                    'label' => 'Limit оf use to X items',
                    'name' => 'items_usage_limit',
                    'info' => 'The maximum number of individual items this coupon can apply to when using item discounts. <b> Leave blank to apply for all eligible items.</b>',
                    'location' => 'default',
                    'tab' => 'restrictions'
                ],
                [
                    'type' => 'input',
                    'input_type' => 'number',
                    'label' => 'Limit of use per user',
                    'name' => 'user_usage_limit',
                    'info' => 'The maximum number of coupon use per individaul user. <b>Leave blank to Unlimited use.</b>',
                    'location' => 'default',
                    'tab' => 'restrictions'
                ],
                [
                    'type' => 'input',
                    'input_type' => 'email',
                    'label' => 'Apply individual coupon to email address',
                    'name' => 'customer_email',
                    'info' => "Enter your email address to apply this coupon only at checkout if the customer's email address matches this value.",
                    'location' => 'default',
                    'tab' => 'restrictions'
                ],
                // [
                //     'type' => 'editor',
                //     'label' => 'Description',
                //     'name' => 'message',
                //     'location' => 'default'
                // ],
                // starting of right-sidebar
                [
                    'type' => 'datetimepicker',
                    'label' => 'Coupon start date',
                    'name' => 'start_date',
                    'info' => 'Select the date and time you want the coupon to become valid.',
                    'location' => 'right-sidebar'
                ],
                [
                    'type' => 'datetimepicker',
                    'label' => 'Coupon expiration date',
                    'name' => 'end_date',
                    'info' => 'Select the date and time when the coupon will expire.',
                    'location' => 'right-sidebar'
                ],
            ],
            'relations' => $relations,

        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$request->code){
            $request['code'] = $this->generateCode();
        }
        $request['creator_id'] = auth()->id();

        $resource = $this->model->create($request->all());
        $resource->products()->sync($request['products_id']);
        $resource->categories()->sync($request['categories']);

        return redirect()->to('admin/marketing/'.$this->module.'/'.$resource->id.'/edit')
        ->with('success', 'Coupon successfully created');
        
    }

    public function generateCode()
    {
        $prefix = substr(env('APP_NAME') ,0,3 );
        $random = \Str::random(6);
        $code = $prefix.'-'.$random;
        if(request()->ajax()){
            return response()->json(['status' => 'success', 'code' => $code]);
        }
        return $code;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Product $product_model, Variation $variation_model, Category $category_model)
    {
        $resource = $this->model->find($id);
        
        $relations = [];
        $relations['products'] = $product_model
        ->where('status', 'published')
        ->where('parent_lang_id', null)
        ->orderBy('title', 'ASC')
        ->pluck('title', 'id')
        ->toArray();
        $relations['variations'] = $variation_model->getVariationsGroupedByColor()
        ->get()
        ->pluck('title', 'id')
        ->toArray();

        $relations['categories'] =  $category_model
        ->where('type', 'products')
        ->where('parent_lang_id', null)
        ->orderBy('title', 'desc')
        ->orderBy('parent_id', 'DESC')
        ->pluck('title', 'id')
        ->toArray();

        return view('admin-panel::shop.coupons.create_edit')->with([
            'module' => $this->module, 
            'options' => [],
            'additional_options' => [
                [
                    'type' => 'select',
                    'label' => 'Coupon Type',
                    'name' => 'type',
                    'type_options' => [
                        'percent:Percentage Discount (%)',
                        'fixed_cart:Fixed discount on Cart',
                        'fixed_product:Fixed discount per Product',
                    ],
                    'info' => 'Select coupon type.',
                    'location' => 'default',
                    'tab' => 'general'
                ],

                [
                    'type' => 'input',
                    'input_type' => 'number',
                    'label' => 'Coupon amount',
                    'name' => 'discount',
                    'info' => 'Enter an amount of discount without any currency. 
                    In case if you selected a percentage discount enter only the value without any percent symbol. The maximum value for percentage discount is 100, which means FREE.',
                    'location' => 'default',
                    'tab' => 'general'
                ],
                
                [
                    'type' => 'input',
                    'input_type' => 'number',
                    'label' => 'Coupon usage limit',
                    'name' => 'usage_limit',
                    'info' => 'Enter a value for how many times this coupon can be used. Upon reaching this amount, the coupon will be canceled. <b>Leave blank for UNLIMITED usage.</b>',
                    'location' => 'default',
                    'tab' => 'restrictions'
                ],
                [
                    'type' => 'text',
                    'input_type' => 'number',
                    'label' => 'Limit оf use to X items',
                    'name' => 'items_usage_limit',
                    'info' => 'The maximum number of individual items this coupon can apply to when using item discounts. <b> Leave blank to apply for all eligible items.</b>',
                    'location' => 'default',
                    'tab' => 'restrictions'
                ],
                [
                    'type' => 'input',
                    'input_type' => 'number',
                    'label' => 'Limit of use per user',
                    'name' => 'user_usage_limit',
                    'info' => 'The maximum number of coupon use per individaul user. <b>Leave blank to Unlimited use.</b>',
                    'location' => 'default',
                    'tab' => 'restrictions'
                ],
                [
                    'type' => 'input',
                    'input_type' => 'email',
                    'label' => 'Apply individual coupon to email address',
                    'name' => 'customer_email',
                    'info' => "Enter your email address to apply this coupon only at checkout if the customer's email address matches this value.",
                    'location' => 'default',
                    'tab' => 'restrictions'
                ],
                // [
                //     'type' => 'editor',
                //     'label' => 'Description',
                //     'name' => 'message',
                //     'location' => 'default'
                // ],
                // starting of right-sidebar
                [
                    'type' => 'datetimepicker',
                    'label' => 'Coupon start date',
                    'name' => 'start_date',
                    'info' => 'Select the date and time you want the coupon to become valid.',
                    'location' => 'right-sidebar'
                ],
                [
                    'type' => 'datetimepicker',
                    'label' => 'Coupon expiration date',
                    'name' => 'end_date',
                    'info' => 'Select the date and time when the coupon will expire.',
                    'location' => 'right-sidebar'
                ],
            ],
            'relations' => $relations,
            'resource' => $resource
        ]);
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $resource = $this->model->find($id);
        $resource->update($request->all());
        $resource->products()->sync($request['products']);
        $resource->categories()->sync($request['categories']);
        
        return redirect()->to('admin/marketing/'.$this->module.'/'.$id.'/edit')
        ->with('success', 'Coupon successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
