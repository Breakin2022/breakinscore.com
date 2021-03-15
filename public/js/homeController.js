var app = angular.module('app',[]);
let l = function(a){
  // console.log(a);
  // window.dd(a);
  console.log(arguments);
};
let debug = function(){
  l(arguments);
  // console.debug(arguments);
}
app.service('homeService',function($http){

  return {
    getCompetitionList: function(){
      return $http({
            method : "POST",
            url : "/api/getAllCompetition"
        });
    },
    getNotification: function(competition){
      return $http({
        method: 'POST',
        url: '/api/getNotificationDetails',
        data:{
          competition: competition
        }
      });
    },
    getStopTimerStatus: function(competition){
      return $http({
        method: 'POST',
        url: '/api/getStopTimerStatus',
        data:{
          competition: competition
        }
      });
    },
    getTeamScore: function(competition){
      return $http({
        method: 'POST',
        url: '/api/getTeamScoreA',
        data:{
          competition: competition
        }
      });
    },
    getScoreOf2teams: function(competition){
      return $http({
        method: 'POST',
        url: '/api/getScoreOf2teams',
        data:{
          competition: competition
        }
      });
    },
    getScoreOf4teams: function(competition){
      return $http({
        method: 'POST',
        url: '/api/getScoreOf4teams',
        data:{
          competition: competition
        }
      });
    },
    getScoreOf8teams: function(competition){
      return $http({
        method: 'POST',
        url: '/api/getScoreOf8teams',
        data:{
          competition: competition
        }
      });
    },
    getScoreOf16teams: function(competition){
      return $http({
        method: 'POST',
        url: '/api/getScoreOf16teams',
        data:{
          competition: competition
        }
      });
    },


  };

});


app.service('helper',function($interval, homeService){
  return {
    INTERVALHANDLE2: 0,
    INTERVALHANDLE4: 0,
    INTERVALHANDLE8: 0,
    INTERVALHANDLE16: 0,
    cancleIntervals: function ( ){
      $interval.cancel(this.INTERVALHANDLE2);
      $interval.cancel(this.INTERVALHANDLE4);
      $interval.cancel(this.INTERVALHANDLE8);
      $interval.cancel(this.INTERVALHANDLE16);
    },
  };
});







