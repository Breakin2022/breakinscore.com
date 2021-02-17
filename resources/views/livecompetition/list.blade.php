@extends('layouts.header')
@section('title','Competitive breakin League')
@section('current-page','Dashboard')
@section('main-section')
  <div id="content-panel">
  <div class="container-fluid">



  <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel">
            <div class="panel-heading">
                <h3 style="margin-bottom: 26px;"> <span class="pull-left">Live Competitions</span></h3>
            </div>
            <div class="panel-body m-t-0">
            <div class="table-responsive">
            <table id="customer-list" class="table table-striped" >
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Round</th>
                    <th style="Display:none;"></th>
                    <th style="Display:none;"></th>
                    <th style="Display:none;"></th>
                    <th>action</th>
                </tr>
            </thead>
                <tbody>
                     @php
                     $i = 1;
                     @endphp
                     @foreach($arryColletion as $item)
                    <tr id="del{{$item->id}}">
                        <td>{{$i++}}</td>
                        <td  onclick="openCompetition('<?php echo $item->id; ?>')" id="red{{$item->title}}">{{$item->title}}</td>
                        <td id="red{{$item->type}}">{{$item->type}}</td>
                        <td >{{$item->round}}</td>
                        <td style="Display:none;"></td>
                        <td style="Display:none;"></td>
                        <td style="Display:none;"></td>
                        <td></td>
                    </tr>
                  @endforeach


                    </tbody>
                    </table>
                </div> <!-- /table-responsive -->
                </div> <!-- /panel-body -->
            </div> <!-- /panel-->

          </div> <!-- /col -->

          

  </div> <!-- /row -->


  @foreach($arryColletion as $item)
  <div class="row competitionTeams" id="competitionTeam{{$item->id}}" style="Display:none;">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel">
            <div class="panel-heading">
                <h3 style="margin-bottom: 26px;"> <span class="pull-left">{{$item->title}}</span></h3>
            </div>
            <div class="panel-body m-t-0">
            <div class="table-responsive">
            <table class="table table-striped" >
            <thead>
                <tr>
                    <th>#</th>
                    <th>First team</th>
                    <th>Second team</th>
                    <th>Round</th>
                    <th style="Display:none;"></th>
                    <th style="Display:none;"></th>
                    <th style="Display:none;"></th>
                    <th>action</th>
                </tr>
            </thead>
                <tbody>
                     @php
                     $i = 1;
                     @endphp

                     @foreach($item->matches as $match)
                    <tr>
                        <td>{{$i++}}</td>
                        <td id="red{{$item->title}}">{{$match->teamOne->name}}</td>
                        <td id="red{{$item->type}}">{{$match->teamTwo->name}}</td>
                        <td >{{$match->roundNo}}</td>
                        <td style="Display:none;"></td>
                        <td style="Display:none;"></td>
                        <td style="Display:none;"></td>
                        <td>
                            @if($match->start_time == null)
                            <a href="{{Route('insertscoresofmatch', ['matchid' => $match->id, 'competitionid' => $item->id])}}" class="btn btn-xs btn-primary hidden-xs hidden-sm">Start Match</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach

                    </tbody>
                    </table>
                </div> <!-- /table-responsive -->
                </div> <!-- /panel-body -->
            </div> <!-- /panel-->

          </div> <!-- /col -->

          

  </div> <!-- /row -->
@endforeach

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

function openCompetition(id){

    var divID = "#competitionTeam" + id;
    $(".competitionTeams").css("display","none");
    $(divID).css("display","inline");

}
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
