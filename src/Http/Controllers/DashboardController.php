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
// use \Spatie\Analytics\Analytics; //UNCOMENT FOR ANALYTICS
// use \Spatie\Analytics\Period; //UNCOMENT FOR ANALYTICS
use \Carbon\Carbon;
// use App\Http\Controllers\Admin\JoomagREST;

class DashboardController extends Controller
{
	public function __construct() //Analytics $analytics
    {
       $this->middleware('admin');
       // $this->analytics = $analytics;
	}

    public function index()
    {


        //retrieve visitors and pageview data for the current day and the last seven days
        // $analyticsData = $this->analytics->fetchVisitorsAndPageViews(Period::days(7)); //UNCOMENT FOR ANALYTICS

        // retrieve visitors and pageviews since the 6 months ago
        // $analyticsData = $this->analytics->fetchVisitorsAndPageViews(Period::months(1));

        //retrieve sessions and pageviews with yearMonth dimension since 1 year ago

        //UNCOMENT FOR ANALYTICS

       // $analyticStats = $this->analytics->performQuery(
       //     Period::days(7),
       //     'ga:sessions',
       //     [
       //         'metrics' => 'ga:users, ga:sessions, ga:bounceRate, ga:avgSessionDuration, ga:pageviews',
       //     ]
       // );
       // $analyticCountryStats = $this->analytics->performQuery(
       //     Period::days(7),
       //     'ga:sessions',
       //     [
       //         'metrics' => 'ga:sessions',
       //         'dimensions' => 'ga:countryIsoCode, ga:country'
       //     ]
       // );



        // $analytics_results = $this->parseResults($analyticsData, 30);

        $dates = [];
        $visitors = [];
        $pageViews = [];
        $sessionsCharterData = [];
        $countriesSessions = [];
        $countriesSessionsPersent = [];

        //UNCOMENT FOR ANALYTICS

        // foreach ($analyticsData as $key => $value) {
        //    $date  = new Carbon($value['date']);
        //    $sessionsCharterData[$key]['date'] = $date->format('Y-m-d');
        //    $sessionsCharterData[$key]['visitors'] = $value['visitors'];
        //    $dates[] = $date->format('F d');
        //    $visitors[] = $value['visitors'];
        //    $pageViews[] = $value['pageViews'];
        // }
        // foreach ($analyticCountryStats as $key => $value) {
        //    $countriesSessions[$value[0]] = $value[2];
        //    $countriesSessionsPersent[$key]['country'] = $value[1];
        //    $countriesSessionsPersent[$key]['persent'] = number_format($value[2]*100/$analyticCountryStats->totalsForAllResults['ga:sessions'], 2, '.', '');
        // }


        $default_lang = Language::orderBy('order')->first();
        $lang  = $default_lang->code;
        $resources = DB::table('resources')
        ->select('type', 'icon',  DB::raw('count(*) as total'))
        ->where('lang', $lang)
        ->leftJoin('modules', 'resources.type', '=', 'modules.slug')
        ->groupBy('type', 'icon')
        ->get();


        if(env('is_shop')){
            $today = \Carbon\Carbon::today();
            $start_of_current_month = Carbon::parse($today)->startOfMonth();
            $end_of_current_month = Carbon::parse($today)->endOfMonth();

            $pre_month = \Carbon\Carbon::today()->subMonth(1);
            // $start_of_prev_month = Carbon::parse($pre_month)->startOfMonth();
            // $end_of_prev_month = Carbon::parse($pre_month)->endOfMonth();
            $start_of_prev_month =  new \Carbon\Carbon('first day of last month');
            $start_of_prev_month->startOfDay();
            $end_of_prev_month =  new \Carbon\Carbon('last day of last month');
            $end_of_prev_month->endOfDay();

            $orders = \Codeman\Admin\Models\Shop\Order::select('subtotal', 'total', 'shipping_price', 'created_at')
//            ->whereIn('status', ['processing', 'completed'])
            ->orderBy('created_at', 'DESC')
            ->get();

            $orders_current_month = \Codeman\Admin\Models\Shop\Order::select('subtotal', 'total', 'shipping_price', 'created_at')
//            ->whereIn('status', ['processing', 'completed'])
            ->whereBetween('created_at',[$start_of_current_month, $end_of_current_month])
            ->orderBy('created_at', 'DESC')
            ->get();

            $orders_past_month = \Codeman\Admin\Models\Shop\Order::select('subtotal', 'total', 'shipping_price', 'created_at')
//            ->whereIn('status', ['processing', 'completed'])
            ->whereBetween('created_at',[$start_of_prev_month, $end_of_prev_month])
            ->orderBy('created_at', 'DESC')
            ->get();

            $orders_refounds = \Codeman\Admin\Models\Shop\Order::select('subtotal')
            ->where('status', 'RETURNED')
            ->orderBy('created_at', 'DESC')
            ->get();

            $orders_refounds_current_month = \Codeman\Admin\Models\Shop\Order::select('subtotal')
            ->where('status', 'refound')
            ->whereBetween('created_at',[$start_of_current_month, $end_of_current_month])
            ->orderBy('created_at', 'DESC')
            ->get();

            $orders_refounds_past_month = \Codeman\Admin\Models\Shop\Order::select('subtotal')
            ->where('status', 'RETURNED')
            ->whereBetween('created_at',[$start_of_prev_month, $end_of_prev_month])
            ->orderBy('created_at', 'DESC')
            ->get();

            $orders_data['lifetime']['total_orders'] = $orders->count();
            $orders_data['lifetime']['total_revenue'] = $orders->sum('total');
            $orders_data['lifetime']['subtotal_revenue'] = $orders->sum('subtotal');
            $orders_data['lifetime']['subtotal_shipping_price'] = $orders->sum('shipping_price');
            $orders_data['lifetime']['total_refound_count'] = $orders_refounds->count();
            $orders_data['lifetime']['total_refound'] = $orders_refounds->sum('subtotal');

            $orders_data['current_month']['total_orders'] = $orders_current_month->count();
            $orders_data['current_month']['total_revenue'] = $orders_current_month->sum('total');
            $orders_data['current_month']['subtotal_revenue'] = $orders_current_month->sum('subtotal');
            $orders_data['current_month']['subtotal_shipping_price'] = $orders_current_month->sum('shipping_price');
            $orders_data['current_month']['total_refound_count'] = $orders_refounds_current_month->count();
            $orders_data['current_month']['total_refound'] = $orders_refounds_current_month->sum('subtotal');

            $orders_data['past_month']['total_orders'] = $orders_past_month->count();
            $orders_data['past_month']['total_revenue'] = $orders_past_month->sum('total');
            $orders_data['past_month']['subtotal_revenue'] = $orders_past_month->sum('subtotal');
            $orders_data['past_month']['subtotal_shipping_price'] = $orders_past_month->sum('shipping_price');
            $orders_data['past_month']['total_refound_count'] = $orders_refounds_past_month->count();
            $orders_data['past_month']['total_refound'] = $orders_refounds_past_month->sum('subtotal');

            // dd($orders, $orders_total, $orders_subtotal, $orders_shipping_price);


            $days_list = array();
            $period = \Carbon\CarbonPeriod::create($start_of_current_month, $end_of_current_month);
            foreach ($period as $key => $date) {
                $days_list[$date->format('m/d/Y')] = $key;
            }
            $orders_chart_current = \Codeman\Admin\Models\Shop\Order::select('created_at', 'subtotal')->orderBy('created_at', 'ASC')
//            ->whereIn('status', ['processing', 'completed'])
            ->whereBetween('created_at',[$start_of_current_month, $end_of_current_month])->get()->groupBy(
                function ($item) {
                      return $item->created_at->format('m/d/Y'); // given date is mutated to carbon by eloquent..
                      return (new \DateTime($item->created_at))->format('m/d/Y'); // ..othwerise
                    })->reduce(function ($result, $group) {
                      return $result->put($group->first()->created_at->format('m/d/Y'), $group->sum('subtotal'));
                    }, collect()
                )->toArray();
            $orders_chart_current = array_merge($days_list, $orders_chart_current);

            $days_list = array();
            $period = \Carbon\CarbonPeriod::create($start_of_prev_month, $end_of_prev_month);

            foreach ($period as $key =>  $date) {
                $days_list[$date->format('m/d/Y')] = 0;
            }

            $orders_chart_prev = \Codeman\Admin\Models\Shop\Order::select('created_at', 'subtotal')->orderBy('created_at', 'ASC')
//            ->whereIn('status', ['processing', 'completed'])
            ->whereBetween('created_at',[$start_of_prev_month, $end_of_prev_month])->get()->groupBy(
            function ($item) {
                  return $item->created_at->format('m/d/Y'); // given date is mutated to carbon by eloquent..
                  return (new \DateTime($item->created_at))->format('m/d/Y'); // ..othwerise
                })->reduce(function ($result, $group) {
                  return $result->put($group->first()->created_at->format('m/d/Y'), $group->sum('subtotal'));
                }, collect()
            )->toArray();
            $orders_chart_prev = array_merge($days_list, $orders_chart_prev);


            $orders_data['sales_chart']['current'] = json_encode($orders_chart_current);
            $orders_data['sales_chart']['prev'] = json_encode($orders_chart_prev);

            // Products Bestsellers
            // $best_sellers = \Codeman\Admin\Models\Shop\OrderItem::has('order')->has('product')
            // ->with(['order', 'product'])
            // ->select(DB::raw('SUM(order_items.qty) as count', 'order_items.product_id'), DB::raw('SUM(order_items.price) as total_price', 'product_id'), 'order_items.product_id', 'orders.status as order_status')
            // ->join('orders', 'orders.id', 'order_items.order_id')
            // ->whereIn('orders.status', ['processing', 'completed'])
            // ->groupBy('product_id')
            // ->orderBy('count', 'DESC')
            // ->take(20)
            // ->get();

            // Variations Bestsellers
             $best_sellers = \Codeman\Admin\Models\Shop\OrderItem::has('order')->has('variation')
             ->with(['order', 'variation'])
             ->select(DB::raw('SUM(order_items.qty) as count', 'order_items.product_id'), DB::raw('SUM(order_items.price) as total_price', 'variation_id'), 'order_items.variation_id', 'orders.status as order_status')
             ->join('orders', 'orders.id', 'order_items.order_id')
//             ->whereIn('orders.status', ['processing', 'completed'])
             ->groupBy('variation_id')
             ->orderBy('count', 'DESC')
             ->take(20)
             ->get();
        }

    	return view('admin-panel::dashboard', [
            'pages_count' => Page::where('lang', $lang)->count(),
            'resources' => isset($resources) ? $resources : null,
            'orders_data' => isset($orders_data) ? $orders_data : null,
            'best_sellers' => isset($best_sellers) ? $best_sellers : null
            // 'sessionsCharterData' => $sessionsCharterData,
            // 'dates' => $dates,
            // 'visitors' => $visitors,
            // 'pageviews' => $pageViews,
            // 'analyticStats' => isset($analyticStats) ? $analyticStats->totalsForAllResults : array(),
            // 'countriesSessions' => $countriesSessions,
            // 'countriesSessionsPersent' => $countriesSessionsPersent,
        ]);
    }
}
