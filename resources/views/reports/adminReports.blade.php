@extends('layouts.header')
@section('title','Competitive breakin League')
@section('current-page','Options')
@section('main-section')

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
<script  type="text/javascript" src="{{URL::asset('public/js/bootstrap.min.js')}}"></script>
<script  type="text/javascript" src="{{URL::asset('public/js/menu/metisMenu.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('public/js/menu/nanoscroller.js')}}"></script>
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
<script type="text/javascript" src="{{URL::asset('public/js/jQuery.style.switcher.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('public/js/jquery-functions.js')}}"></script>
<script>
$(document).ready(function() {
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
});
</script>
</body>
</html>
@endsection