app.controller('homeController',function($scope,homeService ,$interval,$timeout,helper ){
  $scope.collection8Teams = [];
  $scope.isTimerViewStarted = false;
  $scope.loading = false;
  $scope.showloader = function(){
      $scope.loading = true;
  };
  $scope.hideloader = function(){
      $scope.loading = false;
  };
  $scope.play = function() {
        var audio = new Audio('/public/s.mp3');
        audio.play();
  };
  window.scopeAy = $scope;
  window.scopePlay = $scope.play;
  $scope.sliderConfg = {
    redraw: function(){
      
      $timeout(function(){
        $timeout(function(){
          var swiper = new Swiper('.swiper-container', {
            autoplay: {
              delay: 3000,
            },
            slidesPerView: 3,
            spaceBetween: 10,
            reverseDirection: true,
            disableOnInteraction: false,
            pagination: {
              el: '.swiper-pagination',
              clickable: true,
            },
          });
        },100);
      },100);
    }

  }
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
      l('in getFinishedMatches');
      var filteredMatchesThoseAreFinished  = _.filter(matches,function(o) {
        if (o.isFinished == "null") {
          return false;
        }else{
          return true;
        }
      });

      var AllMatchesN = _.map(allRoundsMatches,function(match){
        // console.log(allRoundsMatches);
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
      debug('inside twoTeam match Start');
      $scope.twoTeam.match = [];
      helper.INTERVALHANDLE2 = $interval(function () {
        l('inside INTERVALHANDLE2');
        homeService.getScoreOf2teams(competition).then(function(response){
          var matches = response.data.matches;
          var notification = response.data.notifications;

          var matchId = matches[0].matchId;

          var isit = _.find( notification ,{ 'matchId':matchId+'' })
          debug('isit value ', isit, 'notification', notification, 'matchId',matchId);
          // debug('isit value ', isit);
          if (typeof isit == "undefined") {
            matches[0].t1score = '';
            matches[0].t2score = '';
          }else{

            if (isit.isFinished == 'null') {
              matches[0].t1score = '';
              matches[0].t2score = '';

            }
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
      t1score: '',
      t2score: ''
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
    displayLoader: false,

    displayLoaderView: function(){
      helper.cancleIntervals();
      this.display4 = false;
      this.displayTeamVSscore = false;
      this.displayTimer = false;
      this.displayScore = false;
      this.displayBlank = false;
      this.displayTable = false;
      this.display16 = false;
      this.display8 = false;
      this.display2 = false;
      this.displayLoader = true;
    },
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
      this.displayLoader = false;
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
      this.displayLoader = false;
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
      this.displayLoader = false;
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
      this.displayLoader = false;
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
      this.displayLoader = false;
      this.displayTable = true;
      $scope.sliderConfg.redraw();

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
      this.displayLoader = false;
      this.displayBlank = true;
    },
    displayTimerView: function(){
      if ($scope.isTimerViewStarted ) {
        l('inside isTimerViewStarted');
        return 0;
      }
      $scope.isTimerViewStarted = true;
      $timeout(function(){
        l('is isTimerViewStarted is now false')
        $scope.isTimerViewStarted = false;
      },2000);
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
      this.displayLoader = false;
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
      this.displayLoader = false;
      this.displayScore = true;
    },
    displayTeamVSscoreView: function(){
      l('inside displayTeamVSscoreView');
      helper.cancleIntervals();
      this.display2 = false;
      this.display4 = false;
      this.display16 = false;
      this.display8 = false;
      this.displayTable = false;
      this.displayBlank = false;
      this.displayTimer = false;
      this.displayScore = false;
      this.displayLoader = false;
      this.displayTeamVSscore = true;
      $scope.sliderConfg.redraw();


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
  window.screen = $scope.screen;

  $scope.competitionUpdate = function(selectedCompetition){
    l('inside competitionUpdate')
    homeService.getCompetitionList().then(function(response){
      $scope.competitions = _.filter(response.data,function(item){
          return item.competition_criterias > 2;
      });
      var currentCompetitionIndex = _.findIndex($scope.competitions, ['id', selectedCompetition.id]);
      $scope.selectedCompetition = $scope.competitions[currentCompetitionIndex];
      $scope.matchesOfSelectedCompetition = $scope.competitions[currentCompetitionIndex].matches;

    });
  };

  homeService.getCompetitionList().then(function(response){
    // competition_criterias

    $scope.competitions = _.filter(response.data,function(item){
        return item.competition_criterias > 2;
    });
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
      l('insdie timeConfig wait method')
      $scope.timeConfig.isReadyToRunAgain = false;
    },
    waitOver: function(){
      $timeout(function(){
        l('inside timeout waitOver');
        $scope.timeConfig.isReadyToRunAgain = true;
      },4000);
    }
  };

  $scope.notificationInterval = function(){

    var NI = $interval(function(){
      // if $scope.timeConfig.isReadyToRunAgain is true then go next else go back ;
      // l('inside NI Interval $scope.timeConfig.isReadyToRunAgain is');
      homeService.getNotification($scope.selectedCompetition).then(function(response){
        if (!$scope.timeConfig.isReadyToRunAgain) {
          return 0;
        }
        if (response.data.isStarted.length > 0) {
            $scope.timeConfig.wait();
            $scope.screen.displayTimerView();
            $scope.CountDownInterval(response.data.isStarted);
            $interval.cancel(NI);
        }
        if (response.data.isFinished.length > 0) {
          debug('event finished ',response.data.isFinished, response,response.data.isFinished.length);
          $scope.screen.displayTimerView();
          $scope.CountDownInterval(response.data.isFinished,3);
          $interval.cancel(NI);
        }

      });
    },1000);
  };

  $scope.timeoutmine = function(){
    $timeout(function(){

      $scope.save_lock = false;
      $scope.timeConfig.waitOver();
      $scope.screen.choiceDisplay();
      $scope.notificationInterval();
    },9000);
  }
  $scope.save_lock = false;
  $scope.CountDownInterval = function(argObj,counter = false){
    if ($scope.save_lock) {
      window.dd('lock is true');
      return 0;
    }else{
      window.dd('lock is again going true');
      $scope.save_lock = true;
    }

    var CDIHANDLE;

    if (counter) {
      $scope.countDown = 3;
      l(argObj);
      $scope.isResponseRecevied = false;
      $scope.isLoadingViewIsLoaded = false;
      homeService.getTeamScore(argObj).then(function(response){
        debug('inside getteamscore ', argObj,'response', response);
        // l('mawais');
        // l(argObj);
        // l(response);
        // l('inside getteamscore');
        $scope.teamVsMatch  = {
            t1name: response.data[0].t1name,
            t2name: response.data[0].t2name,
            t1score: response.data[0].t1score,
            t2score: response.data[0].t2score
        };
        $scope.isResponseRecevied = true;
        if ($scope.isLoadingViewIsLoaded) {
          $scope.screen.displayTeamVSscoreView();
          $scope.timeoutmine();
        }
        debug('... after geting score and assigning values',$scope.teamVsMatch);
      });

      $scope.THREECHANDLE = $interval(function(){
        window.dd($scope.THREECHANDLE)

        $scope.countDown = $scope.countDown - 1;
        if ($scope.countDown == 0) {
          debug('hellow timer is over at that time');
          $interval.cancel($scope.THREECHANDLE);

          if (!$scope.isResponseRecevied) {
            $scope.isLoadingViewIsLoaded = true;
            $scope.screen.displayLoaderView();
          }else {
            $scope.screen.isResponseRecevied = false;
            $scope.screen.displayTeamVSscoreView();
            $scope.timeoutmine();
          }
          l('going to display displayTeamVSscoreView');

          //////herererere
        }
      },1000);
    }else{

      $scope.countDown = 10;
      CDIHANDLE = $interval(function(){
        $scope.countDown = $scope.countDown - 1;
        homeService.getStopTimerStatus(argObj).then(function(response){

          if(response.data[0].stopTimer == 1){
            $scope.save_lock = false;

            $scope.timeConfig.waitOver();
            $scope.notificationInterval();
            $scope.screen.choiceDisplay();
            $interval.cancel(CDIHANDLE);
          }
        });

        if ($scope.countDown == 0) {
          $scope.save_lock = false;

          $scope.timeConfig.waitOver();
          $scope.notificationInterval();
          $scope.screen.choiceDisplay();
          $interval.cancel(CDIHANDLE);
        }
      },1000);
    }
  };
  $scope.notificationInterval();

});
