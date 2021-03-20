<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    public function criterias(){
      return $this->hasMany('App\CriteriaScore','scoreId','id');
    }
    public function judge(){
      return $this->hasOne('App\Judge','id','judgeId');
    }
    public function participant(){
      return $this->hasOne('App\Participant','id','participantId');
    }
}
