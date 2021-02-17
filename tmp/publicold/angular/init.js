var app = angular.module('app',[]);

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
