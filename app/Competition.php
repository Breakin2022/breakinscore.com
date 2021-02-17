<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
  protected $table = 'competitionVenues';
  public $timestamps = false;

  public function criterias(){
    return $this->hasMany('App\competitionCriteria','competitionid','id');
  }
}
