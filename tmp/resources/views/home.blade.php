@extends('layouts.header')
@section('title','Competitive breakin League')
@section('current-page','Dashboard')
@section('main-section')
<style media="screen">
.footer {
  position: absolute;
  right: 0;
  bottom: 0;
  left: 0;
  padding: 1rem;
  background-color: #efefef;
  text-align: center;
}
#content-panel .chat-panel .chat .right .chat-body{
  border-radius: 0px;
  margin-top: 0px;
  margin-right: 13px;
}
</style>
  <!-- //////////////////////////////////////////////////// Content-Panel div -->
  <div id="content-panel">
  <div class="container-fluid">





  <div class="row">

          <!-- //////////////////////////////////////////////////// Bar Chart -->
          <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12 sale-bar">
              <div class="panel">
                  <div class="panel-heading">
                  <h3 class="pull-left">Participant</h3>



                  </div> <!-- /panel-heading -->

                  <div class="panel-body dashboard-panel m-t-10">
                  <div class="canvas-holder">
                      <canvas id="barChart"></canvas>
                  </div> <!-- /canvas-holder -->
                  </div> <!-- /panel-body -->
              </div> <!-- /panel-->
          </div> <!-- /col -->

          <!-- //////////////////////////////////////////////////// Statistics -->

          <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 stat-item">
          <div class="panel">
              <div class="panel-body">
                  <div class="col-xs-9 left-content no-padding pull-left">
                      <h2>Total Participant</h2>
                      <div class="statistics" data-from="0" data-to="{{DB::table('participant')->count()}}"  data-refresh-interval="50"></div>
                  </div>
                  <div class="col-xs-3 right-content no-padding pull-right">
                      <span><i class="fa fa-user" style="color:white" aria-hidden="true"></i></span>
                  </div>
              </div> <!-- /panel-body -->

          </div> <!-- /panel -->
          </div> <!-- /col-lg-3 -->

          <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 stat-item">
              <div class="panel">
                  <div class="panel-body">
                      <div class="col-xs-9 left-content no-padding pull-left">
                          <h2> Total teams</h2>
                          <div class="statistics" data-from="0" data-to="{{ DB::table('teams')->count()}}" data-refresh-interval="50"></div>
                      </div>
                      <div class="col-xs-3 right-content no-padding pull-right">
                          <span><i class=" fa  fa-trophy " style="color:white" aria-hidden="true"></i></span>
                      </div>
                  </div> <!-- /panel-body -->

              </div> <!-- /panel -->
          </div> <!-- /col-lg-3 -->

          <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 stat-item">
          <div class="panel">
              <div class="panel-body">
                  <div class="col-xs-9 left-content no-padding pull-left">
                      <h2>Total Judges</h2>
                      <div class="statistics" data-from="0" data-to="{{ DB::table('judges')->count()}}" data-speed="1000" data-refresh-interval="50"></div>
                  </div>
                  <div class="col-xs-3 right-content no-padding pull-right">
                      <span> <i class="fa fa-user" style="color:white" aria-hidden="true"></i></span>
                  </div>
              </div> <!-- /panel-body -->

          </div> <!-- /panel -->
          </div> <!-- /col-lg-3 -->
          <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 stat-item">
          <div class="panel">
              <div class="panel-body">
                  <div class="col-xs-9 left-content no-padding pull-left">
                      <h2>Total Sponsors</h2>
                      <div class="statistics" data-from="0" data-to="{{ DB::table('sponsors')->count()}}" data-speed="1000" data-refresh-interval="50"></div>
                  </div>
                  <div class="col-xs-3 right-content no-padding pull-right">
                      <span> <i class="fa fa-users" style="color:white" aria-hidden="true"></i></span>
                  </div>
              </div> <!-- /panel-body -->

          </div> <!-- /panel -->
          </div> <!-- /col-lg-3 -->

  </div> <!-- /row -->




  </div> <!-- /container-fluid -->
  </div> <!-- /content-panel -->

  <!-- /////////////////////////////// Scripts /////////////////////////////// -->
  <!-- Offline jQuery script -->
  <!-- <script  type="text/javascript" src="jquery.min"></script>  -->
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script  type="text/javascript" src="{{URL::asset('public/js/bootstrap.min.js')}}"></script>

  <!-- Menu Script -->
  <script  type="text/javascript" src="{{URL::asset('public/js/menu/metisMenu.min.js')}}"></script>
  <script type="text/javascript" src="{{URL::asset('public/js/menu/nanoscroller.js')}}"></script>

  <!-- Data Range Picker Script -->
  <script type="text/javascript" src="{{URL::asset('public/js/moment.js')}}"></script>
  <script type="text/javascript" src="{{URL::asset('public/js/daterangepicker/daterangepicker.js')}}"></script>

  <!-- CountTo Script -->
  <script type="text/javascript" src="{{URL::asset('public/js/countTo/jquery.countTo.js')}}"></script>

  <!-- Morris Chart Script -->
  <script  type="text/javascript" src="{{URL::asset('public/js/morris-js/raphael.min.js')}}"></script>
  <script  type="text/javascript" src="{{URL::asset('public/js/morris-js/morris.min.js')}}"></script>

  <!-- Chart.js Script -->
  <script type="text/javascript" src="{{URL::asset('public/js/chart-js/Chart.js')}}"></script>

  <!-- Flot.js Script -->
  <script type="text/javascript" src="{{URL::asset('public/js/flot-js/excanvas.min.js')}}"></script>
  <script type="text/javascript" src="{{URL::asset('public/js/flot-js/jquery.flot.js')}}"></script>
  <script type="text/javascript" src="{{URL::asset('public/js/flot-js/jquery.flot.resize.js')}}"></script>
  <script type="text/javascript" src="{{URL::asset('public/js/flot-js/jquery.flot.time.js')}}"></script>

  <!-- Data Tables Script -->
  <script type="text/javascript" src="{{URL::asset('public/js/datatables/datatables.min.js')}}"></script>

  <!-- VMap Script -->
  <script type="text/javascript" src="{{URL::asset('public/js/vmap/jquery.vmap.js')}}"></script>
  <script type="text/javascript" src="{{URL::asset('public/js/vmap/maps/jquery.vmap.usa.js')}}"></script>

  <!-- Dashboard script -->
  {{-- <script type="text/javascript" src="{{URL::asset('public/js/dashboard.js')}}"></script> --}}

  <!-- Theme Configurator script -->
  <script type="text/javascript" src="{{URL::asset('public/js/jQuery.style.switcher.min.js')}}"></script>

  <script type="text/javascript" src="{{URL::asset('public/js/jquery-functions.js')}}"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $('.statistics').countTo();
      // ------------------- 1.Bar Chart -------------------
