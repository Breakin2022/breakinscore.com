 <!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Competitive Breakin League</title>
    <link href="https://fonts.googleapis.com/css?family=Alegreya" rel="stylesheet">
    <script src="{{URL::asset('public/js/jquery.min.js')}}" charset="utf-8"></script>
    <link rel="stylesheet" type="text/css" href="{{URL::asset('public/css/home.css')}}">
    <script src="{{URL::asset('public/js/angular.js')}}" charset="utf-8"></script>
    <script src="{{URL::asset('public/js/homeController.js')}}" charset="utf-8"></script>
    <script src="{{URL::asset('public/js/underscore.js')}}" charset="utf-8"></script>

    <style media="screen">
      .debugArea{
        /* background: white;
        color: black;
        height: 500px;
        width: 250px;
        position: absolute;
        left: 0;
        top: 0;
        z-index: 999; */
      }
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
<div class="debugArea" id="debug" style="    display: block; position: fixed;    z-index: 99999; height: 200px; color: yellow; width: 200px;">

</div>
@verbatim


<div class="rankingBox">
  <a href="participantsRanking" style="color:white;text-decoration:none;border-bottom: 1px solid white;">Participant Ranking</a>
  &nbsp &nbsp &nbsp &nbsp
  <a href="teamsRanking" style="color:white;text-decoration:none;border-bottom: 1px solid white;">Teams Ranking</a>
</div>
<div class="overlay"> </div>

<img ng-hide="screen.displayTeamVSscore" src="/public/img/logo.jpg" alt="logo" class="img-logo">

<div class="box-1 display8" ng-show="screen.display8">

  <div class="box-inner-1">
    <div class="name mostimportantclass">
      {{eightTeam.match.secondRoundMatches[0].t1name }}
    </div>
    <div class="score mostimportantclass">
      {{eightTeam.match.secondRoundMatches[0].t1score }}
    </div>
  </div>

  <div class="box-inner-2">
    <div class="name mostimportantclass">
      {{eightTeam.match.secondRoundMatches[0].t2name }}
    </div>
    <div class="score mostimportantclass">
      {{eightTeam.match.secondRoundMatches[0].t2score }}
    </div>
  </div>
  <div class="box-inner-3">
    <div class="name mostimportantclass">
      {{eightTeam.match.secondRoundMatches[1].t1name }}
    </div>
    <div class="score mostimportantclass">
      {{eightTeam.match.secondRoundMatches[1].t1score }}
    </div>
  </div>
  <div class="box-inner-4">
    <div class="name mostimportantclass">
      {{eightTeam.match.secondRoundMatches[1].t2name }}
    </div>
    <div class="score mostimportantclass">
      {{eightTeam.match.secondRoundMatches[1].t2score }}
    </div>
  </div>
  <div class="box-inner-5">
    <div class="name mostimportantclass">
      {{eightTeam.match.thirdRoundMatches[0].t1name }}
    </div>
    <div class="score mostimportantclass">
      {{eightTeam.match.thirdRoundMatches[0].t1score }}
    </div>
  </div>
  <div class="box-inner-6">
    <div class="name mostimportantclass">
      {{eightTeam.match.thirdRoundMatches[0].t2name }}
    </div>
    <div class="score mostimportantclass">
      {{eightTeam.match.thirdRoundMatches[0].t2score }}
    </div>
  </div>
  <div class="box-inner-7">
    <div class="name mostimportantclass">
      {{eightTeam.match.fourthRoundMatches[0].t1name }}
    </div>
    <div class="score mostimportantclass">
      {{eightTeam.match.fourthRoundMatches[0].t1score }}
    </div>
  </div>





  <div class="box-inner-1-border-start"> </div>
  <div class="box-inner-1-border"> </div>
  <div class="box-inner-1-border-end"> </div>


  <div class="box-inner-2-border-start"> </div>
  <div class="box-inner-2-border"> </div>
  <div class="box-inner-2-border-end"> </div>



  <div class="box-inner-3-border-start"> </div>
  <div class="box-inner-3-border"> </div>
  <div class="box-inner-3-border-end"> </div>



