@extends('layouts.secondHeader')
@section('content')
  <div class="container">
    <div class="row">
      <div class="col-12 d-flex justify-content-center mt-4">
        <form class="form-inline">
          <label class="my-1 mr-2" for="ageGroup">Age group</label>
          <select class="custom-select my-1 mr-sm-2 rounded-0 shadow-sm" id="ageGroup">

            <option selected value="1">5 - 12</option>
            <option value="2">13 - 18</option>
            <option value="3">18+</option>
          </select>
        </form>

      </div>
    </div>
    <div class="row">
      <div class="col-12 mt-4">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>Rank</th>
              <th>Team</th>
              <th>Points</th>
              <th>Join Date</th>
            </tr>
          </thead>
          <tbody>
            @php
            $ageGroupOne = $teams->where('ageGroup',1);
            $ageGroupTwo = $teams->where('ageGroup',2);
            $ageGroupThree = $teams->where('ageGroup',3);
            @endphp

            @foreach ($ageGroupOne as $team)
            <tr class="ageGroup{{$team->ageGroup}}"  >
            <td>{{$loop->iteration}}</td>
            <td>{{$team->name}}</td>
            <td>{{$team->rank}}</td>
            <td>{{$team->join_date}}</td>
            </tr>
            @endforeach
            @foreach ($ageGroupTwo as $team)
            <tr class="ageGroup{{$team->ageGroup}}" style="display:none" >
            <td>{{$loop->iteration}}</td>
            <td>{{$team->name}}</td>
            <td>{{$team->rank}}</td>
            <td>{{$team->join_date}}</td>
            </tr>
            @endforeach
            @foreach ($ageGroupThree as $team)
            <tr class="ageGroup{{$team->ageGroup}}" style="display:none" >
            <td>{{$loop->iteration}}</td>
            <td>{{$team->name}}</td>
            <td>{{$team->rank}}</td>
            <td>{{$team->join_date}}</td>
            </tr>
            @endforeach

          </tbody>
        </table>
      </div>
    </div>


@include('reports.common-info')


  </div>

  <script type="text/javascript">
  $("#ageGroup").change(function(event) {
    var val = $(this).val();
    console.log(val);
    if (val == 1) {
      $(".ageGroup1").show();
      $(".ageGroup2").hide();
      $(".ageGroup3").hide();
    }else if (val == 2) {
      $(".ageGroup1").hide();
      $(".ageGroup2").show();
      $(".ageGroup3").hide();

    }else if (val == 3) {
      $(".ageGroup1").hide();
      $(".ageGroup2").hide();
      $(".ageGroup3").show();
    }
  });
  </script>
@endsection
