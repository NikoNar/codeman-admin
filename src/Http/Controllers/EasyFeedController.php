<?php

namespace Codeman\Admin\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Codeman\Admin\Models\EasyFeed;
use Codeman\Admin\Http\Requests\EasyFeedRequest;
use ZipArchive;
use Excel;
use Codeman\Admin\Models\FeedProduct;
use Codeman\Admin\Models\Variation;
use Codeman\Admin\Models\Image;
use App\Jobs\ImportFeed;

class EasyFeedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $easyFeedArr = EasyFeed::paginate(20);
        return view('admin-panel::easy-feed.index', compact('easyFeedArr'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin-panel::easy-feed.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EasyFeedRequest $request, EasyFeed $feed)
    {   
        $savedFeed = $feed->create($request->all());
        $this->dispatch(new ImportFeed($savedFeed, $request->url));
        
        return redirect()->route('easy-feed-index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EasyFeedRequest $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, EasyFeed $feed)
    {
        $feed->where('id', $id)->delete();

        return redirect()->route('easy-feed-index');
    }
}
