<?php

namespace Codeman\Admin\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Codeman\Admin\Models\HomeSlider;
use Codeman\Admin\Models\GridSection;
use Settings;


class HomeController extends Controller
{
   /**
       * Run constructor
       *
       * @return Response
       */
    public function __construct(HomeSlider $homeSlider, GridSection $gridSection, Settings $settings)
    {
    	$this->middleware('admin');
        $this->homeSlider = $homeSlider;
        $this->gridSection = $gridSection;
    	$this->settings = $settings;
    }

    /**
       * Display a listing of the resource.
       *
       * @return Response
       */
    public function index()
    {
    	return view('admin-panel::home.index', [
            'homeSliders' => $this->homeSlider->orderBy('order', 'DESC')->get(),
    		'gridView' => $this->gridSection->orderBy('id', 'ASC')->get(),
            'home_settings' => json_decode($this->settings::get('homepage_settings')),
    	]);
    }

    public function update(Request $request)
    {
    	$sliderInput = $request->get('slider');
    	$sliders = array();
        $this->homeSlider->query()->truncate();
        for ($i=0; $i < count($sliderInput['thumbnail']); $i++) {
            if($sliderInput['thumbnail'][$i] != '' || !empty($sliderInput['thumbnail'][$i])) {
    			$slide = [];
    			$slide['thumbnail'] = $sliderInput['thumbnail'][$i];
                $slide['from_to_date_en'] = $sliderInput['from_to_date_en'][$i];
    			$slide['from_to_date_arm'] = $sliderInput['from_to_date_arm'][$i];
                $slide['main_text_en'] = $sliderInput['main_text_en'][$i];
    			$slide['main_text_arm'] = $sliderInput['main_text_arm'][$i];
    			// if(isset($sliderInput['id'][$i]) && $sliderInput['id'][$i] > 0){
    			// 	$this->homeSlider->find($sliderInput['id'][$i])->update($slide);
    			// }else{
    				$this->homeSlider->create($slide);
    			// }
    		}
    	}
        return redirect()->back()->with('success', 'Page Successfully Updated');
    }

    public function updateGridView(Request $request)
    {
        if($request->has('item') and is_array($request->get('item'))) {
            $this->gridSection->truncate();
            $newInputs = [];
            foreach ($request->get('item') as $id => $inputs) {
                $newInputs[$id]['x'] = $inputs['x'];
                $newInputs[$id]['y'] = $inputs['y'];
                $newInputs[$id]['w'] = $inputs['w'];
                $newInputs[$id]['h'] = $inputs['h'];
                unset($inputs['x']);
                unset($inputs['y']);
                unset($inputs['w']);
                unset($inputs['h']);
                $newInputs[$id]['options'] = json_encode($inputs);
            }
            $this->gridSection->insert($newInputs);
        }
        return redirect()->back()->with('success', 'Page Successfully Updated');

    }

    public function updateSettings(Request $request)
    {
        $home_page_settings = json_encode($request->get('homepage_settings'));
        $this->settings::set('homepage_settings', $home_page_settings);
        return redirect()->back()->with('success', 'Home Settings Has Been Successfully Updated');
    }
}
