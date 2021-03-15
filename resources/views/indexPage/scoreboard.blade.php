 <!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scoreboard | {{ $website_title }}</title>
    <link href="https://fonts.googleapis.com/css?family=Alegreya" rel="stylesheet">
    <script src="{{URL::asset('public/js/jquery.min.js')}}" charset="utf-8"></script>
    <link rel="stylesheet" type="text/css" href="{{URL::asset('public/css/home.css?ver=2.5.5')}}">
    <script src="{{URL::asset('public/js/angular.js')}}" charset="utf-8"></script>
    <script src="{{URL::asset('public/js/homeController.js')}}" charset="utf-8"></script>
    <script src="{{URL::asset('public/js/underscore.js')}}" charset="utf-8"></script>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

  @if ( $website_background_status == 'show')
   <style media="screen">
    body {
    background-image: url('{{ asset('storage/app/'.$website_background) }}');
    background-repeat: no-repeat;
    background-position: center;
    background-size: cover;
    }
   </style>
  @endif

    @if ($sponsors_slider_width)
      <style media="screen">
        .swiper-container{
          max-width: {{ $sponsors_slider_width }}%;
        }
      </style>  
    @endif

    <style media="screen">
      .vsss{
        color: white;
        text-shadow: 2px 2px 4px #000000;
        font-size: 20px;
      }
      .newLabel{
        background: white;
        top: 70%;
        /* left: 45%; */
        height: 60px;
        text-align: center;
        width: 87.4%;
        z-index: 333333333333333333;
      }
    </style>
    <script type="text/javascript">
    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        var expires = "expires="+d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    function getCookie(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for(var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    function checkCookie() {
        var user = getCookie("username");
        if (user != "") {
            alert("Welcome again " + user);
        } else {
            user = prompt("Please enter your name:", "");
            if (user != "" && user != null) {
                setCookie("username", user, 365);
            }
        }
    }
      function dd(data){
        var dev = getCookie('dev');
        if (dev == "true") {
          console.log(data);
          var old = $("#debug").html( );
          $("#debug").html(data + "<br><br>" + old);
        }
      }
      window.dd = dd;
    </script>
    <script type="text/javascript">
      $(document).ready(function() {
        $("#bodyhai").show();
      });
    </script>
</head>
<body ng-app="app" ng-controller="homeController" id="bodyhai" style="display:none;">

@if($website_identity != 'title')
<img ng-hide="screen.displayTeamVSscore" src="{{ asset('storage/app/'.$website_logo) }}" alt="logo" class="img-logo">
@endif

<div class="selectBoxcontainer" ng-hide="screen.displayTeamVSscore">
  <select ng-change="competitionChanged()" class="select-box" ng-model="selectedCompetition" ng-options="competition.title for competition in competitions">
  </select>
  <select class="roundChoice" style="display:none;" name="opasdfa" ng-change="roundChoiceChanged()" ng-model="roundChoiceChangedAAAA" ng-options="x for x in currentCompetitionsAllRounds">
  </select>
</div>

@verbatim

<!-- ScoreBoard Section with Sponsors START -->
<div class="tableContainer" ng-show="screen.displayTable">
  <div id="tableBodyDiv" class="tableBodyDiv">
    <?php if($scoreboard_layout == 'style_1' ){ ?>
    <table id="mytable" class="table" style="width:100%;">
      <thead>
        <tr>
          <th style="text-align:left;"> <strong> Teams</strong></th>
          <th class="firstTeamCol">First Team</th>
          <th class="secondTeamCol">Second Team</th>
        </tr>
      </thead>
      <tbody id="tableBody">
        <tr ng-repeat="match in matchesOfSelectedCompetition">
          <td>{{match.t1name }} <span class="vsss">VS</span> {{match.t2name }}</td>
          <td class="firstTeamCol">{{match.t1score}}</td>
          <td class="secondTeamCol">{{match.t2score}}</td>
        </tr>
      </tbody>
    </table>
    <?php }else{ ?>
    <div class="scoreboard-wrapper">
      <div class="scoreboard-row" ng-repeat="match in matchesOfSelectedCompetition">
        <div class="left-team-score">
          {{match.t1score}}
        </div>
        <div class="left-team-name">
          {{match.t1name }}
        </div>

        <div class="team-vs">
          VS
        </div>

        <div class="right-team-name">
          {{match.t2name }}
        </div>
        <div class="right-team-score">
          {{match.t2score}}
        </div>
      </div>
    </div>
    <?php } ?>
  </div>
@endverbatim

  @if ( $sponsors_slider != 'hide')
  <div id="slider-1" class="continerOfSlider">
    <!-- Swiper -->
    <div class="swiper-container">
      <div class="swiper-wrapper">
        @foreach ($sponsors as $sponser)
          <div class="swiper-slide">
            <p>
              <span>{{$sponser->title}}</span>
              <img  style="width:100%;" src="{{ asset('storage/app/'.$sponser->image) }}" alt="{{$sponser->title}}">
            </p>
          </div>
        @endforeach
      </div>
      <!-- Add Pagination -->
      <div class="swiper-pagination"></div>
    </div>
  </div>
  @endif
</div>

@if($website_identity != 'logo')
<div class="siteIdentity">
  <span>{{ $website_title }}</span>
</div>
@endif
<div class="rankingBox">
  {{-- <a href="{{ url('participantsRanking') }}">Participant Ranking</a>
  <a href="{{ url('teamsRanking') }}">Teams Ranking</a> --}}
  <a href="{{ url('privacy') }}">Privacy</a>
</div>

</body>
</html>
