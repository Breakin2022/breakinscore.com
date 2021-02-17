<html>

<head>

  <script
    src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
    integrity="sha256-3edrmyuQ0w65f8gfBsqowzjJe2iM6n0nKciPUp8y+7E="
    crossorigin="anonymous"></script>
  <script src="https://code.getmdl.io/1.3.0/material.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/getmdl-select@2.0.1/getmdl-select.min.js" charset="utf-8"></script>
  <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
  <!-- Material Design icon font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <style media="screen">
    .mytable{
        width: 80%;
        text-align: center;
        /* position: absolute; */
        /* left: 10%;
        right: 10%; */
        top: 320px;
        margin-bottom: 50px;
        display: flex !important;
      }
      .imgDiv{
        position: absolute;
        left: 34%;
        right: 30%;
        width: 30%;
      }
      .imgDiv > img{
        width: 100%;
      }
      .BtnContainer{
        /* position: absolute; */
        top: 520px;
        left: 10%;
        right: 10%;
        text-align: center;
        display: flex;
        justify-content: center;
      }
      .mdl-button{
        margin-top: 12px;
      }
      .selecbx {
        /* display: flex; */
width: 157px;
margin-top: 150px;
position: relative !important;
display: flex;
justify-content: center;
/* left: 44%; */
      }
      .left{
        text-align: left !important;
      }
      .info{
        z-index: 99999;
        /* position: absolute;
        top: 10%; */
      }
    </style>
</head>

<body>
  <div class="imgDiv">
    <img src="{{asset('public/img/logo.jpg')}}" alt="">
  </div>
  <br>
  <br>

  @isset($participants)
  <div class="BtnContainer">
    <!-- Pre-selected value -->
    <div class="selecbx mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height">
      <input type="text" value="" class="mdl-textfield__input" id="ageGroup" readonly>
      <input type="hidden" value="" name="ageGroup">
      <i class="mdl-icon-toggle__label material-icons">keyboard_arrow_down</i>
      <label for="ageGroup" class="mdl-textfield__label">Age Group</label>
      <ul for="ageGroup" class="mdl-menu mdl-menu--bottom-left mdl-js-menu">
        <li class="mdl-menu__item" data-val="1" data-selected="true">5 - 12</li>
        <li class="mdl-menu__item" data-val="2">13 - 18</li>
        <li class="mdl-menu__item" data-val="3">18+</li>

      </ul>
    </div>


  </div>
  <div class="">


  <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp mytable">
    <thead>
      <tr>
        <th class="left">Rank</th>
        <th class="mdl-data-table__cell--non-numeric">Participant</th>
        <th>Points</th>
        <th>Join Date</th>
      </tr>
    </thead>
    <tbody>
      @php
        $ageGroupOne = $participants->where('ageGroup',1);
        $ageGroupTwo = $participants->where('ageGroup',2);
        $ageGroupThree = $participants->where('ageGroup',3);
      @endphp
      @foreach ($ageGroupOne as $participant)
      <tr class="ageGroup{{$participant->ageGroup}}" style="display:none">

        <td class="left">{{$loop->iteration}}</td>
        <td class="mdl-data-table__cell--non-numeric">{{$participant->name}}</td>
        <td>{{$participant->rank}}</td>
        <td>{{$participant->join_date}}</td>
      </tr>
      @endforeach

      @foreach ($ageGroupTwo as $participant)
      <tr class="ageGroup{{$participant->ageGroup}}" style="display:none">

        <td class="left">{{$loop->iteration}}</td>
        <td class="mdl-data-table__cell--non-numeric">{{$participant->name}}</td>
        <td>{{$participant->rank}}</td>
        <td>{{$participant->join_date}}</td>
      </tr>
      @endforeach

      @foreach ($ageGroupThree as $participant)
      <tr class="ageGroup{{$participant->ageGroup}}" style="display:none">

        <td class="left">{{$loop->iteration}}</td>
        <td class="mdl-data-table__cell--non-numeric">{{$participant->name}}</td>
        <td>{{$participant->rank}}</td>
        <td>{{$participant->join_date}}</td>
      </tr>
      @endforeach

    </tbody>
  </table>
    </div>
  <div class="info">
    1- If participant wins the match, rank of participant will be increased by 2
    <br>
2- if participant loses the match, rank of participant will be decreased by 1
  </div>
  @else

  {{-- <div class="BtnContainer">
      Please select age group .
      <br>
      <a class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" href="{{ route('participantsRanking',1) }}">5 - 12</a> <br>
  <a class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" href="{{ route('participantsRanking',2) }}">13 - 18</a> <br>
  <a class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" href="{{ route('participantsRanking',3) }}">18+</a> <br>
  </div> --}}
  @endisset

  <script type="text/javascript">
    $("#ageGroup").change(function(event) {
      var val = $(this).val();
      if (val == "5 - 12") {
        $(".ageGroup1").show();
        $(".ageGroup2").hide();
        $(".ageGroup3").hide();
      }else if (val == "13 - 18") {
        $(".ageGroup1").hide();
        $(".ageGroup2").show();
        $(".ageGroup3").hide();

      }else if (val == "18+") {
        $(".ageGroup1").hide();
        $(".ageGroup2").hide();
        $(".ageGroup3").show();
      }
    });
    // var elm = document.getElementById("ageGroup");
    // elm.addEventListener('change', function(val) {
    //   var a = document.getElementById('ageGroup').getAttribute('data-val');
    //   console.log(a);
    // });
  </script>
</body>

</html>
