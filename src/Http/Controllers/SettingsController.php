<?php

namespace Codeman\Admin\Http\Controllers;

use Codeman\Admin\Models\Language;
use Illuminate\Http\Request;
use Codeman\Admin\Models\Setting;
use Codeman\Admin\Models\Page;


class SettingsController extends Controller
{
    protected $settings;
    protected $def_lang;
    /**
     * Run constructor
     *
     * @return Response
     */
    public function __construct(Setting $setting)
    {
        $this->middleware('admin');
        $this->settings =  $setting;
        $this->def_lang = Language::orderBy('order')->first();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $settings = $this->settings->where('type', null)->pluck('value', 'key');
        $additional_settings = $this->settings->where('type', '!=', null)->get();

        $pages = Page::where('lang', $this->def_lang->code)->pluck('title','id');
        $languages = Language::pluck('name', 'code');
        $selected_langs = Language::pluck('code')->toArray();
        foreach ($settings as $key => $value) {

            if(isJson($value)) {
                $settings[$key] = json_decode($value);
            }
        }

        return view('admin-panel::setting.index', ['settings' => $settings, 'pages'=> $pages, 'languages'=> $languages, 'selected_langs' => $selected_langs, 'additional_settings' =>$additional_settings]);
    }



    public function type($type, $index){
        $html = view('admin-panel::components.'.$type, ['name'=>"data[".$index."][val]",'index'=>$index])->render();
        return response()->json(array('success' => true, 'html' => $html));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function createOrUpdate(Request $request)
    {
        if($request->site_name == null){
            $request['site_name']  = env('APP_NAME');
        }

        if($request->site_email == null){
            $request['site_email']  = env('APP_EMAIL');
        }
        $updated = [];
        if(null != $all_langs = $request->all_langs){
//            dd($all_langs);
            foreach($all_langs as $key =>$val){
                $all_langs[$key] =(array) json_decode($val);
                Language::updateOrCreate($all_langs[$key]);
                $updated[] = $all_langs[$key]['code'];
            }
        }
        Language::whereNotIn('code', $updated)->delete();

        if($request->has('default_lang')){
            config(['app.locale' => Language::where('code', $request->default_lang)->first()->code]);
            $min = 0;
            Language::where('code', $request->default_lang)
                ->update(['order' => $min]);
            $others = Language::where('code', '!=', $request->default_lang)->get();
            $min++;
            foreach($others as $lang){
                $lang->update(['order' => $min]);
                $min++;
            }

        }
//dd($request->data);
        // $index = $request->home_page;
        if($request->has('_token')){
            unset($request['_token']);

            foreach ($request->except('data') as $key => $value) {
                if(is_array($value)) {
                    // dd($request->all());
                    $value = json_encode($value);
                }
//                 dd($key, $value);
                $this->settings->updateOrCreate(['key' => $key], ['key' => $key, 'value' => $value]);
            }

            $updated_keys = [];
        if($request->data){
//            dd($request->data);
            foreach ($request->data as $key => $data) {
               $item = $this->settings->updateOrCreate(['key' => $data['key']], ['key' => $data['key'], 'value' =>array_key_exists('val', $data)? $data['val']: null, 'type'=>$data['type']]);
               $updated_keys[] = $item->id;
            }
        }
        $this->settings->where('type', '!=', null)->whereNotIn('id', $updated_keys)->delete();

        }

        $selected_langs = Language::select('code', 'name')->get()->toArray();

        return redirect()->back()->with('success', 'Settings Successfully Updated.');
    }

    private function uploadDownloadableFile( $file )
    {
        $filename = preg_replace('/\..+$/', '', $file->getClientOriginalName());
        $filename = \Illuminate\Support\Str::slug($filename);

        $filename = $filename.'-'.uniqid().'.'.$file->getClientOriginalExtension();
        $file->move(public_path('files/program/') , $filename );

        return $filename;
    }

    private function removeFile($filename)
    {
        if(\File::exists(public_path('files/program/'.$filename))){

            \File::delete(public_path('files/program/'.$filename));

        }
    }
}