</div>


<div class="selectBoxcontainer" ng-hide="screen.displayTeamVSscore">


      <select ng-change="competitionChanged()" class="select-box" ng-model="selectedCompetition" ng-options="competition.title for competition in competitions">
      </select>

      <select class="roundChoice" style="display:none;" name="opasdfa" ng-change="roundChoiceChanged()" ng-model="roundChoiceChangedAAAA" ng-options="x for x in currentCompetitionsAllRounds">

      </select>
</div>


<div class="winner-container-match-4  display8" ng-show="screen.display8">


<div class="winner-top-line-start"> </div>
<div class="winner-top-line"> </div>
<div class="winner-top-line-end"> </div>

<div class="winner-box">
  <div class="name mostimportantclass">
    {{eightTeam.match.fourthRoundMatches[0].winner }}
  </div>
  <div class="score mostimportantclass">
    {{eightTeam.match.fourthRoundMatches[0].winnerScore }}
  </div>
</div>

<div class="winner-center-line"> </div>
</div>


<div class="box-2 display8" ng-show="screen.display8">

    <div class="box-inner-1-r">
      <div class="name mostimportantclass">
        {{eightTeam.match.secondRoundMatches[2].t1name }}
      </div>
      <div class="score mostimportantclass">
        {{eightTeam.match.secondRoundMatches[2].t1score }}
      </div>
    </div>
    <div class="box-inner-2-r">
      <div class="name mostimportantclass">
        {{eightTeam.match.secondRoundMatches[2].t2name }}
      </div>
      <div class="score mostimportantclass">
        {{eightTeam.match.secondRoundMatches[2].t2score }}
      </div>
    </div>
    <div class="box-inner-3-r">
      <div class="name mostimportantclass">
        {{eightTeam.match.secondRoundMatches[3].t1name }}
      </div>
      <div class="score mostimportantclass">
        {{eightTeam.match.secondRoundMatches[3].t1score }}
      </div>
    </div>
    <div class="box-inner-4-r">
      <div class="name mostimportantclass">
        {{eightTeam.match.secondRoundMatches[3].t2name }}
      </div>
      <div class="score mostimportantclass">
        {{eightTeam.match.secondRoundMatches[3].t2score }}
      </div>
    </div>
    <div class="box-inner-5-r">
      <div class="name mostimportantclass">
        {{eightTeam.match.thirdRoundMatches[1].t1name }}
      </div>
      <div class="score mostimportantclass">
        {{eightTeam.match.thirdRoundMatches[1].t1score }}
      </div>
    </div>
    <div class="box-inner-6-r">
      <div class="name mostimportantclass">
        {{eightTeam.match.thirdRoundMatches[1].t2name }}
      </div>
      <div class="score mostimportantclass">
        {{eightTeam.match.thirdRoundMatches[1].t2score }}
      </div>
    </div>
    <div class="box-inner-7-r">
      <div class="name mostimportantclass">
        {{eightTeam.match.fourthRoundMatches[0].t2name }}
      </div>
      <div class="score mostimportantclass">
        {{eightTeam.match.fourthRoundMatches[0].t2score }}
      </div>
    </div>



    <div class="box-inner-1-border-start-r"> </div>
    <div class="box-inner-1-border-r"> </div>
    <div class="box-inner-1-border-end-r"> </div>


    <div class="box-inner-2-border-start-r"> </div>
    <div class="box-inner-2-border-r"> </div>
    <div class="box-inner-2-border-end-r"> </div>



    <div class="box-inner-3-border-start-r"> </div>
    <div class="box-inner-3-border-r"> </div>
    <div class="box-inner-3-border-end-r"> </div>



</div>










