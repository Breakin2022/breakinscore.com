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
}
