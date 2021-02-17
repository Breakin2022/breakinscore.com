<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
  public static function teamByColor($color){
    $array = Array();
    $teams = team::where('color', $color)->get();
    foreach ($teams as $team) {
      $array[] = (Object)[
        'label' => $team->name,
        'value' => $team->id
      ];
    }
    return json_encode($array);
  }
  public function members(){
    return $this->hasMany('App\TeamsMember','tid','id');
  }
  public function rank(){
    return $this->hasOne('App\TeamsRank','teamId','id');
  }

}
