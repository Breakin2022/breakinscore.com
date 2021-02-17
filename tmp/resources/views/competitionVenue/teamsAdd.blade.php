@extends('layouts.header')
@section('title','Competitive breakin League')
<!-- @section('current-page','Dashboard') -->
@section('main-section')
  <link rel="stylesheet" type="text/css" href="{{URL::asset('public/css/jquery-ui.min.css')}}">

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
          <div id="alertbox" class="col-md-8 col-xs-12">
              <p>{{Session::get('status')}}</p>
          </div>
        </div>
      </div>
          @endif
          <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
            <div class="panel">
            <div class="panel-heading">
                <h3 style="margin-bottom: 26px;"> <span class="pull-left">{{$competitionName}}</span>
                  <span class="pull-right"><a  class="btn btn-primary btn-sm" onclick="showAddMatchForm()" href="#">Add Match</a></span> </h3>

            </div> <!-- /panel-heading -->
            <div class="panel-body m-t-0" >

            <form method="POST" action="{{route('match.store')}}" class=" hidden" id="addMatchForm" style="    padding-bottom: 20px;">

            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="competitionid" value="{{$cid}}">
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
                      <div class="form-group col-md-4">
                          <label for="start_time">start Time</label>

                          <input type="time" class="form-control" id="start_time" name="start_time" required="">
                      </div>
            </div> --}}

            <button type="submit" class="btn btn-md bg-purple"><span>Submit</span></button>

              </form> <!-- /form -->

              <div class="row panel-body">
                <table id="customer-list" class="table table-striped" >
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Red Team Name</th>
                        <th>Blue Team Name</th>
                        <th class="hidden"></th>
                        <th class="hidden"></th>
                        {{-- <th>phone</th> --}}
                        <th style="Display:none;"></th>
                        <th style="Display:none;"></th>
                        <th>action</th>
                    </tr>
                </thead>
                    <tbody>
                         @php

                         $i = 1;
                         @endphp
                         @foreach($competitionList as $competition)
                           @php
                            //  dd($competition);
                           @endphp
                        <tr id="del{{$competition->id}}">
                            <td>{{$i++}}</td>
                            <td>{{$competition->redteam}}</td>
                            <td>{{$competition->blueteam}}</td>

                            <td class="hidden">


                             </td>

                            <td class="hidden"></td>
                            <td style="Display:none;"></td>
                            <td style="Display:none;"></td>

                            <td>
                              <div class="btn-group">
                                  {{-- <a class="btn btn-xs btn-primary" href="{{route('competitionVenue.show', $competitionVenue->id)}}"><i class="fa fa-eye" aria-hidden="true"></i></a> --}}
                                  {{-- <a class="btn btn-xs btn-edit bg-purple hidden-xs hidden-sm" href="{{route('competitionVenue.edit', $competitionVenue->id)}}" ><i class="fa fa-pencil" aria-hidden="true"></i></a> --}}
                                  <a class="btn btn-delete btn-xs bg-red hidden-xs hidden-sm" href="#" onclick="delcompetitionVenue({{$competition->id}})"><i class="fa fa-times" aria-hidden="true"></i></a>
                              </div>
                            </td>
                        </tr>
                      @endforeach

                        </tbody>
                        </table>
              </div>







             </div> <!-- /panel-body -->

            </div> <!-- /panel-->

          </div> <!-- /col -->
          <div class="col-md-3 ">
                .<div class="panel" style="margin-top: 6px;">
                  <div class="panel-heading " style="padding-bottom: 10px;">
                      <span class="pull-left">Criteria List</span>
                      <span class="pull-right">
                        <a href="#" style="margin-bottom: 12px;" onclick="
                        $('.criteriaListform').removeClass('hidden');
                        " class="btn btn-sm btn-primary">Add Criteria</a>
                      </span>
                  </div>
                  <div class="panel-body">


                    <form method="POST" action="" class="" id="addMatchForm" style="    padding-bottom: 20px;">

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="competitionid" value="{{$cid}}">

                    <div class="row criteriaListform hidden">
                      <div class="form-group col-md-12">
                        <label>All Criterias</label>
                        <select id="criterias" class="form-control">
                          @foreach ($criteriaList as $criteria)
                              <option value="{{$criteria->id}}">{{$criteria->title}}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="row criteriaListform hidden col-md-12">
                      <button type="button" style="margin-bottom: 20px;" onclick="saveCompetitionCriteria()" class="btn btn-md bg-purple"><span>submit</span></button>
                    </div>


                    <table id="criteria-list" class="table table-striped" >
                    <thead>
                        <tr>
                            <th class="hidden">#</th>
                            <th>Title</th>
                            <th class="hidden"></th>
                            <th class="hidden"></th>
                            <th class="hidden"></th>
                            {{-- <th>phone</th> --}}
                            <th style="Display:none;"></th>
                            <th style="Display:none;"></th>
                            <th >Action</th>
                        </tr>
                    </thead>
                        <tbody id="mycstmid">
                             @php

                             $i = 1;
                             @endphp
                             @foreach($criteriasName as $competition)
                            <tr id="delCritera{{$competition->id}}">
                                <td class="hidden"></td>
                                <td>{{$competition->title}}</td>
                                <td class="hidden"></td>

                                <td class="hidden"></td>
                                <td class="hidden"></td>
                                <td style="Display:none;"></td>
                                <td style="Display:none;"></td>

                                <td>
                                  <div class="btn-group">
                                      <a class="btn btn-delete btn-xs bg-red hidden-xs hidden-sm" href="#" onclick="delCriteria({{$competition->id}})"><i class="fa fa-times" aria-hidden="true"></i></a>
                                  </div>
                                </td>
                            </tr>
                          @endforeach

                            </tbody>
                            </table>
                      <p class="alert alert-warning" style="color: #54460b">Note : You must add minimum 3 and maximum 6 criteria in the List in order to display event in the IPad</p>
                  </div>
                </div>


          </div>

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
function delcompetitionVenue(id){
  if (confirm("Are you sure you want to remove this Match ?")) {
    $.ajax({
      url: "{{route('match.index')}}/"+ id,
      type: 'POST',
      data: {
        _method: 'delete',
        _token:     '{{ csrf_token() }}'
      }
    })
    .done(function(data) {
      $("#del"+data).remove();
      // console.log(data);
    })
    .fail(function() {
      alert("error");
    })
    .always(function() {
      console.log("complete");
    });

  }

}

