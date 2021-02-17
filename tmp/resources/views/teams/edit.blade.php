@extends('layouts.header')
@section('title','Competitive breakin League')
@section('current-page','Dashboard')
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
          @php
            // dd($participant);
           @endphp
          <!-- //////////////////////////////////////////////////// Bar Chart -->
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel">
            <div class="panel-heading">
                <h3 style="margin-bottom: 26px;"> <span class="pull-left">Edit Teams</span></h3>
            </div> <!-- /panel-heading -->
            <div class="panel-body m-t-0">

            <form method="POST" action="{{route('teams.update', $teams[0]->id)}}">
            {{method_field('PUT')}}
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

              <div class="row">
                      <div class="form-group col-md-4">
                          <label for="name">Name</label>
                          <input type="text" class="form-control" id="name" value="{{$teams[0]->name}}" name="name" required="">
                      </div>
              </div>

              <div class="row">
                      <div class="form-group col-md-4">
                          <label for="join_date">Joining Date</label>
                          <input type="date"  class="form-control" class="datepicker" id="join_date" value="{{
                            date("Y-m-d", strtotime($teams[0]->join_date))
                          }}" name="join_date" required="">
                      </div>
              </div>
              <div class="row">
                      <div class="form-group col-md-4">
                          <label for="color">Color</label>
                          <input type="text" class="form-control" id="color" name="color" value="{{$teams[0]->color}}" required="">
                      </div>
              </div>


              <div class="row">

                      <div class="form-group col-md-4">

  @if (Session::has('participant1id'))
    <input type="hidden" id="OrignalfirstParticipantselected" name="firstParticipantselected" value="{{Session('participant1id')}}">
    <input type="hidden" id="firstParticipantselected" name="firstParticipantselected" value="{{Session('participant1id')}}">
  @else
    <input type="hidden" id="firstParticipantselected" name="firstParticipantselected" value="">
  @endif
                          <label for="firstParticipant">
                                First Participant
                          </label>

  @if (Session::has('participant1'))
    <input type="hidden" id="OrginalfirstParticipant"  class="form-control" name="firstParticipant" value="{{Session('participant1')}}" required="" title="type &quot;a&quot;">
    <input id="firstParticipant"  class="form-control" name="firstParticipant" value="{{Session('participant1')}}" required="" title="type &quot;a&quot;">
  @else
    <input id="firstParticipant"  class="form-control" name="firstParticipant" value required="" title="type &quot;a&quot;">
  @endif

                      </div>
                </div>
                <div class="row">
                      <div id="secondParticipantDiv"  class="form-group col-md-4">

  @if (Session::has('participant2id'))
    <input type="hidden" id="OrignalsecondParticipantselected" name="secondParticipantselected" value="{{Session('participant2id')}}" >

        <input type="hidden" id="secondParticipantselected" name="secondParticipantselected" value="{{Session('participant2id')}}" >
  @else
        <input type="hidden" id="secondParticipantselected" name="secondParticipantselected" value="">
  }
  @endif
                          <label for="secondParticipant">Second Participant</label>

@if (Session::has('participant2'))
  <input type="hidden" id="OrignalsecondParticipant"  class="form-control" name="secondParticipant" value="{{Session('participant2')}}" title="type &quot;a&quot;">

  <input id="secondParticipant"  class="form-control" name="secondParticipant" value="{{Session('participant2')}}" title="type &quot;a&quot;">
@else
  <input id="secondParticipant"  class="form-control" name="secondParticipant" title="type &quot;a&quot;">
@endif

                      </div>

                </div>


              <button type="submit" class="btn btn-md bg-primary"><span>Submit</span></button>

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
    orginalInputBK1 = $("#OrginalfirstParticipant").val();
    orignalValueBK1 = $("#OrignalfirstParticipantselected").val();

    secondParticipantselectedValue = $("#secondParticipantselected").val();
    selectedValue = $("#firstParticipant").val();
    if (selectedValue == secondParticipantselectedValue) {
      alert("This Participant is already Selected as Second Participant \nPlease Change Second Participant to be set him/Her as First Participant");
      $("#firstParticipant").val(orginalInputBK1); // empty 2nd input etc..
      $("#firstParticipantselected").val(orignalValueBK1);

      return false;
    }


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
    // $("#secondParticipantDiv").addClass('hidden');



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
    orginalInputBK = $("#OrignalsecondParticipant").val();
    orignalValueBK = $("#OrignalsecondParticipantselected").val();

    firstParticipantselectedValue = $("#firstParticipantselected").val();
    selectedValue = $("#secondParticipant").val();
    if (selectedValue == firstParticipantselectedValue) {
      alert("This Participant is already Selected as First Participant \nPlease Change First Player to be set him/Her as Second Participant");
      $("#secondParticipant").val(orginalInputBK); // empty 2nd input etc..
      $("#secondParticipantselected").val(orignalValueBK);

      return false;
    }
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
