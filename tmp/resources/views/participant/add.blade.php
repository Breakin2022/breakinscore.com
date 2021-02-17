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
                <h3 style="margin-bottom: 26px;"> <span class="pull-left">Add Participant</span></h3>
            </div> <!-- /panel-heading -->
            <div class="panel-body m-t-0">

            <form method="POST" action="{{route('participant.store')}}">

            <input type="hidden" name="_token" value="{{ csrf_token() }}">

              <div class="row">
                      <div class="form-group col-md-4">
                          <label for="name">Name</label>
                          <input type="text" class="form-control" id="name" name="name" required="">
                      </div>
                </div>
            <div class="row">
                    <div class="form-group col-md-4">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required="">
                    </div>
              </div>
            <div class="row">
                    <div class="form-group col-md-4">
                        <label for="phone">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" required="">
                    </div>
              </div>
              <div class="row">
                      <div class="form-group col-md-4">
                          <label for="nick">Nick Name</label>
                          <input type="text" class="form-control" id="nick" name="nick" required="">
                      </div>
              </div>
              <div class="row">
                      <div class="form-group col-md-4">
                          <label for="address">Address</label>
                          <input type="text" class="form-control" id="address" name="address" required="">
                      </div>
              </div>
              <div class="row">
                      <div class="form-group col-md-4">
                          <label for="join_date">Joining Date</label>
                          {{-- <input type="date"  class="form-control" class="datepicker" id="join_date" value=""> --}}
                          <input type="date" class="form-control" id="join_date" name="join_date" required="">
                      </div>
              </div>

              <button type="submit" class="btn btn-md bg-purple"><span>Submit</span></button>

              </form> <!-- /form -->

             </div> <!-- /panel-body -->
            </div> <!-- /panel-->

          </div> <!-- /col -->

          <!-- //////////////////////////////////////////////////// Statistics -->



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
