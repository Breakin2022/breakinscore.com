<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
  public function teamOne(){
    return $this->hasOne('App\Team','id','firstTeam');
  }
  public function teamTwo(){
    return $this->hasOne('App\Team','id','secondTeam');
  }
}
