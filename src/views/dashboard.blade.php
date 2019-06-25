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

      <!-- Info boxes -->
      <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="info-box ">
                    <a href="{!! route('page-index') !!}" >
                    <span class="info-box-icon bg-aqua"><i class="fa fa-window-restore"></i></span>
                    </a>
                    <div class="info-box-content">
                      <span class="info-box-text" style="">Pages
                      <span class="info-box-number">{!! $pages_count !!}</span>
                      </span>
                      <a href="{!! route('page-index') !!}" class="small-box-footer info-box-text" style="position: absolute;right: 30px; bottom: 30px;">View all <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="info-box ">
                    <a href="{!! route('service-index') !!}">
                    <span class="info-box-icon bg-red"><i class="fas fa-concierge-bell"></i></i></span>
                    </a>

                    <div class="info-box-content">
                      <span class="info-box-text"  style="">Services
                      <span class="info-box-number">{!! $services_count !!}</span>
                      </span>
                    <a href="{!! route('service-index') !!}" class="small-box-footer info-box-text" style="position: absolute;right: 30px; bottom: 30px;">View all <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>

            <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="info-box">
                    <a href="{!!route('file-index') !!}">
                    <span class="info-box-icon bg-green"><i class="fas fa-briefcase"></i></span>
                    </a>

                  <div class="info-box-content">
                      <span class="info-box-text"  style="">Files
                      <span class="info-box-number">{!! $files_count !!}</span>
                      </span>
                      <a href="{!! route('file-index') !!}" class="small-box-footer info-box-text" style="position: absolute;right: 30px; bottom: 30px;">View all <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="info-box">
                    <a href="{!! route('lecturer-index') !!}">
                    <span class="info-box-icon bg-yellow"><i class="fas fa-users"></i></i></span>
                    </a>

                    <div class="info-box-content">
                      <span class="info-box-text"  style="">Team
                      <span class="info-box-number">{!! $lecturers_count !!}</span></span>
                    </div>
                    <a href="{!! route('lecturer-index') !!}" class="small-box-footer info-box-text" style="position: absolute;right: 30px; bottom: 30px;">View all <i class="fa fa-arrow-circle-right"></i></a>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="info-box">
                    <a href="{!! route('reviews-index') !!}">
                    <span class="info-box-icon bg-aqua"><i class="fas fa-handshake"></i></i></span>
                    </a>
                    <div class="info-box-content">
                      <span class="info-box-text" style="">Partners
                      <span class="info-box-number">{!! $reviews_count !!}</span>
                      </span>
                    </div>
                    <a href="{!! route('reviews-index') !!}" class="small-box-footer info-box-text" style="position: absolute;right: 30px; bottom: 30px;">View all <i class="fa fa-arrow-circle-right"></i></a>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
{{--            <div class="col-md-4 col-sm-6 col-xs-12">--}}
{{--              <div class="info-box" >--}}
{{--                    <a href="{!! route('applications-index') !!}">--}}
{{--                      <span class="info-box-icon bg-blue"><i class="fas fa-address-card"></i></i></span>--}}
{{--                    </a>--}}
{{--                    <div class="info-box-content">--}}
{{--                      <span class="info-box-text" style="">Applicants--}}
{{--                        <span class="info-box-number">{!! $applicants_count !!}</span>--}}
{{--                      </span>--}}
{{--                    </div>--}}
{{--                    <a href="{!! route('applications-index') !!}" class="small-box-footer info-box-text" style="position: absolute;right: 30px; bottom: 30px;">View all <i class="fa fa-arrow-circle-right"></i></a>--}}
{{--                      --}}
{{--                --}}
{{--                <!-- /.info-box-content -->--}}
{{--              </div>--}}
{{--              <!-- /.info-box -->--}}
{{--            </div>--}}

           <!--  <div class="col-md-6 col-sm-6 col-xs-12">
              <div class="info-box bg-red">
                <a href="{!! route('subscriber-index') !!}">
                    <span class="info-box-icon bg-red"><i class="fa fa-envelope"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text" style="font-size: 20px; padding-top: 20px; color: #fff">Subscribers</span>
                    </div>
                </a>
                
              </div>
              
            </div> -->

        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      <div class="row">
              <div class="col-md-12">
                <div class="box">
                  <div class="box-header with-border">
                    <h3 class="box-title">Google Analytics Reports</h3>
                    <div class="box-tools pull-right">
                      
                    </div>
                  </div>
                  <!-- /.box-header -->
                  <div class="box-body">
                    <div class="row">
                      <div class="col-md-12">
                        <p class="text-center">
                          <strong>Users ({!! $dates[0] !!}  -  {!! $dates[count($dates) - 1] !!}) </strong>
                        </p>

                        <div class="chart">
                          <!-- Sales Chart Canvas -->
                          {{-- <canvas id="salesChart" width="1526" height="400"></canvas> --}}
                          <div class="chart" id="sessionsCharterContainer" style="height: 400px; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
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
                          {{-- <span class="description-percentage text-green"><i class="fa fa-caret-up"></i> 17%</span> --}}
                          <h5 class="description-header">{!! number_format($analyticStats['ga:users']) !!}</h5>
                          <span class="description-text"> Users </span>
                        </div>
                        <!-- /.description-block -->
                      </div>
                      <!-- /.col -->
                      <div class="col-sm-3 col-xs-6">
                        <div class="description-block border-right">
                          {{-- <span class="description-percentage text-yellow"><i class="fa fa-caret-left"></i> 0%</span> --}}
                          <h5 class="description-header">{!! number_format($analyticStats['ga:pageviews']) !!}</h5>
                          <span class="description-text"> Page views</span>
                        </div>
                        <!-- /.description-block -->
                      </div>
                      <!-- /.col -->
                      <div class="col-sm-3 col-xs-6">
                        <div class="description-block border-right">
                          {{-- <span class="description-percentage text-green"><i class="fa fa-caret-up"></i> 20%</span> --}}
                          <h5 class="description-header">{!! round($analyticStats['ga:bounceRate'],2) !!} %</h5>
                          <span class="description-text">Bounce Rate</span>
                        </div>
                        <!-- /.description-block -->
                      </div>
                      <!-- /.col -->
                      <div class="col-sm-3 col-xs-6">
                        <div class="description-block">
                          {{-- <span class="description-percentage text-red"><i class="fa fa-caret-down"></i> 18%</span> --}}
                          <h5 class="description-header">
                            {!! number_format(date('i', gmdate($analyticStats['ga:avgSessionDuration']))) !!}m 
                            {!! number_format(date('s', gmdate($analyticStats['ga:avgSessionDuration']))) !!}s 
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
                          {{-- <button type="button" class="btn btn-primary btn-sm daterange pull-right" data-toggle="tooltip" title="" data-original-title="Date range">
                            <i class="fa fa-calendar"></i></button>
                          <button type="button" class="btn btn-primary btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="" style="margin-right: 5px;" data-original-title="Collapse">
                            <i class="fa fa-minus"></i></button> --}}
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
                {{-- <button type="button" class="btn bg-teal btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn bg-teal btn-sm" data-widget="remove"><i class="fa fa-times"></i>
                </button> --}}
              </div>
            </div>
            <div class="box-body border-radius-none">
              <div class="chart" id="countriesSessionsPersentBarContainer" style="height: 500px; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
              </div>
            </div>
            <!-- /.box-body -->
          </div>
              </div>
              <!-- /.col -->
            </div>
