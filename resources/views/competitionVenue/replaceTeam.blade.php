@extends('layouts.header')
@section('title','Competitive breakin League')
@section('current-page','Dashboard')
@section('main-section')

  <div id="content-panel">
  <div class="container-fluid">

  <div class="row">
          @if(Session::has('status'))
          <div class="panel
          @if (Session::has('alert'))
            {{ Session('alert') }}
          @endif
          ">
            <div class="panel-body" style="padding-top:10px;">
          <div id="alertbox" class="col-md-4 col-xs-12">
              <p>{{Session::get('status')}}</p>
          </div>
        </div>
      </div>
          @endif

          <!-- //////////////////////////////////////////////////// Bar Chart -->
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel">
            <div class="panel-heading">
                <h3 style="margin-bottom: 26px;"> <span class="pull-left">Add Competition</span></h3>
            </div> <!-- /panel-heading -->
            <div class="panel-body m-t-0">

            <form method="POST" action="{{route('competitionVenue.store')}}">

            <input type="hidden" name="_token" value="{{ csrf_token() }}">

              <div class="row">
                      <div class="form-group col-md-4">
                          <label for="title">Title</label>
                          <input type="text" class="form-control" id="title" name="title" required="">
                      </div>
                </div>

            <div class="row">
                    <div class="form-group col-md-4">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" id="address" name="address">
                    </div>
              </div>
            <div class="row">
                    <div class="form-group col-md-4">
                        <label for="phone">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone">
                    </div>
              </div>

              <div class="row">
                      <div class="form-group col-md-4">
                          <label for="start_date">Start Date</label>
                          {{-- <input type="date"  class="form-control" class="datepicker" id="join_date" value=""> --}}
                          <input type="date" class="form-control" id="start_date" name="start_date" required="">
                      </div>
              </div>
              <div class="row">
                    <div class="form-group col-md-4">
                        <label for="phone">Competition Type</label><br>
                        <label class="radio-inline"><input type="radio" name="competitionType" value="1" required="">1 VS 1</label>
                        <label class="radio-inline"><input type="radio" name="competitionType" value="2" required="">2 VS 2</label>
                    </div>
              </div>
              <button type="submit" class="btn btn-md bg-purple"><span>Submit</span></button>

              </form> <!-- /form -->

             </div> <!-- /panel-body -->
            </div> <!-- /panel-->

          </div> <!-- /col -->



  </div> <!-- /row -->




  </div> <!-- /container-fluid -->
  </div> <!-- /content-panel -->


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
  <script>
$(document).ready(function() {
    $('#customer-list').DataTable({
        "columnDefs": [{
        "targets": 7,
        "orderable": false
        }]
    });
});
</script>
  </body>

  </html>
@endsection
