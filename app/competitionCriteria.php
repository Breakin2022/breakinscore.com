<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class competitionCriteria extends Model
{
    public $timestamps = false;

    public function criteria(){
      return $this->hasOne('App\Criteria','id','criteriaid');
    }
}
