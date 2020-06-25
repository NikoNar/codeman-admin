<?php

namespace Codeman\Admin\Http\Controllers\Front;

use Codeman\Admin\Http\Requests\ContactUsRequest;
use Codeman\Admin\Http\Requests\ApplicationRequest;
use Codeman\Admin\Mail\ContactUs;
use Codeman\Admin\Mail\ApplicationMail;
use Codeman\Admin\Models\File;
use Codeman\Admin\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;


class FrontController extends Controller
{
    public $email;

    public function __construct()
    {
        if(null != $email = Setting::select('value')->where('key','site_email')->first()){
            $this->email = $email->value;
        } else {
            $this->email = env('APP_EMAIL');
        }
    }

    public function contact_us(ContactUsRequest $request)
    {
        dd($request->all());
        Mail::to($this->email)->send(new ContactUs($request->except('_token')));
        return redirect()->back()->with('Success', 'Email Was Successfully Sent');
    }

    public function apply(ApplicationRequest $request){
        Mail::to($this->email)->send(new ApplicationMail($request->except('_token')));
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
