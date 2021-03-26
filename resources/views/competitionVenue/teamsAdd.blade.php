@extends('layouts.header')
@section('title','Competitive breakin League')
@section('current-page','Dashboard')
@section('main-section')
<style media="screen">
  .nmp{
    margin: 0;
    padding: 0;
  }
  
  @if ($round > 1)
    .actionCls{
      display: none;
    }
  @endif

  @if($round >= 2)
  #showBtnSchudle{
    display: none;
  }

  @elseif ($round <  2)
  #nextRoundBtn{
    display: none;
  }
  @endif

  @if ($round >= 2)
  #content-panel .panel{
    padding-bottom: 20px !important;
  }
  @endif

  .select2-container{
    width: 160px !important;
  }
</style>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
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
          <div class="col-md-12">
            @if (Session::has('teamsWithSameScore'))
              <div class="panel" style="padding:10px;background:#ebccd1;color:#000;">
                <div class="panel-danger" style="color:#000;">
                  These teams have same score ({{Session::get('teamsWithSameScore')->score}}). Please fix this and try again.
                  <br>
                  {{ Session::get('teamsWithSameScore')->teamsNeedWin }} Teams needs to win.
                  @foreach (Session::get('teamsWithSameScore') as $team)
                    <br> {{$team->name}}
                  @endforeach
                </div>

              </div>
            @endif
          </div>
          <div class="col-md-12">
            @if (Session::has('teamsWithSameScoreE'))
              <div class="panel" style="padding:10px;background:#ebccd1;color:#000;">
                <div class="panel-danger" style="color:#000;">
                  These teams have same score. Please fix this and try again.
                  @foreach (Session::get('teamsWithSameScoreE') as $team)
                    <br> {{$team->name}}
                  @endforeach
                </div>

              </div>
            @endif
          </div>
          <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
            <div class="panel">
            <div class="panel-heading">
                <h3 style="margin-bottom: 26px;"> <span class="pull-left">{{$competitionName}}</span>
                   <span class="pull-right">Current Round <span style="color:red;">
                      @if ($round == 0)

                        none
                      @else
                        {{$round}}
                      @endif
                        </span></span>
                </h3>




            </div>
            <div class="panel-body m-t-0" >



              <div class="row panel-body">
                <table id="customer-list" class="table table-striped" >
                <thead>
                    <tr>
                        <th>#</th>
                        <th>First Team</th>
                        <th>Second Team</th>
                        <th>Round</th>
                        <th class="hidden"></th>
                        <th style="Display:none;"></th>
                        <th >Report</th>
                        <th class="actionCls">action</th>
                    </tr>
                </thead>
                    <tbody>
                         @php

                         $i = 1;
                         @endphp
                         @foreach($competitionList as $competition)
                        <tr id="del{{$competition->id}}">
                            <td>{{$i++}}</td>
                            <td>
                              @php
                                $firstCombinedId = $competition->id.'-'.$competition->firstTeamId;
                              @endphp
                              <div class="pull-left teamDiv{{$firstCombinedId}}">
                                {{$competition->firstTeam}}
                              </div>

                              <div class="hidden pull-left select2Div{{$firstCombinedId}}">
                                <select name="teams" class="allTeamSelect form-control" id="select2{{$firstCombinedId}}">
                                </select>

                                &nbsp
                                <a class="btn btn-delete btn-xs bg-red hidden-xs hidden-sm" href="javascript:void(0)"
                                onclick="submitForm('{{$firstCombinedId}}',{{$competition->firstTeamId}},{{$competition->id}}) "><i class="fa fa-floppy-o" aria-hidden="true"></i></a>
                              </div>
                              <div class="btn-group pull-right editBtn{{$firstCombinedId}}">
                                  <a class="btn btn-delete btn-xs bg-orange hidden-xs hidden-sm" href="javascript:void(0)" style="margin-top: 3px;"
                                  onclick="showSelect2('{{$firstCombinedId}}')"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                              </div>
                            </td>
                            <td>
                              @php
                                $secondCombinedId = $competition->id.'-'.$competition->secondTeamId;
                              @endphp
                              <div class="pull-left teamDiv{{$secondCombinedId}}">
                                {{$competition->secondTeam}}
                              </div>

                              <div class="hidden pull-left select2Div{{$secondCombinedId}}">
                                <select name="teams" class="allTeamSelect" id="select2{{$secondCombinedId}}">
                                </select>

                                &nbsp
                                <a class="btn btn-delete btn-xs bg-red hidden-xs hidden-sm" href="javascript:void(0)"
                                onclick="submitForm('{{$secondCombinedId}}',{{$competition->secondTeamId}},{{$competition->id}})"><i class="fa fa-floppy-o" aria-hidden="true"></i></a>
                              </div>
                              <div class="btn-group pull-right editBtn{{$secondCombinedId}}">
                                  <a class="btn btn-delete btn-xs bg-orange hidden-xs hidden-sm" href="javascript:void(0)" style="margin-top: 3px;"
                                  onclick="showSelect2('{{$secondCombinedId}}')"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                              </div>
                            </td>
                            <td >
                              {{$competition->roundNo}}
                             </td>

                            <td class="hidden"></td>
                            <td style="Display:none;"></td>
                            <td>

                              <a target="_blank" href="{{route('report', $competition->id)}}" >
                              <i class="fa fa-bar-chart-o"></i>

                              </a>

                            </td>

                            <td class="actionCls">
                              <div class="btn-group">

                                  <a class="btn btn-delete btn-xs bg-red hidden-xs hidden-sm" href="javascript:void(0)" onclick="delcompetitionVenue({{$competition->id}})"><i class="fa fa-times" aria-hidden="true"></i></a>
                              </div>
                            </td>
                        </tr>
                      @endforeach

                        </tbody>
                        </table>
              </div>







             </div>

            </div>

          </div>
          <div class="col-md-3 ">
                .<div class="panel" style="margin-top: 6px;">
                  <div class="panel-heading " style="padding-bottom: 10px;">
                      <span class="pull-left">Criteria List</span>
                      <span class="pull-right">
                        <a href="javascript:void(0)" style="margin-bottom: 12px;" onclick="
                        $('.criteriaListform').removeClass('hidden');
                        " class="btn btn-sm btn-primary">Add Criteria</a>
                      </span>
                  </div>
                  <div class="panel-body">


                    <form method="POST" action="{{route('match.store')}}" class=""  style="    padding-bottom: 20px;">

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="competitionid" value="{{$cid}}">
                    <input type="hidden" name="round" value="{{$round}}">
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
                                      <a class="btn btn-delete btn-xs bg-red hidden-xs hidden-sm" href="javascript:void(0)" onclick="delCriteria({{$competition->id}})"><i class="fa fa-times" aria-hidden="true"></i></a>
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

          <div class="col-md-9" style="margin-bottom:100px;">

            <div class="panel panel-body">
              <div class="row panel-heading">
                <h3>create schedule</h3>
              </div>
              <div id="createSchudleBtn" class="row panel-body">
                <div class="col-md-2" style="padding:0;margin:0">
                  <span class=" "><a  class="btn btn-primary btn-sm " id="showBtnSchudle" onclick="showAddMatchForm()" >
                    @if ($round < 1)
                      Create Schedule
                    @else
                      Create Next Round Schedule
                    @endif

                  </a></span>
                </div>

              </div>
              <button type="submit" name="nextRoundBtn"
              {{-- onclick="var e=this;setTimeout(function(){e.disabled=true;},0);return true;" --}}

               value="yes" id="nextRoundBtn" class="btn btn-md bg-purple"><span>Next Round</span></button>

              <div id="addMatchForm" class="row panel-body hidden">
                <div class="row panel-body">
                  @if ($round < 1  )
                    <div class="col-md-2" style="padding:0;margin:0">
                      <a class="btn btn-success btn-sm" onclick="selectAll()">Select All Teams</a>
                    </div>
                  @else

                      <div id="topTeamsChoiceDiv" class="form-group col-md-8 nmp">
                          <label for="topTeamChoice">Select Top Teams</label>
                          <select id="topTeamChoice" style="width: 100%;" class="form-control"  name="topTeamsChoice" >
                            <option selected disabled>Select</option>
                            @foreach ($topTeams as $key => $j)

                              <option value="{{$key}}">{{$key}}</option>
                            @endforeach
                          </select>

                      </div>

                        <div class="form-group col-md-12 hidden" style="margin-top:22px;margin-left:0;padding-left:0">
                          <a class="btn btn-primary btn-sm" onclick="choicemanully()">Choice Manually</a>
                        </div>

                  @endif

                </div>
                <form method="POST" action="{{route('match.store')}}"    style="    padding-bottom: 20px;">

               <input type="hidden" name="_token" value="{{ csrf_token() }}">
               <input type="hidden" name="competitionid" value="{{$cid}}">
                 <div class="row " id="selectBoxForTeams" @if($round > 0) style="display:none;" @endif>

                         <div class="form-group col-md-8 ">
                             <label for="teams">Click to add teams </label>
                             <select   class="form-control" style="width:100%" id="teams" name="teams[]" multiple>
                               @foreach ($allTeams as $team)
                                 <option value="{{$team->id}}">{{$team->name}}</option>
                               @endforeach
                             </select>

                         </div>
                 </div>


               <button type="submit" class="btn btn-md bg-purple disableMe"><span>Submit</span></button>

                 </form>

                  <div @if($round != 1) style="display:none;" @endif>
                  <form method="POST" action="{{route('match.store')}}"    style="    padding-bottom: 20px;" >

                      <input type="hidden" name="_token" value="{{ csrf_token() }}">
                      <input type="hidden" name="competitionid" value="{{$cid}}">
                      <input type="hidden" name="newTeams" value=true>

                      <div class="row " id="selectBoxForTeams">

                          <div class="form-group col-md-8 ">
                              <label for="teams">Click to add teams</label>
                              <select   class="form-control" style="width:100%" id="tteams" name="tteams[]" multiple>
                                  @if($filteredTeams)
                                  @foreach ($filteredTeams as $team)
                                      <option value="{{$team->id}}">{{$team->name}}</option>
                                  @endforeach
                                      @endif
                              </select>

                          </div>
                      </div>


                      <button type="submit" class="btn btn-md bg-purple disableMe"><span>Submit</span></button>

                  </form>
                  </div>

              </div>



            </div>
          </div>
  </div>




  </div>
  </div>

  <script  type="text/javascript" src="{{URL::asset('public/js/bootstrap.min.js')}}"></script>
  <script  type="text/javascript" src="{{URL::asset('public/js/menu/metisMenu.min.js')}}"></script>
  <script type="text/javascript" src="{{URL::asset('public/js/menu/nanoscroller.js')}}"></script>
  <script type="text/javascript" src="{{URL::asset('public/js/moment.js')}}"></script>
  <script type="text/javascript" src="{{URL::asset('public/js/daterangepicker/daterangepicker.js')}}"></script>
  <script type="text/javascript" src="{{URL::asset('public/js/countTo/jquery.countTo.js')}}"></script>
  <script  type="text/javascript" src="{{URL::asset('public/js/morris-js/raphael.min.js')}}"></script>
  <script  type="text/javascript" src="{{URL::asset('public/js/morris-js/morris.min.js')}}"></script>
  <script type="text/javascript" src="{{URL::asset('public/js/chart-js/Chart.js')}}"></script>
  <script type="text/javascript" src="{{URL::asset('public/js/flot-js/excanvas.min.js')}}"></script>
  <script type="text/javascript" src="{{URL::asset('public/js/flot-js/jquery.flot.js')}}"></script>
  <script type="text/javascript" src="{{URL::asset('public/js/flot-js/jquery.flot.resize.js')}}"></script>
  <script type="text/javascript" src="{{URL::asset('public/js/flot-js/jquery.flot.time.js')}}"></script>
  <script type="text/javascript" src="{{URL::asset('public/js/datatables/datatables.min.js')}}"></script>
  <script type="text/javascript" src="{{URL::asset('public/js/vmap/jquery.vmap.js')}}"></script>
  <script type="text/javascript" src="{{URL::asset('public/js/vmap/maps/jquery.vmap.usa.js')}}"></script>
  <script type="text/javascript" src="{{URL::asset('public/js/jQuery.style.switcher.min.js')}}"></script>
  <script type="text/javascript" src="{{URL::asset('public/js/jquery-functions.js')}}"></script>
  <script src="{{URL::asset('public/js/jquery-ui.min.js')}}"></script>
