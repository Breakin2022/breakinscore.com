<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CriteriaScore extends Model
{
  protected $table = 'criteriaScore';
  public $timestamps = false;
  public function criteria(){
    return $this->hasOne('App\Criteria','id','criteriaId');
  }
}
