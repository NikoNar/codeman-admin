<?php

namespace Codeman\Admin\Http\Controllers\Front;

use Codeman\Admin\Http\Requests\ContactUsRequest;
use Codeman\Admin\Http\Requests\ApplicationRequest;
use Codeman\Admin\Mail\ContactUs;
use Codeman\Admin\Mail\ApplicationMail;
use Codeman\Admin\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;


class FrontController extends Controller
{
    public function contact_us(ContactUsRequest $request)
    {
        Mail::to(env('APP_EMAIL'))->send(new ContactUs($request->except('_token')));

        return redirect()->back()->with('Success', 'Email Was Successfully Sent');
    }

    public function apply(ApplicationRequest $request){
        Mail::to(env('APP_EMAIL'))->send(new ApplicationMail($request->except('_token')));
        return redirect()->back()->with('Success', 'Application Was Successfully Sent');
    }

    public function filter(Request $request){
        $year = $request->year;
        $category = $request->category;
        $publications = File::where('year', $year)->whereHas('categories', function($query) use ($category){
            $query->where('slug', $category);
        })->get();
        $returnHTML =  view('layouts.parts.filter')->with('publications', $publications)->render();
        return response()->json(array('success' => true, 'html'=>$returnHTML));
    }
}
