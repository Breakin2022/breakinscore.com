<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeamsMember extends Model
{
    protected $table = "teamsmembers";
    public $timestamps = false;
    public function participant(){
      return $this->hasOne('App\Participant','id','pid');
    }
}
