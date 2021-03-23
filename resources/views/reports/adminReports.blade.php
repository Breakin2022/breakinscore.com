@extends('layouts.header')
@section('title','Competitive breakin League')
@section('current-page','Options')
@section('main-section')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.9.0/css/bootstrap-slider.css">

<div id="content-panel">
<input type="hidden" name="_token" value="{{ csrf_token() }}" />
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
  </div>

  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="panel">
          <div class="panel-heading">
            <h3><span class="pull-left">Reports</span>
            </h3>
          </div>
          <div class="panel-body m-t-0">
            <div class="row panel-body">
              <div class="col-md-4" id="competitionVenues_list">
                <div class="form-group">
                  <label for="competitionVenues">Competition</label>
                  <select class="form-control" id="competitionVenues">
                    <option value="">Select Competition</option>

                    @foreach($competitionVenues as $competitionVenue)
                      <option value="{{ trim($competitionVenue->id) }}">{{ trim($competitionVenue->title) }}</option>
                    @endforeach

                  </select>
                </div>
              </div>
              <div class="col-md-4" id="teams_list">
                <!-- Ajax will get data here -->
              </div>
              <div class="col-md-4" id="competitionVenue">
              <!-- Get Setelcted Competition venue Button -->
              </div>
            </div>
          </div>
        </div>
      </div> <!-- End-col -->
    </div>
  </div><!-- container-fluid end -->

  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="panel">
          <div class="panel-heading">
            <h3><span class="pull-left">Scores</span>
            </h3>
          </div>
          <div class="panel-body m-t-0">
            <div class="panel-body"  id="scores_data">
              <!-- Ajax will fetch match report -->
            </div>
          </div>
        </div>
      </div> <!-- End-col -->
    </div>
  </div><!-- container-fluid end -->
</div>

<!-- Modal -->
<div class="modal fade" id="scoreModal" tabindex="-1" role="dialog" aria-labelledby="scoreModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" style="width: 80%; float: left;">Score</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div id="response"></div>
        <table class="table table-hover table-striped table-sm" scoreid="157">
          <tbody>
            <tr>
              <th>Judge</th>
              <td id="judge-name"></td>
            </tr>
            <tr>
              <th>Criteria</th>
              <td id="criteria-name"></td>
            </tr>
            <tr>
              <th>Score</th>
              <td>
                <div class="form-group row">
                  <label id="crrScore" class="col-sm-2 col-form-label"></label>
                  <div class="col-sm-10">
                    <input 
                      id="score" 
                      data-slider-id='scoreSlider'
                      type="text" 
                      data-slider-min="-10" 
                      data-slider-max="10" 
                      data-slider-step="1"
                      data-slider-value="0" />
                      <input type="hidden" id="criteriaScore" value="" />
                  </div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" id="update-score" class="btn btn-primary btn-xs bg-purple">Update Score</button>
      </div>
    </div>
  </div>
</div>

<script  type="text/javascript" src="{{URL::asset('public/js/bootstrap.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.9.0/bootstrap-slider.js"></script>
<script>
$(document).ready(function() {
  /**
  * To get Matches list
  */
  $(document).on('change', 'select#competitionVenues', function(){
    var competition_id =  $(this).find(":selected").val();
    if(!competition_id){
      return false;
    }
    var competition_name =  $(this).find(":selected").text();
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('input[name="_token"]').val()
      }
    });
    $.ajax({
      url: "{{ url('/reports-teams') }}",
      method: 'post',
      data: {
        competition_id : competition_id
      },
      success: function (result) {
        obj = JSON.parse(result);
        /* obj will have 'status', 'data' */
        if(obj.status == 'error'){
          alert('This '+competition_name+' has no matches data');
        }else{
          var html = '<div class="form-group"> <label for="teams">Teams</label> <select class="form-control" id="match_teams"><option value="">Select Match</option>';
          $.each(obj.data, function(key, value){
            html += '<option value="'+value.id+'">'+value.firstTeam+' vs. '+value.secondTeam+' @rnd:'+value.roundNo+'</option>';
          });
          html += '</select> </div>'; 
          $('#teams_list').html(html);  
        }
        $('#competitionVenue').html('<div class="form-group"> <label>View Competition:</label> <a href="{{ url('/') }}/competitionVenue/'+competition_id+'" target="_blank" class="form-control btn btn-sm bg-purple w-100"><span>'+competition_name+' <i class="fa fa-external-link" aria-hidden="true"></i></span></a></div>');
      }
    });
  });

  /**
  * To get selected Macth Scores
   */
  $(document).on('change', 'select#match_teams', function(){
    var match_id =  $(this).find(":selected").val();
    if(!match_id){
      return false;
    }
    var match_name =  $(this).find(":selected").text();
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('input[name="_token"]').val()
      }
    });
    $.ajax({
      url: "{{ url('/reports-match') }}",
      method: 'post',
      data: {
        match_id : match_id
      },
      success: function (result) {
        obj = JSON.parse(result);
        /* obj will have 'status', 'data' */
        if(obj.status == 'error'){
          alert('This '+match_name+' has no data');
        }else{
          $('#scores_data').html(obj.data);  
        }
      }
    });
  });

  $('#scoreModal #score').slider({
      formatter: function (value) {
        $('#scoreModal #crrScore').text(value);
        return 'Current value: ' + value;
      }
  });

  /**
  * get edit Score
  */
  jQuery(document).on('click', '.edit-score, #update-score', function(){
    var crrObject = $(this);
    var action    = jQuery(crrObject).prop('id');
    if(action=='update-score'){
      var criteriaScoreID = parseInt( $('#scoreModal #criteriaScore').val() );
      var score           = parseInt( $('#scoreModal input#score').val() );
      if(criteriaScoreID && score){
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
          }
        });
        $.ajax({
          url   : "{{ url('/reports-update-score') }}",
          method: 'post',
          data  : {
            id   : criteriaScoreID,
            score: score
          },
          success: function (result) {
            obj = JSON.parse(result);
            /* obj will have 'status', 'data' */
            if(obj.status == 'error'){
              $('#scoreModal #response').html('<div class="alert alert-danger alert-dismissible show" role="alert"> <strong>Error!</strong> '+obj.data+' <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button> </div>'); 
            }else{
              $('#scoreModal #response').html('<div class="alert alert-success alert-dismissible show" role="alert"> <strong>Success!</strong> '+obj.data+' <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button> </div>'); 
              $('#scoresRow_'+criteriaScoreID+' a').text(score);
              $('#scoresRow_'+criteriaScoreID+' a').addClass('bg-purple');
            }
          }
        }); /**Ajax Score Update */
      }else{
        alert('criteriaScoreID or score not set!');
      }
    }else{
      var id       = $(crrObject).parent('td').prop('id').match(/\d+/);;
      var crrScore = parseInt($(crrObject).text());
      var Judge    = $(crrObject).parents('tr').find('th').text();
      var Criteria = $(crrObject).parents('tbody').find('th[criteriaid="criteria_'+$(crrObject).parent('td').attr('criteriaid')+'"]').text();
      $('#scoreModal #judge-name').text(Judge);
      $('#scoreModal #criteria-name').text(Criteria);
      $('#scoreModal #crrScore').text(crrScore);
      $('#scoreModal #score').slider('setValue', crrScore);
      $('#scoreModal  #criteriaScore').val(id);
    }
  });
  
});
</script>
</body>
</html>
@endsection