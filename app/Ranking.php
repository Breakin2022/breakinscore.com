<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ranking extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'competitionId', 'competitionType', 'matchId', 'teamId', 'teamRank', 'teamAgeGroup', 'participantId', 'participantRank', 'participantAgeGroup'
    ];

    public function competition(){
        return $this->hasOne('App\Competition','id','competitionId');
    }
    public function match(){
        return $this->hasOne('App\Match','id','matchId');
    }
    public function team(){
        return $this->hasOne('App\Team','id','teamId');
    }
    public function participant(){
        return $this->hasOne('App\Participant','id','participantId');
    }
}