<div class="box-1 display16" ng-show="screen.display16">

  <div class="b1">
    <div class="name mostimportantclass">
      {{sixteenTeam.match.secondRoundMatches[0].t1name }}
    </div>
    <div class="score mostimportantclass">
      {{sixteenTeam.match.secondRoundMatches[0].t1score }}
    </div>
  </div>

  <div class="b2">
    <div class="name mostimportantclass">
      {{sixteenTeam.match.secondRoundMatches[0].t2name }}
    </div>
    <div class="score mostimportantclass">
      {{sixteenTeam.match.secondRoundMatches[0].t2score }}
    </div>
  </div>
  <div class="b3">
    <div class="name mostimportantclass">
      {{sixteenTeam.match.secondRoundMatches[1].t1name }}

    </div>
    <div class="score mostimportantclass">
      {{sixteenTeam.match.secondRoundMatches[1].t1score }}

    </div>
  </div>
  <div class="b4">
    <div class="name mostimportantclass">
      {{sixteenTeam.match.secondRoundMatches[1].t2name }}
    </div>
    <div class="score mostimportantclass">
      {{sixteenTeam.match.secondRoundMatches[1].t2score }}
    </div>
  </div>
  <div class="b5">
    <div class="name mostimportantclass">
      {{sixteenTeam.match.secondRoundMatches[2].t1name }}
    </div>
    <div class="score mostimportantclass">
      {{sixteenTeam.match.secondRoundMatches[2].t1score }}
    </div>
  </div>
  <div class="b6">
    <div class="name mostimportantclass">
      {{sixteenTeam.match.secondRoundMatches[2].t2name }}
    </div>
    <div class="score mostimportantclass">
      {{sixteenTeam.match.secondRoundMatches[2].t2score }}
    </div>
  </div>
  <div class="b7">
    <div class="name mostimportantclass">
      {{sixteenTeam.match.secondRoundMatches[3].t1name }}
    </div>
    <div class="score mostimportantclass">
      {{sixteenTeam.match.secondRoundMatches[3].t1score }}
    </div>
  </div>
  <div class="b8">
    <div class="name mostimportantclass">
      {{sixteenTeam.match.secondRoundMatches[3].t2name }}
    </div>
    <div class="score mostimportantclass">
      {{sixteenTeam.match.secondRoundMatches[3].t2score }}
    </div>
  </div>

  <div class="b9">
    <div class="name mostimportantclass">
      {{sixteenTeam.match.thirdRoundMatches[0].t1name}}
    </div>
    <div class="score mostimportantclass">
      {{sixteenTeam.match.thirdRoundMatches[0].t1score}}
    </div>
  </div>
  <div class="b10">
    <div class="name mostimportantclass">
      {{sixteenTeam.match.thirdRoundMatches[0].t2name}}
    </div>
    <div class="score mostimportantclass">
      {{sixteenTeam.match.thirdRoundMatches[0].t2score}}
    </div>
  </div>
  <div class="b11">
    <div class="name mostimportantclass">
      {{sixteenTeam.match.thirdRoundMatches[1].t1name}}
    </div>
    <div class="score mostimportantclass">
      {{sixteenTeam.match.thirdRoundMatches[1].t1score}}
    </div>
  </div>
  <div class="b12">
    <div class="name mostimportantclass">
      {{sixteenTeam.match.thirdRoundMatches[1].t2name}}
    </div>
    <div class="score mostimportantclass">
      {{sixteenTeam.match.thirdRoundMatches[1].t2score}}
    </div>
  </div>

  <div class="b13">
    <div class="name mostimportantclass">
      {{sixteenTeam.match.fourthRoundMatches[0].t1name}}
    </div>
    <div class="score mostimportantclass">
      {{sixteenTeam.match.fourthRoundMatches[0].t1score}}
    </div>
  </div>


  <div class="b14">
    <div class="name mostimportantclass">
      {{sixteenTeam.match.fourthRoundMatches[0].t2name}}
    </div>
    <div class="score mostimportantclass">
      {{sixteenTeam.match.fourthRoundMatches[0].t2score}}
    </div>
  </div>

  <div class="b15">
    <div class="name mostimportantclass">
      {{sixteenTeam.match.fifthRoundMatches[0].t1name}}
    </div>
    <div class="score mostimportantclass">
      {{sixteenTeam.match.fifthRoundMatches[0].t1score}}
    </div>

  </div>



  <div class="b1-start"> </div>
  <div class="b1-b2-side"> </div>
  <div class="b2-start"> </div>
  <div class="b1-b2-line"> </div>

  <div class="b3-start"> </div>
  <div class="b3-b4-side"> </div>
  <div class="b4-start"> </div>
  <div class="b3-b4-line"> </div>


  <div class="b5-start"> </div>
  <div class="b5-b6-side"> </div>
  <div class="b6-start"> </div>
  <div class="b5-b6-line"> </div>


  <div class="b7-start"> </div>
  <div class="b7-b8-side"> </div>
  <div class="b8-start"> </div>


  <div class="b9-start"> </div>
  <div class="b9-b10-side"> </div>
  <div class="b10-start"> </div>

  <div class="b11-start"> </div>
  <div class="b11-b12-side"> </div>
  <div class="b12-start"> </div>

  <div class="b13-start"> </div>
  <div class="b13-b14-side"> </div>
  <div class="b14-start"> </div>

  <div class="b15-start"> </div>