var canvas = document.getElementById('barChart');

var data = {
    labels: [
      @php
        $currentMonth = date('m');
        for ($i=1; $i <= 12 ; $i++) {
          $newi = ($i + $currentMonth - 1) % 12 + 1;
          if ($i == 12) {

              echo '"' . $months[1][$newi] . '"';
              continue;
          }
          echo '"' . $months[1][$newi] . '" ,';
        }

      @endphp
      // "May","June","July","August","September","October","November","December","January","February","March"
    ],
    datasets: [
        {
            label: "Participant joined",
            backgroundColor:'#CB132D',
            borderColor:'#CB132D',
            borderWidth: 1,
            data: [
              @php
                $currentMonth = date('m');
                for ($i=1; $i <= 12 ; $i++) {
                  $newi = ($i + $currentMonth - 1) % 12 + 1;
                  if ($i == 12) {

                      echo $months[0][$newi];
                      continue;
                  }
                  echo $months[0][$newi] . ',';
                }

              @endphp

            ],
            // data: [95, 64, 70, 55, 56, 45, 52, 59, 57, 50, 68, 82],
        },
    ]
};//bar data

var option = {
maintainAspectRatio: false,
responsive: true,
    legend:{
    display:false,
    }
};
var myBarChart = Chart.Bar(canvas,{
data:data,
options:option
});

    });




  </script>

  </body>

  </html>
@endsection
