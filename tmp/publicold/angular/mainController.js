app.controller('homeController',function($scope,homeService ,$interval,$timeout,helper ){
  $scope.collection8Teams = [];
  $scope.play = function() {
        var audio = new Audio('/public/s.mp3');
        audio.play();
  };
  $scope.detailCompetition = {
    lastCompetitionId: 0,
    setCompetitionId: function(competition){
      this.lastCompetitionId = competition.id;
    },
    getCompetitionId: function(){
      return this.lastCompetitionId;
    },

  };
  $scope.matchesFilter = {
    getFinishedMatches: function(matches,allRoundsMatches){
      var filteredMatchesThoseAreFinished  = _.filter(matches,function(o) {
        if (o.isFinished == "null") {
          return false;
        }else{
          return true;
        }
      });

      var AllMatchesN = _.map(allRoundsMatches,function(match){
        console.log(allRoundsMatches);
         match = _.map(match,function(o){
           var flage = _.findIndex(filteredMatchesThoseAreFinished,function(obj){
             var matchOfAllRound = obj.matchId;
             var currentMatchMatch = o.matchId;
             return matchOfAllRound ==  currentMatchMatch;
           });

           if (flage != -1) {

           }else{
             o.t1score = ' '
             o.t2score = ' '
           }
           return o;
         });
         return match;
      });
      var allInOne = {

      };
      var totalSize = _.size(AllMatchesN);


      if (totalSize == 1) {
        allInOne.secondRoundMatches = AllMatchesN[0];
      }else if (totalSize == 2) {
        allInOne.secondRoundMatches = AllMatchesN[0];
        allInOne.thirdRoundMatches = AllMatchesN[1];
      }else if (totalSize == 3) {
        allInOne.secondRoundMatches = AllMatchesN[0];
        allInOne.thirdRoundMatches = AllMatchesN[1];
        allInOne.fourthRoundMatches = AllMatchesN[2];
      }else if (totalSize == 4) {
        allInOne.secondRoundMatches = AllMatchesN[0];
        allInOne.thirdRoundMatches = AllMatchesN[1];
        allInOne.fourthRoundMatches = AllMatchesN[2];
        allInOne.fifthRoundMatches = AllMatchesN[3];
      }
      return allInOne;
      // return AllMatchesN;
    },


  },
  $scope.helper = helper;

  $scope.twoTeam = {
    match: '',
    start: function(competition){
      $scope.twoTeam.match = [];
      helper.INTERVALHANDLE2 = $interval(function () {
        homeService.getScoreOf2teams(competition).then(function(response){
          var notification = response.data[1];
          var matches = response.data[0];

          if (notification[0].isFinished != 'null') {

          }else{
            matches[0].t1score = '';
            matches[0].t2score = '';
          }
          // console.log(matches[0]);

          if ($scope.selectedCompetition.id ==  matches[0].competitionId) {
            $scope.twoTeam.match = matches[0];
          }
        } );
      }, 1000);
    },
  };
  $scope.fourTeam = {
    match: '',
    start: function(competition){

      $scope.fourTeam.match = [];
      helper.INTERVALHANDLE4 = $interval(function () {
        homeService.getScoreOf4teams(competition).then(function(response){

          if ($scope.selectedCompetition.id == response.data.competitionId) {
            var abc = $scope.matchesFilter.getFinishedMatches(response.data.notifications,response.data.matches);
            $scope.fourTeam.match = abc;
          }
        } );
      }, 1000);
    },
  };
  $scope.sixteenTeam = {
    match: '',
    start: function(competition){

      $scope.sixteenTeam.match = [];
      helper.INTERVALHANDLE16 = $interval(function () {
        homeService.getScoreOf16teams(competition).then(function(response){
           var abc = $scope.matchesFilter.getFinishedMatches(response.data.notifications,response.data.matches);
          if ($scope.selectedCompetition.id == response.data.competitionId) {
            $scope.sixteenTeam.match = abc;

          }
        } );
      }, 1000);
    },
  };
  $scope.eightTeam = {
    match: '',
    start: function(competition){

      $scope.eightTeam.match = [];
      helper.INTERVALHANDLE8 = $interval(function () {
        homeService.getScoreOf8teams(competition).then(function(response){
          if ($scope.selectedCompetition.id == response.data.competitionId) {
            var abc = $scope.matchesFilter.getFinishedMatches(response.data.notifications,response.data.matches);
            $scope.eightTeam.match = abc;
            // console.log($scope.eightTeam.match);
          }
        } );
      }, 1000);
    },
  };

  $scope.countDown = 10;
  $scope.competitions;
  $scope.selectedCompetition;
  $scope.matchesOfSelectedCompetition;
  $scope.teamVsMatch = {
      t1name: 'team 1',
      t2name: 'team 2',
      t1score: '60',
      t2score: '120'
  };

  $scope.screen = {
    display2: false,
    display4: false,
    display8: false,
    display16: false,
    displayTable: false,
    displayBlank: false,
    displayTimer: false,
    displayScore: false,
    displayTeamVSscore:false,

    display2View: function(){
      helper.cancleIntervals();
      this.display4 = false;
      this.displayTeamVSscore = false;
      this.displayTimer = false;
      this.displayScore = false;
      this.displayBlank = false;
      this.displayTable = false;
      this.display16 = false;
      this.display8 = false;
      this.display2 = true;
      $scope.twoTeam.start($scope.selectedCompetition);
    },
    display4View: function(){
      helper.cancleIntervals();
      this.display2 = false;
      this.displayTeamVSscore = false;
      this.displayTimer = false;
      this.displayScore = false;
      this.displayBlank = false;
      this.displayTable = false;
      this.display16 = false;
      this.display8 = false;
      this.display4 = true;
      $scope.fourTeam.start($scope.selectedCompetition);
    },
    display8View: function(){
      helper.cancleIntervals();
      $scope.eightTeam.start($scope.selectedCompetition);

      this.display2 = false;
      this.display4 = false;
      this.displayTeamVSscore = false;
      this.displayTimer = false;
      this.displayScore = false;
      this.displayBlank = false;
      this.displayTable = false;
      this.display16 = false;
      this.display8 = true;
    },
    display16View: function(){
      helper.cancleIntervals();
      $scope.sixteenTeam.start($scope.selectedCompetition);
      this.display2 = false;
      this.display4 = false;
      this.displayTeamVSscore = false;
      this.displayTimer = false;
      this.displayScore = false;
      this.displayBlank = false;
      this.displayTable = false;
      this.display8 = false;
      this.display16 = true;

    },
    displayTableView: function(){

      $scope.competitionUpdate($scope.selectedCompetition );

      helper.cancleIntervals();
      this.display2 = false;
      this.display4 = false;
      this.displayTeamVSscore = false;
      this.displayTimer = false;
      this.displayScore = false;
      this.displayBlank = false;
      this.display16 = false;
      this.display8 = false;
      this.displayTable = true;

    },
    displayBlankView: function(){
      helper.cancleIntervals();
      this.display2 = false;
      this.display4 = false;
      this.displayTeamVSscore = false;
      this.displayTimer = false;
      this.displayScore = false;
      this.display16 = false;
      this.display8 = false;
      this.displayTable = false;
      this.displayBlank = true;
    },
    displayTimerView: function(){
      $scope.play();
      helper.cancleIntervals();
      this.display2 = false;
      this.display4 = false;
      this.displayTeamVSscore = false;
      this.displayScore = false;
      this.display16 = false;
      this.display8 = false;
      this.displayTable = false;
      this.displayBlank = false;
      this.displayTimer = true;
    },
    displayScoreView: function(){
      helper.cancleIntervals();
      this.display2 = false;
      this.display4 = false;
      this.displayTeamVSscore = false;
      this.display16 = false;
      this.display8 = false;
      this.displayTable = false;
      this.displayBlank = false;
      this.displayTimer = false;
      this.displayScore = true;
    },
    displayTeamVSscoreView: function(){
      helper.cancleIntervals();

      this.display2 = false;
      this.display4 = false;
      this.display16 = false;
      this.display8 = false;
      this.displayTable = false;
      this.displayBlank = false;
      this.displayTimer = false;
      this.displayScore = false;
      this.displayTeamVSscore = true;

    },
    choiceDisplay: function(){
      var competition = $scope.selectedCompetition;
      if (competition.round == 0) {
        this.displayBlankView();
      }
      if (competition.round == 1) {
        this.displayTableView();
      }
      if (competition.round > 1) {

        if (competition.topTeamChoice == 32) {
          if (competition.round == 2) {
            this.displayTableView();
          }else if (competition.round > 2) {
            this.display16View();
          }
        }
        if (competition.topTeamChoice == 16) {
          this.display16View();
        }
        if (competition.topTeamChoice == 8) {
          this.display8View();
        }
        if (competition.topTeamChoice == 4) {
          this.display4View();
        }
        if (competition.topTeamChoice == 2) {
          this.display2View();
        }
      }


    },
  };


  $scope.competitionUpdate = function(selectedCompetition){
    homeService.getCompetitionList().then(function(response){
      $scope.competitions = response.data;
      var currentCompetitionIndex = _.findIndex($scope.competitions, ['id', selectedCompetition.id]);
      $scope.selectedCompetition = $scope.competitions[currentCompetitionIndex];
      $scope.matchesOfSelectedCompetition = $scope.competitions[currentCompetitionIndex].matches;

    });
  };

  homeService.getCompetitionList().then(function(response){
    $scope.competitions = response.data;
    $scope.selectedCompetition = $scope.competitions[0];
    $scope.matchesOfSelectedCompetition = $scope.competitions[0].matches;

    $scope.screen.choiceDisplay();

    // save competition id so that we can set that competition Again Back
    $scope.detailCompetition.setCompetitionId($scope.selectedCompetition);
  },function(error){ console.log(error); });







  $scope.competitionChanged = function(){
    var currentCompetitionIndex = _.findIndex($scope.competitions, ['id', $scope.selectedCompetition.id]);
    $scope.matchesOfSelectedCompetition = $scope.competitions[currentCompetitionIndex].matches;
    $scope.screen.choiceDisplay();
  };



  // major
  $scope.timeConfig = {
    isReadyToRunAgain: true,
    wait: function(){
      $scope.timeConfig.isReadyToRunAgain = false;
    },
    waitOver: function(){
      $timeout(function(){
        $scope.timeConfig.isReadyToRunAgain = true;
        window.dd('wait is over');
      },4000);
    }
  };
  $scope.notificationInterval = function(){

    var NI = $interval(function(){
      homeService.getNotification($scope.selectedCompetition).then(function(response){

        if (response.data.isStarted.length > 0) {

          window.dd('in started');
          if ($scope.timeConfig.isReadyToRunAgain) {
            $scope.timeConfig.wait();
            window.dd('wait is seet');
            $scope.screen.displayTimerView();
            $scope.CountDownInterval(response.data.isStarted);
            $interval.cancel(NI);
            $scope.timeConfig.waitOver();
          }
        }
        if (response.data.isFinished.length > 0) {
          $scope.screen.displayTimerView();
          $scope.CountDownInterval(response.data.isFinished,3);

          $interval.cancel(NI);
        }

      });
    },1000);
  };



  $scope.CountDownInterval = function(argObj,counter = false){
    if (counter) {
      $scope.countDown = 3;

      homeService.getTeamScore(argObj).then(function(response){

        $scope.teamVsMatch  = {
            t1name: response.data[0].t1name,
            t2name: response.data[0].t2name,
            t1score: response.data[0].t1score,
            t2score: response.data[0].t2score
        };
      });


      var THREECHANDLE = $interval(function(){
        $scope.countDown = $scope.countDown - 1;
        if ($scope.countDown == 0) {
          $scope.screen.displayTeamVSscoreView();
          $timeout(function(){
            $scope.screen.choiceDisplay();
            $scope.notificationInterval();
          },9000);
          // here we will display team vs score screen;
          $interval.cancel(THREECHANDLE);
        }
      },1000);
    }else{
      $scope.countDown = 10;
      var CDIHANDLE = $interval(function(){
        $scope.countDown = $scope.countDown - 1;
        homeService.getStopTimerStatus(argObj).then(function(response){

          if(response.data[0].stopTimer == 1){
            $scope.notificationInterval();
            $scope.screen.choiceDisplay();
            $interval.cancel(CDIHANDLE);
          }
        });
        if ($scope.countDown == 0) {
          $scope.notificationInterval();
          $scope.screen.choiceDisplay();
          $interval.cancel(CDIHANDLE);
        }
      },1000);
    }
  };
  $scope.notificationInterval();





});
