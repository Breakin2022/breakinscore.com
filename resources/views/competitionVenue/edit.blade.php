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
                <h3 style="margin-bottom: 26px;"> <span class="pull-left">Edit Competition Venue</span></h3>
            </div> <!-- /panel-heading -->
            <div class="panel-body m-t-0">

            <form method="POST" action="{{route('competitionVenue.update', $competitionVenues[0]->id)}}">
            {{method_field('PUT')}}
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

              <div class="row">
                      <div class="form-group col-md-4">
                          <label for="title">Title</label>
                          <input type="text" class="form-control" id="title" value="{{$competitionVenues[0]->title}}" name="title" required="">
                      </div>
                </div>
            <div class="row">
                    <div class="form-group col-md-4">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" id="address" value="{{$competitionVenues[0]->address}}" name="address">
                    </div>
              </div>
            <div class="row">
                    <div class="form-group col-md-4">
                        <label for="phone">Phone</label>
                        <input type="text" class="form-control" id="phone" value="{{$competitionVenues[0]->phone}}" name="phone">
                    </div>
              </div>

              <div class="row">
                      <div class="form-group col-md-4">
                          <label for="start_date">Start Date</label>
                          <input type="date"  class="form-control" class="datepicker" id="start_date" value="{{
                            date("Y-m-d", strtotime($competitionVenues[0]->start_date))
                          }}" name="start_date" required="">
                      </div>
              </div>

              <button type="submit" class="btn btn-md bg-primary"><span>Submit</span></button>

              </form> <!-- /form -->

             </div> <!-- /panel-body -->
            </div> <!-- /panel-->

          </div> <!-- /col -->

          <!-- //////////////////////////////////////////////////// Statistics -->

          {{-- <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 " style="color:black;">
          <div class="panel">
              <div class="panel-body">
                  <p>yellow</p>
              </div> <!-- /panel-body -->

          </div> <!-- /panel -->
          </div> <!-- /col-lg-3 --> --}}

          {{-- <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 stat-item">
              <div class="panel">
                  <div class="panel-body">
                      <div class="col-xs-9 left-content no-padding pull-left">
                          <h2> Total Games</h2>
                          <div class="statistics" data-from="0" data-to="" data-speed="3500" data-refresh-interval="50"></div>
                      </div>
                      <div class="col-xs-3 right-content no-padding pull-right">
                          <span><i class="fa fa-gamepad" style="color:white" aria-hidden="true"></i></span>
                      </div>
                  </div> <!-- /panel-body -->

              </div> <!-- /panel -->
          </div> <!-- /col-lg-3 --> --}}

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