</div>






<div class="wr" ng-show="screen.display16">
  <div class="wtl"> </div>
  <div class="wbox">
    <div class="name mostimportantclass">
      {{sixteenTeam.match.fifthRoundMatches[0].winner}}
    </div>
    <div class="score mostimportantclass">
      {{sixteenTeam.match.fifthRoundMatches[0].winnerScore}}
    </div>
  </div>
  <div class="wcl"> </div>
</div>












<div class="box-2  display16" ng-show="screen.display16">

  <div class="b1r">
    <div class="name mostimportantclass">
      {{sixteenTeam.match.secondRoundMatches[4].t1name }}
    </div>
    <div class="score mostimportantclass">
      {{sixteenTeam.match.secondRoundMatches[4].t1score }}
    </div>
  </div>

  <div class="b2r">
    <div class="name mostimportantclass">
      {{sixteenTeam.match.secondRoundMatches[4].t2name }}
    </div>
    <div class="score mostimportantclass">
      {{sixteenTeam.match.secondRoundMatches[4].t2score }}
    </div>
  </div>
  <div class="b3r">
    <div class="name mostimportantclass">
      {{sixteenTeam.match.secondRoundMatches[5].t1name }}
    </div>
    <div class="score mostimportantclass">
      {{sixteenTeam.match.secondRoundMatches[5].t1score }}
    </div>
  </div>
  <div class="b4r">
    <div class="name mostimportantclass">
      {{sixteenTeam.match.secondRoundMatches[5].t2name }}
    </div>
    <div class="score mostimportantclass">
      {{sixteenTeam.match.secondRoundMatches[5].t2score }}
    </div>
  </div>
  <div class="b5r">
    <div class="name mostimportantclass">
      {{sixteenTeam.match.secondRoundMatches[6].t1name }}
    </div>
    <div class="score mostimportantclass">
      {{sixteenTeam.match.secondRoundMatches[6].t1score }}
    </div>
  </div>
  <div class="b6r">
    <div class="name mostimportantclass">
      {{sixteenTeam.match.secondRoundMatches[6].t2name }}
    </div>
    <div class="score mostimportantclass">
      {{sixteenTeam.match.secondRoundMatches[6].t2score }}
    </div>
  </div>
  <div class="b7r">
    <div class="name mostimportantclass">
      {{sixteenTeam.match.secondRoundMatches[7].t1name }}
    </div>
    <div class="score mostimportantclass">
      {{sixteenTeam.match.secondRoundMatches[7].t1score }}
    </div>
  </div>
  <div class="b8r">
    <div class="name mostimportantclass">
      {{sixteenTeam.match.secondRoundMatches[7].t2name }}
    </div>
    <div class="score mostimportantclass">
      {{sixteenTeam.match.secondRoundMatches[7].t2score }}
    </div>
  </div>

  <div class="b9r">
    <div class="name mostimportantclass">
      {{sixteenTeam.match.thirdRoundMatches[2].t1name}}
    </div>
    <div class="score mostimportantclass">
      {{sixteenTeam.match.thirdRoundMatches[2].t1score}}
    </div>
  </div>
  <div class="b10r">
    <div class="name mostimportantclass">
      {{sixteenTeam.match.thirdRoundMatches[2].t2name}}
    </div>
    <div class="score mostimportantclass">
      {{sixteenTeam.match.thirdRoundMatches[2].t2score}}
    </div>
  </div>
  <div class="b11r">
    <div class="name mostimportantclass">
      {{sixteenTeam.match.thirdRoundMatches[3].t1name}}
    </div>
    <div class="score mostimportantclass">
      {{sixteenTeam.match.thirdRoundMatches[3].t1score}}
    </div>
  </div>
  <div class="b12r">
    <div class="name mostimportantclass">
      {{sixteenTeam.match.thirdRoundMatches[3].t2name}}
    </div>
    <div class="score mostimportantclass">
      {{sixteenTeam.match.thirdRoundMatches[3].t2score}}
    </div>
  </div>

  <div class="b13r">
    <div class="name mostimportantclass">
      {{sixteenTeam.match.fourthRoundMatches[1].t1name}}
    </div>
    <div class="score mostimportantclass">
      {{sixteenTeam.match.fourthRoundMatches[1].t1score}}
    </div>
  </div>


  <div class="b14r">
    <div class="name mostimportantclass">
      {{sixteenTeam.match.fourthRoundMatches[1].t2name}}
    </div>
    <div class="score mostimportantclass">
      {{sixteenTeam.match.fourthRoundMatches[1].t2score}}
    </div>
  </div>

  <div class="b15r">
    <div class="name mostimportantclass">
      {{sixteenTeam.match.fifthRoundMatches[0].t2name}}
    </div>
    <div class="score mostimportantclass">
      {{sixteenTeam.match.fifthRoundMatches[0].t2score}}
    </div>
  </div>




  <div class="b1-r-start"> </div>
  <div class="b1-r-b2-side"> </div>
  <div class="b2-r-start"> </div>
  <div class="b1-r-b2-line"> </div>

  <div class="b3-r-start"> </div>
  <div class="b3-r-b4-side"> </div>
  <div class="b4-r-start"> </div>
  <div class="b3-r-b4-line"> </div>


  <div class="b5-r-start"> </div>
  <div class="b5-r-b6-side"> </div>
  <div class="b6-r-start"> </div>
  <div class="b5-r-b6-line"> </div>


  <div class="b7-r-start"> </div>
  <div class="b7-r-b8-side"> </div>
  <div class="b8-r-start"> </div>


  <div class="b9-r-start"> </div>
  <div class="b9-r-b10-side"> </div>
  <div class="b10-r-start"> </div>

  <div class="b11-r-start"> </div>
  <div class="b11-r-b12-side"> </div>
  <div class="b12-r-start"> </div>

  <div class="b13-r-start"> </div>
  <div class="b13-r-b14-side"> </div>
  <div class="b14-r-start"> </div>

  <div class="b15-r-start"> </div>



