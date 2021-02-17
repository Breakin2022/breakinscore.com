
@extends('layouts.header')
@section('title','Competitive breakin League')
@section('current-page','Dashboard')
@section('main-section')
  <div id="content-panel">
  <div class="container-fluid">

    <div class="row">

    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="panel">
            <div class="panel-heading">
                <h3 style="margin-bottom: 26px;"> <span class="pull-left">{{$arryColletion['match']->firstTeamName}}</span></h3>
            </div> <!-- /panel-heading -->
            <div class="panel-body m-t-0">
            @foreach($arryColletion['teamOneParticipants'] as $item)
            <h3>{{$item->name}}</h3>
                <form method="POST" id="insertScore{{$item->id}}" class="insertScoreForm">
                    <input type="hidden" name="judgeId" value="{{$arryColletion['judgeId']}}">
                    <input type="hidden" name="matchId" value="{{$arryColletion['match']->id}}">
                    <input type="hidden" name="competitionId" value="{{$arryColletion['competition']->id}}">
                    <input type="hidden" name="participantId" value="{{$item->id}}">
                    <input type="hidden" name="isLast" value="0">

                    <div class="row">
                        @foreach($arryColletion['criterias'] as $cri)
                            <div class="form-group col-md-4">
                                <label for="name">{{$cri->title}}</label>
                                <input type="number" class="form-control" class="score" scoreId="{{$cri->id}}" id="score{{$cri->id}}" name="score[]" required>
                            </div>
                        @endforeach
                    </div>
                    <button type="submit" class="btn btn-md bg-purple"><span>Submit</span></button>
                    </form> <!-- /form -->
            @endforeach

            </div>
        </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="panel">
            <div class="panel-heading">
                <h3 style="margin-bottom: 26px;"> <span class="pull-left">{{$arryColletion['match']->secondTeamName}}</span></h3>
            </div> <!-- /panel-heading -->
            <div class="panel-body m-t-0">
            @foreach($arryColletion['teamTwoParticipants'] as $item)
                <h3>{{$item->name}}</h3>
                <form method="POST" id="insertScore{{$item->id}}" class="insertScoreForm">
                    <input type="hidden" name="judgeId" value="{{$arryColletion['judgeId']}}">
                    <input type="hidden" name="matchId" value="{{$arryColletion['match']->id}}">
                    <input type="hidden" name="competitionId" value="{{$arryColletion['competition']->id}}">
                    <input type="hidden" name="participantId" value="{{$item->id}}">
                    <input type="hidden" name="isLast" value="0">

                    <div class="row">
                        @foreach($arryColletion['criterias'] as $cri)
                            <div class="form-group col-md-4">
                                <label for="name">{{$cri->title}}</label>
                                <input type="number" class="form-control" class="score" scoreId="{{$cri->id}}" id="score{{$cri->id}}" name="score[]" required>
                            </div>
                        @endforeach
                    </div>
                    <button type="submit" class="btn btn-md bg-purple"><span>Submit</span></button>
                    </form> <!-- /form -->
            @endforeach

            </div>
        </div>
        </div>
        
    </div>


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
$( '.insertScoreForm' ).on( 'submit', function(e) {
e.preventDefault();
var FormId = "#" + $(this).attr("id");

var judgeId = $(FormId).find(' input[name=judgeId]').val();
var matchId = $(FormId).find(' input[name=matchId]').val();
var participantId = $(FormId).find(' input[name=participantId]').val();
var competitionId = $(FormId).find(' input[name=competitionId]').val();
var isLast = $(FormId).find(' input[name=isLast]').val();

var scoreIds = $(FormId + " input[name='score[]']").map(function(){return $(this).attr("scoreId");}).get();
var score = $(FormId + " input[name='score[]']").map(function(){return $(this).val();}).get();

// var scorelength = scoreIds.length;
// var i = 0;
// var score = new Array();
// for(i = 0; i < scorelength; i++)
// {
//     score = [
//         'score' => scoreMarks[i],
//         'id' => scoreIds[i]
//     ]
// }
console.log(participantId);
$.ajax({
        type: "POST",
        url: "{{Route('api-insertScore')}}",
        data: { judgeId:judgeId, matchId:matchId, participantId:participantId, isLast:isLast, score:score, competitionId:competitionId }, 
        success: function( msg ) {
            alert( msg );11
        }
    });
});

</script>
  </body>

  </html>
@endsection
