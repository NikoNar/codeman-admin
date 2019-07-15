<?php

namespace Codeman\Admin\Http\Controllers;
use Codeman\Admin\Models\File;
use Codeman\Admin\Models\Language;
use Codeman\Admin\Models\Page;
use Codeman\Admin\Models\Portfolio;
use Codeman\Admin\Models\Program;
use Codeman\Admin\Models\Lecturer;
use Codeman\Admin\Models\Resource;
use Codeman\Admin\Models\Review;
use Codeman\Admin\Models\Application;
use Codeman\Admin\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
//use \Spatie\Analytics\Analytics;  //////////////////////////////////////////////////////////UNCOMENT FOR ANALYTICS
//use \Spatie\Analytics\Period;//////////////////////////////////////////////////////////UNCOMENT FOR ANALYTICS
use \Carbon\Carbon;
// use App\Http\Controllers\Admin\JoomagREST;

class DashboardController extends Controller
{
//	public function __construct(Analytics $analytics){   //////////////////////////////////////////////////////////UNCOMENT FOR ANALYTICS
//        // $this->middleware('admin');
//        $this->analytics = $analytics;
//	}

    public function index() {
        //retrieve visitors and pageview data for the current day and the last seven days
//        $analyticsData = $this->analytics->fetchVisitorsAndPageViews(Period::days(30)); //////////////////////////////////////////////////////////UNCOMENT FOR ANALYTICS

        //retrieve visitors and pageviews since the 6 months ago
        // $analyticsData = $this->analytics->fetchVisitorsAndPageViews(Period::months(1));

        //retrieve sessions and pageviews with yearMonth dimension since 1 year ago

//        //////////////////////////////////////////////////////////UNCOMENT FOR ANALYTICS
//        $analyticStats = $this->analytics->performQuery(
//            Period::days(30),
//            'ga:sessions',
//            [
//                'metrics' => 'ga:users, ga:sessions, ga:bounceRate, ga:avgSessionDuration, ga:pageviews',
//            ]
//        );
//        // dd($analyticStats);
//        $analyticCountryStats = $this->analytics->performQuery(
//            Period::days(30),
//            'ga:sessions',
//            [
//                'metrics' => 'ga:sessions',
//                'dimensions' => 'ga:countryIsoCode, ga:country'
//            ]
//        );



        // $analytics_results = $this->parseResults($analyticsData, 30);
        $dates = [];
        $visitors = [];
        $pageViews = [];
        $sessionsCharterData = [];
        $countriesSessions = [];
        $countriesSessionsPersent = [];

//        //////////////////////////////////////////////////////////UNCOMENT FOR ANALYTICS

//        foreach ($analyticsData as $key => $value) {
//            $date  = new Carbon($value['date']);
//            $sessionsCharterData[$key]['date'] = $date->format('Y-m-d');
//            $sessionsCharterData[$key]['visitors'] = $value['visitors'];
//            $dates[] = $date->format('F d');
//            // $visitors[] = $value['visitors'];
//            // $pageViews[] = $value['pageViews'];
//        }
//        foreach ($analyticCountryStats as $key => $value) {
//            $countriesSessions[$value[0]] = $value[2];
//            $countriesSessionsPersent[$key]['country'] = $value[1];
//            $countriesSessionsPersent[$key]['persent'] = number_format($value[2]*100/$analyticCountryStats->totalsForAllResults['ga:sessions'], 2, '.', '');
//        }
        // dd($sessionsCharterData);

        $default_lang = Language::orderBy('order')->first();
        $def_land_id  = $default_lang->id;
        $resources = DB::table('resources')
            ->select('type', 'icon',  DB::raw('count(*) as total'))
            ->where('language_id', $def_land_id)
            ->leftJoin('modules', 'resources.type', '=', 'modules.slug')
            ->groupBy('type', 'icon')
            ->get();
    	return view('admin-panel::dashboard',
        [
            'pages_count' => Page::where('language_id', $def_land_id)->count(),
            'resources' => $resources,
            'sessionsCharterData' => $sessionsCharterData,
            'dates' => $dates,
            // 'visitors' => $visitors,
            // 'pageviews' => $pageViews,
            'analyticStats' => isset($analyticStats) ? $analyticStats->totalsForAllResults : array(),
            'countriesSessions' => $countriesSessions,
            'countriesSessionsPersent' => $countriesSessionsPersent,

        ]);
    }


    public function api(){
    	// // require 'JoomagREST.php';
    	$jmClient = new JoomagREST(
    	    'api_e4b2931146aab38f8b32164d1a46a33e',
    	    'sec_18d6de16458da52e97ffe1aa3b47f788a01f3225a6ebcf8ad63f2423879fa960'
    	);

    	// # Make the call to the client.
    	// $result = $jmClient->getMagazinesList();
    	// dd($result);
    	dd(bcrypt('tztzik'));
    }
    
}