</div>




<div class="box-1  display2" ng-show="screen.display2">

  <div class="b1">
      <div class="name mostimportantclass">
        {{twoTeam.match.t1name}}
      </div>
      <div class="score mostimportantclass">
        {{twoTeam.match.t1score}}
      </div>
  </div>


</div>
<div class="wr  display2" ng-show="screen.display2">
  <div class="wtl"> </div>
  <div class="wbox">

    <div class="name mostimportantclass">
      {{twoTeam.match.winner}}
    </div>
    <div class="score mostimportantclass">
      {{twoTeam.match.winnerScore}}
    </div>


  </div>
  <div class="wcl"> </div>
  <div class="b15-start"> </div>
  <div class="b15-r-start"> </div>

</div>
<div class="box-2  display2" ng-show="screen.display2">
  <div class="b1r">
      <div class="name mostimportantclass">
        {{twoTeam.match.t2name}}
      </div>
      <div class="score mostimportantclass">
        {{twoTeam.match.t2score}}
      </div>
  </div>


</div>









<div class="box-1  display4" ng-show="screen.display4">


  <div class="b1">
      <div class="name mostimportantclass">
         {{fourTeam.match.secondRoundMatches[0].t1name}}
      </div>
       <div class="score mostimportantclass">
        {{fourTeam.match.secondRoundMatches[0].t1score}}
      </div>
  </div>
  <div class="b2">
      <div class="name mostimportantclass">
        {{fourTeam.match.secondRoundMatches[0].t2name}}
      </div>
      <div class="score mostimportantclass">
        {{fourTeam.match.secondRoundMatches[0].t2score}}
      </div>
  </div>
  <div class="b3">
      <div class="name mostimportantclass">
        {{fourTeam.match.thirdRoundMatches[0].t1name}}
      </div>
      <div class="score mostimportantclass">
        {{fourTeam.match.thirdRoundMatches[0].t1score}}
      </div>
  </div>


