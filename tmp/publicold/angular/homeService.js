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
