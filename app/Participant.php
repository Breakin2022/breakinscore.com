<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
  protected $table = 'participant';
  public $timestamps = false;
  public function rank(){
    return $this->hasOne('App\PlayersRank','participantId','id');
  }
}
