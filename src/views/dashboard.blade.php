@extends('admin-panel::layouts.app')
@section('style')
    <link rel="stylesheet" href="{!! asset('admin-panel/bower_components/jvectormap/jquery-jvectormap.css' ) !!}">
    <link rel="stylesheet" href="{!! asset('admin-panel/bower_components/morris.js/morris.css' ) !!}">
    <style>
        .jvectormap-label {
            position: absolute;
            display: none;
            border: solid 1px #CDCDCD;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px;
            background: #292929;
            color: white;
            font-family: sans-serif, Verdana;
            font-size: smaller;
            padding: 3px;
        }
    </style>
@endsection
@section('content')
    <section class="content">
@php
    $color = $rand = array('#008fca');
@endphp
    @if(env('is_shop'))
        <div class="row">
            <div class="col-lg-3 col-xs-6 h-100">
                  <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <small>All Time Orders</small>
                        <h3>{{ number_format($orders_data['lifetime']['total_orders'], 0) }}</h3>
                        <h5><span>Current month: </span>{{ number_format($orders_data['current_month']['total_orders'], 0) }} Orders</h5>
                        <h5><span>Previous Month: </span>{{ number_format($orders_data['past_month']['total_orders'], 0) }} Orders</h5>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="{{ route('admin.orders') }}" class="small-box-footer">See Orders
                        {{-- <i class="fa fa-arrow-circle-right"></i> --}}
                    </a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6 h-100">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <small>All Time Revenue</small>
                        <h3>{{ number_format($orders_data['lifetime']['subtotal_revenue'], 0) }}<sup style="font-size: 20px"> RUB</sup></h3>
                        <h5><span>Current month:</span> {{ number_format($orders_data['current_month']['subtotal_revenue'], 0) }}<sup style="font-size: 10px"> RUB</sup> </h5>
                        <h5><span>Previous month:</span> {{ number_format($orders_data['past_month']['subtotal_revenue'], 0) }}<sup style="font-size: 10px"> RUB</sup> </h5>
                    </div>
                    <div class="icon">
                        {{-- <i class="ion ion-cash-outline"></i> --}}
                        <i class="fa fa-dollar-sign"></i>
                    </div>
                    <p  class="small-box-footer">Without delivery cost</p>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6 h-100">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <small>All Time Delivery Costs</small>
                        <h3>{{ number_format($orders_data['lifetime']['subtotal_shipping_price'], 0) }}
                            <sup style="font-size: 20px"> RUB</sup>
                        </h3>
                        <h5><span>Current month:</span> {{ number_format($orders_data['current_month']['subtotal_shipping_price'], 0) }}<sup style="font-size: 10px"> RUB</sup> </h5>
                        <h5><span>Previous month:</span> {{ number_format($orders_data['past_month']['subtotal_shipping_price'], 0) }}<sup style="font-size: 10px"> RUB</sup> </h5>
                    </div>
                    <div class="icon">
                        <i class="fa fa-shipping-fast"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info
                        {{-- <i class="fa fa-arrow-circle-right"></i> --}}
                    </a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6 h-100">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <small>All Time Refounds</small>
                        <h3>
                            {{ number_format($orders_data['lifetime']['total_refound'], 0) }}
                            <sup style="font-size: 20px"> RUB</sup>
                        </h3>
                        <h5><span>Current month:</span> {{ number_format($orders_data['current_month']['total_refound'], 0) }}<sup style="font-size: 10px"> RUB</sup> </h5>
                        <h5><span>Previous month:</span> {{ number_format($orders_data['past_month']['total_refound'], 0) }}<sup style="font-size: 10px"> RUB</sup> </h5>
                    </div>
                    <div class="icon">
                        <i class="fa fa-undo"></i>
                    </div>
                    <p class="small-box-footer">{{ $orders_data['lifetime']['total_refound_count'] }} Products were returned</p>
                  </div>
            </div>
            <!-- ./col -->
        </div>
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Monthly report of sales</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="chart">
                        <canvas id="sales-chart" class="chartjs-render-monitor"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Best sellers</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    @if(isset($best_sellers) && !empty($best_sellers))
                    <ul class="products-list product-list-in-box" style="height: 443px; overflow-y: auto;">
                        @foreach($best_sellers as $item)
                            <li class="item">
                                <div class="product-img">
                                    @if($item->variation->secondary_thumbnail)
                                        <img src="{!! img_icon_size($item->variation->secondary_thumbnail) !!}" alt="{!! $item->variation->title !!}" style="object-fit: contain">
                                    @elseif($item->variation->thumbnail)
                                        <img src="{!! img_icon_size($item->variation->thumbnail) !!}" alt="{!! $item->variation->title !!}">
                                    @else
                                        <img src="" alt="{!! $item->variation->title !!}">
                                    @endif
                                </div>
                                <div class="product-info">
                                    <a href="{!! url('/admin/variations/'.$item->variation->id.'/edit') !!}" class="product-title" target="_blank">
                                        {!! $item->variation->title !!}
                                        <span class="label label-warning pull-right" style="padding-top: 5px">
                                            {!! number_format($item->total_price, 0) !!} <sup>RUB</sup>
                                        </span>
                                    </a>
                                    <span class="product-description">
                                        {!! $item->count !!} sales
                                    </span>
                                </div>
                            </li>
                            <!-- /.item -->
                        @endforeach
                    </ul>
                    @endif
                </div>
                <!-- /.box-body -->
                {{-- <div class="box-footer text-center">
                    <a href="javascript:void(0)" class="uppercase">View All Products</a>
                </div> --}}
                <!-- /.box-footer -->
            </div>
        </div>
        @endif
        <!-- Info boxes -->
        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box ">
                        <a href="{!! route('page-index') !!}">
                            <span class="info-box-icon" style="background-color: {{$color[array_rand($color)]}}; color:#fff"><i class="fa fa-window-restore"></i></span>
                        </a>
                        <div class="info-box-content">
                          <span class="info-box-text" style="">
                            <span class="info-box-number">{!! $pages_count !!}</span>
                            <span>Pages</span>
                          </span>
                            <a href="{!! route('page-index') !!}" class="small-box-footer info-box-text"
                               style="position: absolute;right: 30px; bottom: 30px;">View all <i
                                        class="fa fa-arrow-circle-right"></i></a>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                @isset($resources)
                    @foreach($resources as $res)
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="info-box ">
                                <a href="{!! route('resources.index', [$res->type]) !!}">
                                    <span class="info-box-icon" style="background-color: {!!$color[array_rand($color)]!!}; color:#fff"><i class="fa {{$res->icon}}"></i></span>
                                </a>
                                <div class="info-box-content">
                                  <span class="info-box-text" style="">{{$res->type}}
                                  <span class="info-box-number">{!! $res->total !!}</span>
                                  </span>
                                    <a href="{!! route('resources.index', [$res->type]) !!}"
                                       class="small-box-footer info-box-text"
                                       style="position: absolute;right: 30px; bottom: 30px;">View all <i
                                                class="fa fa-arrow-circle-right"></i></a>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                    @endforeach
                @endif
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        {{-- UNCOMENT FOR ANALYTICS --}}
        @if(isset($dates))
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Google Analytics Reports</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="text-center">
                                        <strong>Users ({!! $dates[0] !!} - {!! $dates[count($dates) - 1] !!}) </strong>
                                    </p>

                                    <div class="chart">
                                        <!-- Sales Chart Canvas -->
                                        {{-- <canvas id="salesChart" width="1526" height="400"></canvas> --}}
                                        <div class="chart" id="sessionsCharterContainer"
                                             style="height: 400px; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                                        </div>
                                    </div>
                                    <!-- /.chart-responsive -->
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- ./box-body -->
                        <div class="box-footer">
                            <div class="row">
                                <div class="col-sm-3 col-xs-6">
                                    <div class="description-block border-right">
                                       <span class="description-percentage text-green"><i class="fa fa-caret-up"></i> 17%</span>
                                        <h5 class="description-header">{!! number_format($analyticStats['ga:users']) !!}</h5>
                                        <span class="description-text"> Users </span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-3 col-xs-6">
                                   <div class="description-block border-right">
                                         <span class="description-percentage text-yellow"><i class="fa fa-caret-left"></i> 0%</span>
                                        <h5 class="description-header">{!! number_format($analyticStats['ga:pageviews']) !!}</h5>
                                        <span class="description-text"> Page views</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                               <div class="col-sm-3 col-xs-6">
                                    <div class="description-block border-right">
                                        <span class="description-percentage text-green"><i class="fa fa-caret-up"></i> 20%</span>
                                        <h5 class="description-header">{!! round($analyticStats['ga:bounceRate'],2) !!}
                                            %</h5>
                                        <span class="description-text">Bounce Rate</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                               <!-- /.col -->
                                <div class="col-sm-3 col-xs-6">
                                    <div class="description-block">
                                         <span class="description-percentage text-red"><i class="fa fa-caret-down"></i> 18%</span>
                                        <h5 class="description-header">
                                            {!! number_format(date('i', gmdate($analyticStats['ga:avgSessionDuration']))) !!}
                                            m
                                            {!! number_format(date('s', gmdate($analyticStats['ga:avgSessionDuration']))) !!}
                                            s
                                        </h5>
                                        <span class="description-text">Session Duration</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.box-footer -->
                    </div>
                    <!-- /.box -->
                </div>
                <div class="col-md-8">
                    <section class="connectedSortable ui-sortable">
                        <!-- Map box -->
                        <div class="box box-solid bg-light-blue-gradient">
                            <div class="box-header ui-sortable-handle" style="cursor: move;">
                                <!-- tools box -->
                               <div class="pull-right box-tools">
                                    <button type="button" class="btn btn-primary btn-sm daterange pull-right" data-toggle="tooltip" title="" data-original-title="Date range">
                                      <i class="fa fa-calendar"></i></button>
                                    <button type="button" class="btn btn-primary btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="" style="margin-right: 5px;" data-original-title="Collapse">
                                      <i class="fa fa-minus"></i></button>
                                </div>
                                <!-- /. tools -->

                                <i class="fa fa-map-marker"></i>

                                <h3 class="box-title">
                                    Visitors by country
                                </h3>
                            </div>
                            <div class="box-body">
                                <div id="world-map" style="height: 500px; width: 100%;"></div>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.box -->

                    </section>

                </div>
                <div class="col-md-4">
                    <div class="box box-solid bg-teal-gradient">
                        <div class="box-header ui-sortable-handle" style="cursor: move;">
                            <i class="fa fa-th"></i>

                           <h3 class="box-title">Percentage of visitors by countries</h3>

                            <div class="box-tools pull-right">
                                 <button type="button" class="btn bg-teal btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn bg-teal btn-sm" data-widget="remove"><i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body border-radius-none">
                            <div class="chart" id="countriesSessionsPersentBarContainer"
                                 style="height: 500px; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                            </div>
                       </div>
                       <!-- /.box-body -->
                   </div>
                </div>
                <!-- /.col -->
            </div>
        @endif
        {{-- UNCOMENT FOR ANALYTICS-- END --}}

    @endsection

        @section('script')

            @if(isset($dates))
            <script>
                // let dates = {!! json_encode($dates) !!};
                // let pageview = {!! json_encode($pageviews) !!};
                // let visitors = {!! json_encode($visitors) !!};
                // let sessionsCharterData = {!! json_encode($sessionsCharterData) !!};
                // let countriesSessions = {!! json_encode($countriesSessions) !!};
                // let countriesSessionsPersent = {!! json_encode($countriesSessionsPersent) !!};

            </script>
            @endif

            <script src="{!! asset('admin-panel/bower_components/chart.js/Chart.js' ) !!}"></script>
            <script src="{!! asset('admin-panel/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js' ) !!}"></script>
            <script src="{!! asset('admin-panel/plugins/jvectormap/jquery-jvectormap-world-mill-en.js' ) !!}"></script>
            <!-- Morris.js charts -->
            <script src="{!! asset('admin-panel/bower_components/raphael/raphael.min.js' ) !!}"></script>
            <script src="{!! asset('admin-panel/bower_components/morris.js/morris.min.js' ) !!}"></script>


            {{-- <script src="{!! asset('admin-panel/dist/js/pages/dashboard2.js' ) !!}"></script>
            {{-- <script src="{!! asset('admin-panel/dist/js/pages/dashboard.js' ) !!}"></script> --}}
            <script src="{!! asset('admin-panel/dist/js/demo.js' ) !!}"></script>

            <script>
                // var visitorsData = countriesSessions;
                // World map by jvectormap
                // $('#world-map').vectorMap({
                //     map: 'world_mill_en',
                //     backgroundColor: 'transparent',
                //     regionStyle: {
                //         initial: {
                //             fill: '#e4e4e4',
                //             'fill-opacity': 1,
                //             stroke: 'none',
                //             'stroke-width': 0,
                //             'stroke-opacity': 1
                //         }
                //     },
                //     series: {
                //         regions: [
                //             {
                //                 values: visitorsData,
                //                 scale: ['#92c1dc', '#ebf4f9'],
                //                 normalizeFunction: 'polynomial'
                //             }
                //         ]
                //     },
                //     onRegionLabelShow: function (e, el, code) {
                //         // if (typeof visitorsData[code] != 'undefined')
                //         el.html(el.html() + ': ' + visitorsData[code] + ' new visitors');
                //     }
                // });

                /* Morris.js Charts */

                $(document).ready(function () {

                    // var sessionsCharter = new Morris.Line({
                    //     element: 'sessionsCharterContainer',
                    //     resize: true,
                    //     data: sessionsCharterData,
                    //     xkey: 'date',
                    //     // ykeys     : ['date', 'date2'],
                    //     ykeys: ['visitors'],
                    //     // labels    : ['Item 1', 'Item 2'],
                    //     labels: ['Users'],
                    //     // lineColors: ['#a0d0e0', '#3c8dbc'],
                    //     lineColors: ['#a0d0e0'],
                    //     hideHover: 'auto',
                    //     xLabelFormat: function (d) {
                    //         var weekdays = new Array(7);
                    //         weekdays[0] = "Sun";
                    //         weekdays[1] = "Mon";
                    //         weekdays[2] = "Tue";
                    //         weekdays[3] = "Wed";
                    //         weekdays[4] = "Thu";
                    //         weekdays[5] = "Fri";
                    //         weekdays[6] = "Sat";

                    //         var months = new Array(12);
                    //         months[0] = "Jan";
                    //         months[1] = "Feb";
                    //         months[2] = "Mar";
                    //         months[3] = "Apr";
                    //         months[4] = "May";
                    //         months[5] = "Jun";
                    //         months[6] = "Jul";
                    //         months[7] = "Aug";
                    //         months[8] = "Sep";
                    //         months[9] = "Oct";
                    //         months[10] = "Nov";
                    //         months[11] = "Dec";

                    //         return weekdays[d.getDay()] + ' ' +
                    //             d.getDate() + ' ' +
                    //             months[d.getMonth()]
                    //         // ("0" + (d.getMonth() + 1)).slice(-2) + '-' +
                    //         // ("0" + (d.getDate())).slice(-2);
                    //     },
                    // });

                    // var countriesSessionsPersentBar = new Morris.Bar({
                    //     element: 'countriesSessionsPersentBarContainer',
                    //     resize: true,
                    //     data: countriesSessionsPersent,
                    //     xkey: 'country',
                    //     ykeys: ['persent'],
                    //     labels: ['Visitors %'],
                    //     lineColors: ['#efefef'],
                    //     lineWidth: 2,
                    //     hideHover: 'auto',
                    //     gridTextColor: '#fff',
                    //     gridStrokeWidth: .8,
                    //     pointSize: 4,
                    //     pointStrokeColors: ['#efefef'],
                    //     gridLineColor: '#efefef',
                    //     // gridTextFamily   : 'Open Sans',
                    //     gridTextSize: 10,
                    //     hoverCallback: function (index, options, content, row) {
                    //         return row.country + ': ' + row.persent * 100 / 100 + '%';
                    //     }
                    // });
                    // countriesSessionsPersentBar.redraw();
                    // sessionsCharter.redraw();
                    @if($orders_data)
                        var current = JSON.parse('{!! $orders_data['sales_chart']['current'] !!}');
                        var prev = JSON.parse('{!! $orders_data['sales_chart']['prev'] !!}');
                        var barChartData = {
                            labels : Object.keys(prev).length  > Object.keys(current).length ? Object.keys(prev) : Object.keys(current) ,
                            datasets : [
                                {
                                    fillColor : "#007bff",
                                    strokeColor : "#007bff",
                                    highlightFill: "#007bffde",
                                    highlightStroke: "#007bffde",
                                    data: current
                                },
                                {
                                    fillColor : "#d2d6de",
                                    strokeColor : "#d2d6de",
                                    highlightFill : "#d2d6dede",
                                    highlightStroke : "#d2d6dede",
                                    data: prev
                                }
                            ],

                        }

                        var $salesChart = document.getElementById("sales-chart").getContext("2d");
                        window.myBar = new Chart($salesChart).Bar(barChartData, {
                            responsive : true,
                        });
                    @endif
                });

            </script>

@endsection
