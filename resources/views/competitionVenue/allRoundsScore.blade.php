@extends('layouts.header')
@section('title','Competitive breakin League')
@section('current-page','Dashboard')
@section('main-section')

  <div id="content-panel">
  <div class="container">

  <div class="row">
          @if(Session::has('status'))
          <div class="panel
          @if (Session::has('alert'))
            {{ Session('alert') }}
          @endif">
            <div class="panel-body" style="padding-top:10px;">
          <div id="alertbox" class="col-md-4 col-xs-12">
              <p>{{Session::get('status')}}</p>
          </div>
        </div>
          @endif
    <div class="panel panel panel-default col-md-11 " style="margin-left:10px;padding-top:15px;">
      <div class="panel-body">

    <div class="col-md-12">
      <table id="customer-list" class="table table-striped" >
      <thead>
          <tr>
              <th>#</th>
              <th>Round</th>
              <th>First Team</th>
              <th>Score</th>
              <th>Second Team</th>
              <th>Score</th>
          </tr>
      </thead>
          <tbody>
            @foreach ($allMatches as $key => $match)
              <tr>
                <td>{{$key + 1}}</td>
                <td>{{$match->roundNo}}</td>
                <td>{{$match->t1name}}</td>
                <td>{{$match->t1score}}</td>
                <td>{{$match->t2name}}</td>
                <td>{{$match->t2score}}</td>
              </tr>
            @endforeach
          </tbody>
      </table>

    </div>

  </div>
</div>

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
      "pageLength": 25 , 
        "columnDefs": [{
        "orderable": false
        }]
    });
});
</script>
  </body>

  </html>
@endsection
