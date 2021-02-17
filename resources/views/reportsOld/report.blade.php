<!DOCTYPE html>
<html lang="en">
<head>
  <title>{{$competition->title}}</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</head>
<body>

<div class="">
  <div class="col-md-12 p-0 text-center shadow-sm">
    <img src="{{asset('public/img/logo.jpg')}}" alt="" class="img-fluid col-md-3 pb-3">
  </div>

  <div class="col-md-12 p-0 d-flex shadow-sm pt-3 pb-3">
    <div class="col-md-6 text-center">
      <h3>{{$match->teamOne->name}}</h3>
    </div>
    <div class="col-md-6 text-center">
      <h3>{{$match->teamTwo->name}}</h3>
    </div>
  </div>


  <div class="col-md-12 p-0 d-flex shadow-sm pt-3 pb-3">
    <div class="col-md-3 text-center">
      <h5>{{$match->teamOne->members[0]->participant->name}}</h5>
    </div>
    <div class="col-md-3 text-center">
      <h5>{{$match->teamOne->members[1]->participant->name}}</h5>
    </div>
    <div class="col-md-3 text-center">
      <h5>{{$match->teamTwo->members[0]->participant->name}}</h5>
    </div>
    <div class="col-md-3 text-center">
      <h5>{{$match->teamTwo->members[1]->participant->name}}</h5>
    </div>

  </div>

@foreach ($allData as $key => $data)
  <div class="p-0 d-flex  mb-3 mt-3 justify-content-center">
          <h5>Round No: {{$key + 1}}</h5>
  </div>
  <div class="p-0 d-flex shadow-sm pt-3 pb-3">
    @php

      // debug
      // $data = $allData[1];

      $participant1Data = $data->participant1Data;
      // array_map
      // dd($participant1Data,array_values($participant1Data));
      $p1colums = count(current($participant1Data));
      $participant2Data = $data->participant2Data;
      $p2colums = count(current($participant2Data));
      $participant3Data = $data->participant3Data;
      $p3colums = count(current($participant3Data));
      $participant4Data = $data->participant4Data;
      $p4colums = count(current($participant4Data));

      $p1Scoree = App\Helpers\Helpers::getSumScore($participant1Data);
      $p2Scoree = App\Helpers\Helpers::getSumScore($participant2Data);
      $p3Scoree = App\Helpers\Helpers::getSumScore($participant3Data);
      $p4Scoree = App\Helpers\Helpers::getSumScore($participant4Data);


    @endphp
    <div class="col p-0">
      <table class="table table-hover table-striped table-hover table-sm">
        <tr>
          <th></th>
          @for ($i=1; $i <= $p1colums; $i++)
            <th>Round {{$i}}</th>
          @endfor
        </tr>
        @foreach ($participant1Data as $key => $arr)
          <tr>
            <th> {{$key}} </th>
            @for ($i=1; $i <= count($arr); $i++)
              <td> {{$arr[$i]}}  </td>
            @endfor
          </tr>
        @endforeach

      </table>
      <br>
      <div class="text-center">
              Total Score: {{$p1Scoree}}
      </div>
    </div>


    {{-- 2nd participant --}}
    <div class="col p-0 border-left">
      <table class="table table-hover table-striped table-hover table-sm">
        <tr>
          <th></th>
          @for ($i=1; $i <= $p2colums; $i++)
            <th>Round {{$i}}</th>
          @endfor
        </tr>
        @foreach ($participant2Data as $key => $arr)
          <tr>
            <th> {{$key}} </th>
            @for ($i=1; $i <= count($arr); $i++)
              <td> {{$arr[$i]}}  </td>
            @endfor
          </tr>
        @endforeach

      </table>
      <br>
      <div class="text-center">
              Total Score: {{$p2Scoree}}
      </div>
    </div>
    {{-- 3rd participant --}}
    <div class="col p-0 border-left">
      <table class="table table-hover table-striped table-hover table-sm">
        <tr>
          <th> </th>
          @for ($i=1; $i <= $p3colums; $i++)
            <th>Round {{$i}}</th>
          @endfor
        </tr>
        @foreach ($participant3Data as $key => $arr)
          <tr>
            <th> {{$key}} </th>
            @for ($i=1; $i <= count($arr); $i++)
              <td> {{$arr[$i]}}  </td>
            @endfor
          </tr>
        @endforeach

      </table>
      <br>
      <div class="text-center">
              Total Score: {{$p3Scoree}}
      </div>
    </div>


    {{-- 4th participant --}}
    <div class="col p-0 border-left">
      <table class="table table-hover table-striped table-sm">
        <tr>
          <th></th>
          @for ($i=1; $i <= $p4colums; $i++)
            <th>Round {{$i}}</th>
          @endfor
        </tr>
        @foreach ($participant4Data as $key => $arr)
          <tr>
            <th> {{$key}} </th>
            @for ($i=1; $i <= count($arr); $i++)
              <td> {{$arr[$i]}}  </td>
            @endfor
          </tr>
        @endforeach

      </table>
      <br>
      <div class="text-center">
              Total Score: {{$p4Scoree}}
      </div>
    </div>

  </div>
@endforeach



</div>
<script type="text/javascript">
window.print();
</script>
</body>
</html>
