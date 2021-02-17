@extends('layouts.header')
@section('title','Competitive breakin League')
<!-- @section('current-page','Dashboard') -->
@section('main-section')
  <link rel="stylesheet" type="text/css" href="{{URL::asset('public/css/jquery-ui.min.css')}}">

  <div id="content-panel">
  <div class="container-fluid">



  <div class="row">
          @if(Session::has('status'))
          <div class="panel">
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
                <h3 style="margin-bottom: 26px;"> <span class="pull-left">Add Match</span></h3>
            </div> <!-- /panel-heading -->
            <div class="panel-body m-t-0">

            <form method="POST" action="{{route('match.store')}}">

            <input type="hidden" name="_token" value="{{ csrf_token() }}">

              <div class="row">
                      <div class="form-group col-md-4">
                          <label for="redTeam">Red Team</label>
                          <input type="hidden" class="form-control" id="redTeamhidden" name="redTeamhidden"  value="">
                          <input type="text" class="form-control" id="redTeam" name="redTeam" required="">
                      </div>
                      <div class="form-group col-md-1 ">
                        <label style="    padding-top: 29px;padding-left: 30%;" >VS</label>
                        {{-- <br> --}}
                        {{-- <label >VS</label> --}}
                      </div>
                      <div class="form-group col-md-4 ">
                          <label for="blueTeam">Blue Team</label>
                          <input type="hidden" class="form-control" id="blueTeamhidden" name="blueTeamhidden" required="">
                          <input type="text" class="form-control" id="blueTeam" name="blueTeam" required="">
                      </div>

            </div>

            {{-- <div class="row">
            </div> --}}
            <div class="row">
                    <div class="form-group col-md-4">
                        <label for="start_date">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" required="">
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
  <script src="{{URL::asset('public/js/jquery-ui.min.js')}}"></script>



<script type="text/javascript">
function valueToLabel(id , collection){
  value = $(id).val();

  for (i in collection) {
    console.log(collection[i].value + " and value is " + value + "\n");

      if (collection[i].value == value) {
        $(id + 'hidden' ).val(value);
        $(id).val(collection[i].label);
        // console.log('now time' + collection[i].label);
      }
  }
}

 var redTeams = new Array();
 redTeams = @php
           echo $redTeams;
          @endphp;

$( "#redTeam" ).autocomplete({
  source: redTeams
});

$("#redTeam" ).on( "autocompleteclose", function( event, ui ) {
    valueToLabel('#redTeam', redTeams);
  });












  var blueTeams = new Array();
  blueTeams = @php
            echo $blueTeams;
           @endphp;

 $( "#blueTeam" ).autocomplete({
   source: blueTeams
 });

 $("#blueTeam" ).on( "autocompleteclose", function( event, ui ) {
     valueToLabel('#blueTeam', blueTeams);
   });


</script>

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