@endsection

@section('script')
  <script>
    {{-- // let dates = {!! json_encode($dates) !!}; --}}
    {{-- // let pageview = {!! json_encode($pageviews) !!}; --}}
    {{-- // let visitors = {!! json_encode($visitors) !!}; --}}
    let sessionsCharterData = {!! json_encode($sessionsCharterData) !!};
    let countriesSessions = {!! json_encode($countriesSessions) !!};
    let countriesSessionsPersent = {!! json_encode($countriesSessionsPersent) !!};
    
  </script>
  

  <script src="{!! asset('admin-panel/bower_components/chart.js/Chart.js' ) !!}"></script>
  <script src="{!! asset('admin-panel/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js' ) !!}"></script>
  <script src="{!! asset('admin-panel/plugins/jvectormap/jquery-jvectormap-world-mill-en.js' ) !!}"></script>
  <!-- Morris.js charts -->
  <script src="{!! asset('admin-panel/bower_components/raphael/raphael.min.js' ) !!}"></script>
  <script src="{!! asset('admin-panel/bower_components/morris.js/morris.min.js' ) !!}"></script>


  {{-- <script src="{!! asset('admin-panel/dist/js/pages/dashboard2.js' ) !!}"></script> --}}
  {{-- <script src="{!! asset('admin-panel/dist/js/pages/dashboard.js' ) !!}"></script> --}}
  <script src="{!! asset('admin-panel/dist/js/demo.js' ) !!}"></script>

  <script>
    var visitorsData = countriesSessions;
    // World map by jvectormap
    $('#world-map').vectorMap({
      map              : 'world_mill_en',
      backgroundColor  : 'transparent',
      regionStyle      : {
        initial: {
          fill            : '#e4e4e4',
          'fill-opacity'  : 1,
          stroke          : 'none',
          'stroke-width'  : 0,
          'stroke-opacity': 1
        }
      },
      series           : {
        regions: [
          {
            values           : visitorsData,
            scale            : ['#92c1dc', '#ebf4f9'],
            normalizeFunction: 'polynomial'
          }
        ]
      },
      onRegionLabelShow: function (e, el, code) {
        // if (typeof visitorsData[code] != 'undefined')
          el.html(el.html() + ': ' + visitorsData[code] + ' new visitors');
      }
    });

    /* Morris.js Charts */

$(document).ready(function(){
  var sessionsCharter = new Morris.Line({
      element   : 'sessionsCharterContainer',
      resize    : true,
      data      : sessionsCharterData,
      xkey      : 'date',
      // ykeys     : ['date', 'date2'],
      ykeys     : ['visitors' ],
      // labels    : ['Item 1', 'Item 2'],
      labels    : ['Users'],
      // lineColors: ['#a0d0e0', '#3c8dbc'],
      lineColors: ['#a0d0e0'],
      hideHover : 'auto',
      xLabelFormat: function (d) {
          var weekdays = new Array(7);
          weekdays[0] = "Sun";
          weekdays[1] = "Mon";
          weekdays[2] = "Tue";
          weekdays[3] = "Wed";
          weekdays[4] = "Thu";
          weekdays[5] = "Fri";
          weekdays[6] = "Sat";

          var months = new Array(12);
          months[0] = "Jan";
          months[1] = "Feb";
          months[2] = "Mar";
          months[3] = "Apr";
          months[4] = "May";
          months[5] = "Jun";
          months[6] = "Jul";
          months[7] = "Aug";
          months[8] = "Sep";
          months[9] = "Oct";
          months[10] = "Nov";
          months[11] = "Dec";

          return weekdays[d.getDay()] + ' ' + 
                  d.getDate() + ' ' + 
                  months[d.getMonth()]
                 // ("0" + (d.getMonth() + 1)).slice(-2) + '-' + 
                 // ("0" + (d.getDate())).slice(-2);
        },
    });

    var countriesSessionsPersentBar = new Morris.Bar({
      element          : 'countriesSessionsPersentBarContainer',
      resize           : true,
      data             : countriesSessionsPersent,
      xkey             : 'country',
      ykeys            : ['persent'],
      labels           : ['Visitors %'],
      lineColors       : ['#efefef'],
      lineWidth        : 2,
      hideHover        : 'auto',
      gridTextColor    : '#fff',
      gridStrokeWidth  : .8,
      pointSize        : 4,
      pointStrokeColors: ['#efefef'],
      gridLineColor    : '#efefef',
      // gridTextFamily   : 'Open Sans',
      gridTextSize     : 10,
      hoverCallback: function (index, options, content, row) {
        return  row.country + ': '+ row.persent*100/100 + '%';
      }
    });
    countriesSessionsPersentBar.redraw();
    sessionsCharter.redraw();

});

  </script>

@endsection
