<?php

namespace Codeman\Admin\Http\Controllers;

use Codeman\Admin\Http\Requests\SubscriptionRequest;
use Illuminate\Http\Request;
use Codeman\Admin\Http\Controllers\Controller;
use Codeman\Admin\Models\Subscriber;
use Codeman\Admin\Models\User;

class SubscribersController extends Controller
{

    public function __construct(Subscriber $model)
    {
        $this->middleware('admin', ['except' => ['store']]);
        $this->model = $model;
        $this->module = 'subscribers';
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

        return view('admin-panel::subscribers.index', [
            'resources' => isset($resources) ? $resources : null, 
            'model' => $this->model, 
            'module' => $this->module, 
            'data_filters' => config()->get('admin-data-filters') && config()->get('admin-data-filters')['subscribers'] ? config()->get('admin-data-filters')['subscribers'] : null,
            'data_filters_json' => config()->get('admin-data-filters') && config()->get('admin-data-filters')['subscribers'] ? json_encode(config()->get('admin-data-filters')['subscribers']) : null,
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
       

        $productSearchValue = null;
        

        // Total records
        $totalRecords = $this->model->select('count(*) as allcount')->count();
        if($rowperpage == -1){
            $start = 0;
            $rowperpage = $totalRecords;
        }
        
        // Fetch records
        $records = $this->model->select('subscribers.id', 'subscribers.email', 'subscribers.created_at', 'subscribers.updated_at', 'subscribers.active', 'users.first_name', 'users.last_name', 'users.id as user_id')->leftjoin('users', 'users.email', 'subscribers.email');

        if($searchQuery && !empty($searchQuery)){
            $searchFields = $this->model->getSearchableFields();
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
    public function create(User $user_model)
    {
        return view('admin-panel::shop.coupons.create_edit')->with([
            'module' => $this->module, 
            'status_options' => [
                'active' => 'Active',
                'disabled' => 'Unsubscribed',
            ],

        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubscriptionRequest $request)
    {
        $subscriber = $this->model->firstOrCreate(['email' => $request->email]);
        return response()->json([
            'status' => true,
            'data' => $subscriber,
            'message' => __('Thank you for subscribing.'),
            'reload_page' => false,
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, User $user_mode)
    {
        $resource = $this->model->find($id);
    
        return view('admin-panel::shop.coupons.create_edit')->with([
            'module' => $this->module, 
            // 'options' => ['status'],
            // 'additional_options' => [

            //     [
            //         'type' => 'input',
            //         'input_type' => 'number',
            //         'label' => 'Coupon amount',
            //         'name' => 'discount',
            //         'info' => 'Enter an amount of discount without any currency. 
            //         In case if you selected a percentage discount enter only the value without any percent symbol. The maximum value for percentage discount is 100, which means FREE.',
            //         'location' => 'default',
            //         'tab' => 'general'
            //     ],
            // ],
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
        return redirect()->to('admin/'.$this->module.'/'.$id.'/edit')
        ->with('success', 'Subscriber successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $resource = $this->model->find($id);
        $resource->delete();        
        return redirect()->to('admin/'.$this->module)
        ->with('success', 'Subscriber successfully deleted');
    }

}