<script type="text/javascript">

$("button[type=submit]").click(function(event) {
  setTimeout(function(){
    $("button[type=submit]").attr('disabled',"disabled");
  },0);
});
$("input[type=submit]").click(function(event) {
  setTimeout(function(){
    $("input[type=submit]").attr('disabled',"disabled");
  },0);
});

    var allTeamsCollection = JSON.parse('{!!$allTeamsCollection!!}');
    $(".allTeamSelect").select2({
      data: allTeamsCollection
    })
</script>
<script type="text/javascript">
  $("#topTeamChoice").change(function( ){

    var value = $(this).val();
    // console.log(value);
    if (value == "Top 16") {
      console.log('s');
      selectTop32();
    }

    if (value == "Top 8") {
      selectTop16();
    }

    if (value == "Top 4") {
      selectTop8();
    }

    if (value == "Top 2") {
      selectTop4();
    }
    if (value == "Finals Match") {
      selectTop2();
    }


  });
  function nextRound( ){
    if (confirm('are you sure ?')) {
      $.ajax({
        url: "{{route('chnangeCompetitionRound',$cid)}}"  ,
        type: 'POST',
        data: {

          _token:     '{{ csrf_token() }}'
        }
      })
      .done(function(data) {
        location.reload();
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
 function selectAll(){
   var teamsss = Array(<?php echo $teamsId ?>
    );
   $("#teams").val(teamsss).trigger('change');
 }
</script>

<script type="text/javascript">
$(document).ready(function() {
    $('#teams').select2();
});

$(document).ready(function() {
    $('#tteams').select2();
});
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

  <script>
$(document).ready(function() {
    $('#customer-list').DataTable({
        "pageLength": "25",
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
    $('#mycstmid').append('<tr id="delCritera'+ data +'"><td>'+ $( "#criterias option:selected" ).text() +'</td><td><div class="btn-group"><a class="btn btn-delete btn-xs bg-red" href="javascript:void(0)" onclick="delCriteria(' + data +')"><i class="fa fa-times" aria-hidden="true"></i></a></div></td></tr>');
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
    $('#addMatchForm').toggleClass('hidden');
  }

 </script>
 <script>


function selectTop32(){
  var t  = Array(<?php if(isset($topTeams['Top 16'])){ echo $topTeams['Top 16']; } ?> );
  $("#teams").val(t).trigger('change');
}
function selectTop16(){
  var t  = Array(<?php if(isset($topTeams['Top 8'])){ echo $topTeams['Top 8']; } ?> );
  $("#teams").val(t).trigger('change');
}
function selectTop8(){
  var t  = Array(<?php if(isset($topTeams['Top 4'])){ echo $topTeams['Top 4']; } ?> );
  $("#teams").val(t).trigger('change');
}
function selectTop4(){
  var t  = Array(<?php if(isset($topTeams['Top 2'])){ echo $topTeams['Top 2']; } ?> );
  $("#teams").val(t).trigger('change');
}
function selectTop2(){
  var t  = Array(<?php if(isset($topTeams['Finals Match'])){ echo $topTeams['Finals Match']; } ?> );
  $("#teams").val(t).trigger('change');
}

function choicemanully(){
  $("#topTeamsChoiceDiv").toggle('hidden');
  $("#selectBoxForTeams").toggle('hidden');
}

 </script>
 <script src="{{URL::asset('public\js\teamsAdd.js')}}" charset="utf-8"></script>
  </body>

  </html>

@endsection
