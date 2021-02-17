@extends('layouts.header')
@section('title','Competitive breakin League')
{{-- @section('current-page','Dashboard') --}}
@section('main-section')
<link rel="stylesheet" type="text/css" href="{{URL::asset('public/css/jquery-ui.min.css')}}">
  <!-- //////////////////////////////////////////////////// Content-Panel div -->
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
                <h3 style="margin-bottom: 26px;"> <span class="pull-left">Teams</span>


                </h3>

                  <a href="{{route('teams.index')}}" class="btn btn-primary btn-xs pull-right">Back</a>


            </div> <!-- /panel-heading -->

            <div class="panel-body m-t-0">

            <form method="POST" action="{{route('teamsMembers.store')}}">
            {{-- {{method_field('PUT')}} --}}
            <input type="hidden" name="teamid" value="{{$teams[0]->id}}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

              <div class="row">

                <div class="form-group col-md-4">
                   <div class="col-md-6  panel panel-heading">
                      <label >Team Name</label>
                    </div>
                    <div class="col-md-6  panel panel-heading">
                    <label><strong> {{$teams[0]->name}}</strong> </label>
                    </div>
                </div>

                <div class="form-group col-md-5">
                   <div class="col-md-6 panel panel-heading">
                      <label>Team Joining Date</label>
                    </div>
                    <div class="col-md-6  panel panel-heading">
                      <label>{{$teams[0]->join_date}}</label>
                    </div>
                </div>



              </div>
              @if (Session::has('participant1'))
              <div class="row">
                <div class="form-group col-md-8">
                   <div class="col-md-5 panel panel-heading">
                      <label>First Participant</label>
                    </div>
                    <div class="col-md-7  panel panel-heading">
                      <label>{{Session('participant1')}}</label>
                    </div>
                </div>
              </div>
              @endif
              @if (Session::has('participant2'))
                <div class="row">
              <div class="form-group col-md-8">
                 <div class="col-md-5 panel panel-heading">
                    <label>Second Participant</label>
                  </div>

                  <div class="col-md-7  panel panel-heading">
                    <label>{{Session('participant2')}}</label>
                  </div>
              </div>

            </div>

              @endif
              <div class="row">
                      @if (!Session::has('participant1'))
                      <div class="form-group col-md-4">
                        <input type="hidden" id="firstParticipantselected" name="firstParticipantselected" value="">
                          <label for="firstParticipant">
                                First Participant
                          </label>
                          <input id="firstParticipant"  class="form-control" name="firstParticipant" required="" title="type &quot;a&quot;">

                      </div>
                      @endif
                      @if (!Session::has('participant2'))
                      <div id="buttondiv" class="hidden form-group col-md-4" style="padding-top: 23px;">
                        <a onclick="showSecondParticipant()" href="#" class="btn btn-primary btn-sm "> <span class="fa fa-plus"></span> </a>
                      </div>
                      <div id="secondParticipantDiv" @if (!Session::has('participant2') && Session::has('participant1'))
                          class="form-group col-md-4"
                          @else
                            class="hidden form-group col-md-4"
                        @endif >
                        <input type="hidden" id="secondParticipantselected" name="secondParticipantselected" value="">
                          <label for="secondParticipant">Second Participant</label>
                          <input id="secondParticipant"  class="form-control" name="secondParticipant" title="type &quot;a&quot;">
                      </div>
                    @endif
                </div>







                @if (!Session::has('participant2'))

              <button type="submit" class="btn btn-md bg-primary"><span>Submit</span></button>
                @endif
              </form> <!-- /form -->

             </div> <!-- /panel-body -->
            </div> <!-- /panel-->

          </div> <!-- /col -->


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
  <script src="{{URL::asset('public/js/jquery-ui.min.js')}}"></script>
{{-- secondParticipant --}}
<script>
function getaCopy(array, skip){
  var newArray = new Array();
  for (var i in array) {
    if (i == skip) {
      continue;
    }
    newArray.push(
      {
        label: array[i].label,
        value: array[i].value
      }
    );
  }
  return newArray;
}
function showSecondParticipant(){
  $("#buttondiv").addClass('hidden');
  $("#secondParticipantDiv").removeClass('hidden');
}
var firstParticipantCollection = new Array();
var orignalParticipents = new Array();
@php
foreach ($participants as $participant) {
    @endphp
    firstParticipantCollection.push({
      value: "{{$participant->id}}",
      label: "{{$participant->name}} -  {{$participant->email}}"
    });
    @php
}
@endphp

orignalParticipents = firstParticipantCollection;

$( "#firstParticipant" ).autocomplete({
	source: firstParticipantCollection
});

$( "#firstParticipant" ).on( "autocompleteclose", function( event, ui ) {
  selectedValue = $("#firstParticipant").val();
  for (var i in firstParticipantCollection) {
  if (firstParticipantCollection[i].value == selectedValue) {
    $("#firstParticipantselected").val(selectedValue);
    $('#firstParticipant').val(firstParticipantCollection[i].label);

    $( "#secondParticipant" ).autocomplete('option', 'source',
      getaCopy(orignalParticipents ,i)
    )
    }
  }
  $("#secondParticipantselected").val('');
  $("#buttondiv").removeClass('hidden');
  $("#secondParticipantDiv").addClass('hidden');



} );

</script>



<script>
var secondParticipantCollection = new Array();
@php
foreach ($participants as $participant) {
    @endphp
    secondParticipantCollection.push({
      value: "{{$participant->id}}",
      label: "{{$participant->name}} -  {{$participant->email}}"
    });
    @php
}
@endphp

$( "#secondParticipant" ).autocomplete({
	source: secondParticipantCollection
});

$( "#secondParticipant" ).on( "autocompleteclose", function( event, ui ) {
  selectedValue = $("#secondParticipant").val();
  for (var i in secondParticipantCollection) {
  if (secondParticipantCollection[i].value == selectedValue) {
    $("#secondParticipantselected").val(selectedValue);
    $('#secondParticipant').val(secondParticipantCollection[i].label);
    }
  }
} );

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