function delCriteria(id){
  if (confirm("Are you sure you want to remove this Critera ?")) {
    $.ajax({
      url: "{{route('competitionCriteria.index')}}/"+ id,
      type: 'POST',
      data: {
        _method: 'delete',
        _token:     '{{ csrf_token() }}'
      }
    })
    .done(function(data) {
      $("#delCritera"+data).remove();
      console.log('yes');
      console.log("#delCritera"+data);
      // console.log(data);
    })
    .fail(function() {
      alert("error");
    })
    .always(function() {
      console.log("complete");
    });

  }

}
</script>
<script type="text/javascript">
  function currentCriteraCount(){
    count = $("tr[id^='delCritera']").size();
    return count;
  }
</script>

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

<script>

function saveCompetitionCriteria(){
  competitionId = {{$cid}};

  selectedCriteria = $('#criterias option:selected').val()
  // alert(currentCriteraCount());
  if (currentCriteraCount() >= 6) {
    alert('Maximum 6 criteras are allowed');
    return;
  }
  $.ajax({
    url: "{{route('competitionCriteria.store')}}",
    type: 'POST',
    // dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
    data: {
      competitionid : competitionId,
      criteriaid    : selectedCriteria,
      _token:     '{{ csrf_token() }}'
    }
  })
  .done(function(data) {
    if (data == "error") {
      alert('Criteria already Exists in this Competition');
      return;
    }
    // delCritera226
    $('#mycstmid').append('<tr id="delCritera'+ data +'"><td>'+ $( "#criterias option:selected" ).text() +'</td><td><div class="btn-group"><a class="btn btn-delete btn-xs bg-red" href="#" onclick="delCriteria(' + data +')"><i class="fa fa-times" aria-hidden="true"></i></a></div></td></tr>');
  })
  .fail(function() {
    alert("error");
  })
  .always(function() {
    console.log("complete");
  });

}
  function showAddMatchForm()
  {
    $('#addMatchForm').removeClass('hidden');
  }
  function updateTime(id){
    // console.log(id);
    // return ;
    newTime = $("#input"+ id).val();
    console.log(newTime);
    $.ajax({

      url: "{{URL::to('match')}}/"+ id,
      type: 'POST',
      // dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
      data: {
        _method: 'PUT',
        match_id : id,
        start_time : newTime,
        competitionId: {{$cid}},
        _token:     '{{ csrf_token() }}'
      }
    })
    .done(function(data) {
      // $("#del"+data).remove();
      // console.log(data);
      $('#time'+ id).html(newTime);

      $('#input'+ id).addClass('hidden');
      $('#saveButton'+ id).addClass('hidden');
      $('#editButton' + id).removeClass('hidden');
      $('#time' + id).removeClass('hidden');

    })
    .fail(function() {
      alert("error");
    })
    .always(function() {
      console.log("complete");
    });

  }
</script>

  </body>

  </html>
@endsection