</div>

<div class="box-3 display4" ng-show="screen.display4">
  <div class="b1-start"> </div>
  <div class="b1-b2-side"> </div>
  <div class="b2-start"> </div>
</div>
<div class="box-3  display4" ng-show="screen.display4">
  <div class="b1-r-start"> </div>
  <div class="b1-r-b2-side"> </div>
  <div class="b2-r-start"> </div>
  <div class="b7-b8-line"> </div>
  <div class="b7-r-b8-line "> </div>

</div>

<div class="wr  display4" ng-show="screen.display4">
  <div class="wtl"> </div>
  <div class="wbox">
    <div class="name mostimportantclass">
      {{fourTeam.match.thirdRoundMatches[0].winner}}
    </div>
    <div class="score mostimportantclass">
      {{fourTeam.match.thirdRoundMatches[0].winnerScore}}
    </div>
   </div>
  <div class="wcl"> </div>
  <div class="b15-start"> </div>
  <div class="b15-r-start"> </div>

</div>

<div class="box-2  display4" ng-show="screen.display4">
  <div class="b1r">
      <div class="name mostimportantclass">
        {{fourTeam.match.secondRoundMatches[1].t1name}}

      </div>
      <div class="score mostimportantclass">
        {{fourTeam.match.secondRoundMatches[1].t1score}}

      </div>
  </div>
  <div class="b2r">
      <div class="name mostimportantclass">
        {{fourTeam.match.secondRoundMatches[1].t2name}}
      </div>
      <div class="score mostimportantclass">
        {{fourTeam.match.secondRoundMatches[1].t2score}}
      </div>
  </div>
  <div class="b3r">
      <div class="name mostimportantclass">
        {{fourTeam.match.thirdRoundMatches[0].t2name}}
      </div>
      <div class="score mostimportantclass">
        {{fourTeam.match.thirdRoundMatches[0].t2score}}

      </div>
  </div>

</div>










<div class="tableContainer" ng-show="screen.displayTable">





  <div id="tableBodyDiv" class="tableBodyDiv">

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
  </div>

