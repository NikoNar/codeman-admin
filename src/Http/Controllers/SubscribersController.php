<?php

namespace Codeman\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Codeman\Admin\Http\Requests\SubscriberRequest;
use App\Http\Controllers\Controller;
use Codeman\Admin\Models\Subscriber;

class SubscribersController extends Controller
{
    /**
       * Run constructor
       *
       * @return Response
       */
    public function __construct(Subscriber $subscriber)
    {
    	$this->subscriber =  $subscriber;
    	// $this->middleware('admin');
    }

    /**
       * Display a listing of the resource.
       *
       * @return Response
       */
    public function index()
    {
    	return view('admin-panel::subscriber.index', ['subscribers' => $this->subscriber->orderBy('created_at', 'DESC')->paginate(10)]);
    }

	/**
	* Show the form for creating a new resource.
	*
	* @return Response
	*/
	public function create()
	{
    	return view('admin-panel::subscriber.create');
	}

	/**
	* Store a newly created resource in storage.
	*
	* @return Response
	*/
	public function store( SubscriberRequest $request )
	{
		$this->subscriber->create($request->all());
		return redirect()->back()->with('success', 'Subscriber Successfully Created');
	}

	/**
	* Remove the specified resource from storage.
	*
	* @param  int  $id
	* @return Response
	*/
	public function destroy($id)
	{
		if($this->subscriber->find($id)->delete()){
			return redirect()->back()->with('success', 'Subscriber Successfully Deleted.');
		}
	}
}