@endverbatim

  <div class="continerOfSlider" style="display: flex;
    justify-content: center;margin-top:10px;">

         <div class="sliderContainer">

        <ul class="bxslider" id="mySliderSponsers">
          @foreach ($sponsors as $sponser)

                      <li style="    float: left; list-style: none;   position: relative;   width: 200px;     margin-right: 10px;">
               <div class=" " style="    color: #fff;   background-color: #3097d1;     display: flex;     justify-content: center;">
                 {{$sponser->title}}
               </div>
               <img  style="width:100%;" src="storage/app/{{$sponser->image}}" alt="">
             </li>
           @endforeach

        </ul>
       </div>
     </div>

@verbatim


</div>


<div class="displayBlank" ng-show="screen.displayBlank">
  <h1>No Records</h1>
</div>
<div class="displayBlank" ng-show="screen.displayLoader">
  <h1>Loading....</h1>
</div>


<div class="displayTimer" ng-show="screen.displayTimer">
  <h1>{{countDown}}</h1>
</div>


<div class="displayTeamVSscore" style="    padding: 35px;" ng-show="screen.displayTeamVSscore" style="margin-bottom:250px;">




<div class="team1Score">
  <div class="team1 displayScoretTitle">
     {{teamVsMatch.t1name}}
  </div>
  <div class="ScoreA">
    <div class="scoreView">
       {{teamVsMatch.t1score}}
     </div>

  </div>
</div>



<div class="team1vsTeam2">

  <div class="vs">
    VS
    <br>

    <br>

    <h2 ng-if="teamVsMatch.t1score == teamVsMatch.t2score">TIE</h2>

  </div>
</div>


<div class="team2Score">
  <div class="team2 displayScoretTitle">
       {{teamVsMatch.t2name}}
  </div>

  <div class="ScoreA">

    <div class="scoreView">
       {{teamVsMatch.t2score}}
     </div>

  </div>
</div>



@endverbatim

  <div class="continerOfSlider" ng-show="screen.displayTeamVSscore" style=" display: flex; position: relative;   margin-top: 44%; z-index: 999; justify-content: center; width: 100%; ">

         <div class="sliderContainer">

        <ul class="bxslider" id="mySliderSponsers1b">
          @foreach ($sponsors as $sponser)

                      <li style="    float: left; list-style: none;   position: relative;   width: 200px;     margin-right: 10px;">
               <div class=" " style="    color: #fff;   background-color: #3097d1;     display: flex;     justify-content: center;">
                 {{$sponser->title}}
               </div>
               <img  style="width:100%;" src="storage/app/{{$sponser->image}}" alt="">
             </li>
           @endforeach

        </ul>
       </div>
     </div>
     @verbatim



</div>





@endverbatim


















<script type="text/javascript">


$(".box-1").height(window.innerHeight);
$(".box-2").height(window.innerHeight);
$("body").height(window.innerHeight);
$(".box-1").height(window.innerHeight);
$(".overlay").height(window.innerHeight);
$("body").css('margin','0');
$("body").css('padding','0');



</script>



<script type="text/javascript">
var master = {

  firstTeamColor: function(){
    return '#f58b83';
  },
  secondTeamColor: function(){
    return '#5489b3';
  },




};


window.master = master;



</script>





<link rel="stylesheet" href="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.css">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
 <script src="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js"></script>

 <script>
     $(document).ready(function(){

       var slider = $('#mySliderSponsers').bxSlider({
          slideWidth: 200,
          minSlides: 1,
          maxSlides: 3,
          slideMargin: 10,
          controls: false,
          auto: true,
          autoControls: true,
          stopAutoOnClick: true,
          pager: true,
        });
        window.slider1 = slider;



        var slider1a = $('#mySliderSponsers1b').bxSlider({
          slideWidth: 200,
          minSlides: 1,
          maxSlides: 3,
          slideMargin: 10,
          controls: false,
          auto: true,
          autoControls: true,
          stopAutoOnClick: true,
          pager: true,
         });
         window.slider2 = slider1a;

      });
 </script>

<style media="screen">
  .rankingBox{
    position: absolute;
    top: 10px;
    right: 10px;
    background: transparent;
    color: white;
    z-index: 999999;
  }
</style>

</body>
</html>
