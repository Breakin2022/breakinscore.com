<?php

namespace App\Helpers;

use Blade;
use DB;
use App\User;
use App\Competition;
use App\competitionCriteria;
use App\Criteria;
use App\CriteriaScore;
use App\Judge;
use App\Match;
use App\Participant;
use App\Score;
use App\Sponsor;
use App\Team;
use App\TeamsMember;
use App\TeamsRank;
use App\Ranking;

class Helpers
{
    /**
     * getSumScore($participantData)
     */
    public static function getSumScore($participantData)
    {
        $participantData = array_values($participantData);
        $totalScore = 0;
        foreach ($participantData as $key => $value) {
            $totalScore += array_sum($value);
        };
        return $totalScore;
    }

    /**
     * getParticipantCriteriaScore($dataObj)
     */
    public static function getParticipantCriteriaScore($dataObj)
    {
        $scoreNCriterias = [];

        $obj = new \stdClass;
        $obj->totalRounds = Score::where('participantId', $dataObj->team1Member1Id)->where('competitionId', $dataObj->competitionId)->max('matchRoundNumber');

        for ($i=1; $i <= $obj->totalRounds; $i++) {
            $member1Score = self::scoreForRound($dataObj->competitionId, $dataObj->team1Member1Id, $dataObj->currentRound, $i);
            dd($member1Score);
            // $scoreNCriterias[$criteria->criteria->title] =
        }

        dd($obj);
    }

    /**
     * getParticipantScoreByRound($competitionId, $participantId, $round)
     */
    public static function getParticipantScoreByRound($competitionId, $participantId, $round)
    {
        $participantScore = Score::where('competitionId', $competitionId)->where('participantId', $participantId)->where('roundNo', $round)->get();
        return $participantScore;
    }

    /**
     * * updateWinnerTeamAndPlayersAndScore($competitionId, $matchId)
     * ? Related Functions
     * ! decreaseTeamAndPlayerRank($teamMembers, $teamId)
     * ! increaseTeamAndPlayerRank($teamMembers, $teamId, $howMuchIncrement = 2)
     * * Helper Fucntions
     * getAgeGroupOfTeamMember($teamId)
     * getAgeGroup($participantId)
     */
    public static function updateWinnerTeamAndPlayersAndScore($competitionId, $matchId)
    {
        $competition = DB::table('competitionVenues')->where('id', $competitionId)->first();
        $matches     = DB::table('matches')->where('id', $matchId)->first();

        $competition->matches       = $matches;
        $matches->firstTeamMembers  = DB::Table('teamsmembers')->where('tid', $matches->firstTeam)->get();
        $matches->secondTeamMembers = DB::Table('teamsmembers')->where('tid', $matches->secondTeam)->get();
        
        $matches->firstTeamMembers = $matches->firstTeamMembers->map(function ($teamMembers) use ($competition,$matchId) {
            
            /* Test Get scores
            echo "competition->id: ".$competition->id. " teamMembers->tid: " .$teamMembers->tid . " matchId: " . $matchId . " competition->round: " . $competition->round; 
            */
            $idofScore = DB::table('scores')->where('competitionId', $competition->id)->where('teamId', $teamMembers->tid)->where('matchId', $matchId)->where('roundNo', $competition->round)->get();
            $idofScore = $idofScore->pluck('id');
            $criteriaScore = DB::table('criteriaScore')->whereIn('scoreId', $idofScore)->get();
            $teamMembers->totalScore = $criteriaScore->sum('score');
            $teamMembers->score = $criteriaScore;
            return $teamMembers;    
        });
        
        $matches->secondTeamMembers = $matches->secondTeamMembers->map(function ($teamMembers) use ($competition,$matchId) {
            $idofScore = DB::table('scores')->where('competitionId', $competition->id)->where('teamId', $teamMembers->tid)->where('matchId', $matchId)->where('roundNo', $competition->round)->get();
            $idofScore = $idofScore->pluck('id');
            $criteriaScore = DB::table('criteriaScore')->whereIn('scoreId', $idofScore)->get();
            $teamMembers->totalScore = $criteriaScore->sum('score');
            $teamMembers->score = $criteriaScore;
            return $teamMembers;
        });

        $firstTeamScore = $matches->firstTeamMembers->sum('totalScore');
        $secondTeamScore = $matches->secondTeamMembers->sum('totalScore');

        $other_details = array(
            'competitionId'   => $competitionId,
            'competitionType' => $competition->type,
            'matchId'         => $matchId
        );

        if ($competition->round == 1 && $firstTeamScore == $secondTeamScore) {
            self::increaseTeamAndPlayerRank($matches->firstTeamMembers, $matches->firstTeam, 1, $other_details);
            self::increaseTeamAndPlayerRank($matches->secondTeamMembers, $matches->secondTeam, 1, $other_details);
            return 3;
        }
        if ($firstTeamScore == $secondTeamScore) {
            return 2;
        }
        if ($firstTeamScore > $secondTeamScore) {
            self::increaseTeamAndPlayerRank($matches->firstTeamMembers, $matches->firstTeam, 2, $other_details);
            self::decreaseTeamAndPlayerRank($matches->secondTeamMembers, $matches->secondTeam, $other_details);
        } else {
            self::increaseTeamAndPlayerRank($matches->secondTeamMembers, $matches->secondTeam, 2, $other_details);
            self::decreaseTeamAndPlayerRank($matches->firstTeamMembers, $matches->firstTeam, $other_details);
        }

        return 1;
    }

    protected static function insert_ranking($other_details, $teamId, $teamNewRank, $teamMemberAgeGroup, $pid, $playerNewRank, $ageGroup){
        $competitionId       = $other_details['competitionId'];
        $competitionType     = $other_details['competitionType'];
        $matchId             = $other_details['matchId'];
        $teamId              = $teamId;
        $teamRank            = $teamNewRank;
        $teamAgeGroup        = $teamMemberAgeGroup;
        $participantId       = $pid;
        $participantRank     = $playerNewRank;
        $participantAgeGroup = $ageGroup;
        
        DB::table('rankings')->insert(
            [
              'competitionId'       => $competitionId,
              'competitionType'     => $competitionType,
              'matchId'             => $matchId,
              'teamId'              => $teamId,
              'teamRank'            => $teamRank,
              'teamAgeGroup'        => $teamAgeGroup,
              'participantId'       => $participantId,
              'participantRank'     => $participantRank,
              'participantAgeGroup' => $participantAgeGroup,
              "created_at"          => date('Y-m-d H:i:s'),
              "updated_at"          => date('Y-m-d H:i:s'),
            ]
        );
    }

    protected static function decreaseTeamAndPlayerRank($teamMembers, $teamId, $other_details)
    {
        $teamMemberAgeGroup = self::getAgeGroupOfTeamMember($teamId);
        $teamRank           = DB::Table('teamsRanks')->where('teamId', $teamId)->first();
        $teamNewRank        = $playerNewRank = 0;
        if ($teamRank != null) {
            if($teamRank->rank > 0){
                $teamNewRank = intval($teamRank->rank) - 1;
                DB::Table('teamsRanks')->where('teamId', $teamId)->update(['rank' => $teamNewRank]);
            }
            if($teamRank->ageGroup != $teamMemberAgeGroup){
                DB::Table('teamsRanks')->where('teamId', $teamId)->update(['ageGroup' => $teamMemberAgeGroup]);
            }
        }
        foreach ($teamMembers as $teammember) {
            $pid = $teammember->pid;
            $ageGroup = self::getAgeGroup($pid);
            $playersRank = DB::table('playersRank')->where('participantId', $pid)->first();
            if ($playersRank != null) {
                if($playersRank->rank > 0){
                    $playerNewRank = intval($playersRank->rank) - 1;
                    DB::Table('playersRank')->where('participantId', $pid)->update(['rank' => $playerNewRank]);
                }
                if($playersRank->ageGroup != $ageGroup){
                    DB::Table('playersRank')->where('participantId', $pid)->update(['ageGroup' => $ageGroup]);
                }
            }
            if( ($teamRank != null) && ($playersRank != null) ){
                self::insert_ranking($other_details, $teamId, $teamNewRank, $teamMemberAgeGroup, $pid, $playerNewRank, $ageGroup);   
            }
        }
    }

    protected static function increaseTeamAndPlayerRank($teamMembers, $teamId, $howMuchIncrement = 2, $other_details){
        $teamMemberAgeGroup = self::getAgeGroupOfTeamMember($teamId);
        $teamNewRank        = $playerNewRank = 2;
        $teamRank           = DB::Table('teamsRanks')->where('teamId', $teamId)->first();
        if ( $teamRank != null ) {
            $teamNewRank = intval($teamRank->rank) + $howMuchIncrement;
            DB::Table('teamsRanks')->where('teamId', $teamId)->update(['rank' => $teamNewRank]);
            
            if($teamRank->ageGroup != $teamMemberAgeGroup){
                DB::Table('teamsRanks')->where('teamId', $teamId)->update(['ageGroup' => $teamMemberAgeGroup]);
            }
        } else {
            DB::Table('teamsRanks')->insert([
                'teamId'=>$teamId,
                'rank'=>$howMuchIncrement,
                'ageGroup'=>$teamMemberAgeGroup
              ]);
        }
        foreach ($teamMembers as $teammember) {
            $pid         = $teammember->pid;
            $ageGroup    = self::getAgeGroup($pid);
            $playersRank = DB::table('playersRank')->where('participantId', $pid)->first();
            if ($playersRank == null) {
                DB::table('playersRank')->insert([
                  'participantId'=>$pid,
                  'rank'=>$howMuchIncrement,
                  'ageGroup'=>$ageGroup
                ]);
            }else{
                $playerNewRank = intval($playersRank->rank) + $howMuchIncrement;
                DB::Table('playersRank')->where('participantId', $pid)->update(['rank' => $playerNewRank]);
                $playerNewRank = $playersRank->rank;
                if($playersRank->ageGroup != $ageGroup){
                    DB::Table('playersRank')->where('participantId', $pid)->update(['ageGroup' => $ageGroup]);
                }
            }
            
            if( ($teamRank != null) && ($playersRank != null) ){
                self::insert_ranking($other_details, $teamId, $teamNewRank, $teamMemberAgeGroup, $pid, $playerNewRank, $ageGroup);   
            }
        }
    }

    /*
    there 3 age groups
    between 5 year to 12
    between 13 to 18
    greater than 18 i.e 18++;
    */
    protected static function getAgeGroupOfTeamMember($teamId)
    {
        $teamsMember = DB::table('teamsmembers')->where('tid', $teamId)->first();
        return self::getAgeGroup($teamsMember->pid);
    }

    protected static function getAgeGroup($participantId)
    {
        $participant = Participant::where('id', $participantId)->first();
        if ($participant->dob == null || $participant->dob == "") {
            return 0;
        }
        $age = \Carbon\Carbon::parse($participant->dob)->age;
        if ($age >= 5 && $age < 13) {
            return 1;
        } elseif ($age >= 13 && $age < 18) {
            return 2;
        } else {
            return 3;
        }
    }
    /**updateWinnerTeamAndPlayersAndScore($competitionId, $matchId) END Completly */

    public static function notificationData($competitionId)
    {
        return DB::table('notifications')->where('competitionId', '=', $competitionId)->get();
    }
    public static function d($data)
    {
        var_export($data);
        die();
    }
    public static function getTeamsWithIdAndRoundFor8Teams($competitionId, $compeition = null)
    {
        $object = (object)[];
        if ($compeition->round > 1) {
            $matches = self::getTeamsNamesAndIdN($competitionId, 2);
            $object->secondRoundMatches = self::getMatchesScoreN($matches, $competitionId);
        }
        if ($compeition->round > 2) {
            $matches = self::getTeamsNamesAndIdN($competitionId, 3);
            $object->thirdRoundMatches = self::getMatchesScoreN($matches, $competitionId);
        }
        if ($compeition->round > 2) {
            $matches = self::getTeamsNamesAndIdN($competitionId, 3);
            $object->thirdRoundMatches = self::getMatchesScoreN($matches, $competitionId);
        }
        if ($compeition->round > 3) {
            $matches = self::getTeamsNamesAndIdN($competitionId, 4);
            $object->fourthRoundMatches = self::getMatchesScoreN($matches, $competitionId, $compeition);
        }

        return $object ;
    }
    public static function getTeamsWithIdAndRoundFor16Teams($competitionId, $compeition = null)
    {
        $object = (object)[];
        if ($compeition->round > 1) {
            $matches = self::getTeamsNamesAndIdN($competitionId, 2);
            $object->secondRoundMatches = self::getMatchesScoreN($matches, $competitionId);
        }
        if ($compeition->round > 2) {
            $matches = self::getTeamsNamesAndIdN($competitionId, 3);
            $object->thirdRoundMatches = self::getMatchesScoreN($matches, $competitionId);
        }
        if ($compeition->round > 2) {
            $matches = self::getTeamsNamesAndIdN($competitionId, 3);
            $object->thirdRoundMatches = self::getMatchesScoreN($matches, $competitionId);
        }
        if ($compeition->round > 3) {
            $matches = self::getTeamsNamesAndIdN($competitionId, 4);
            $object->fourthRoundMatches = self::getMatchesScoreN($matches, $competitionId);
        }
        if ($compeition->round > 4) {
            $matches = self::getTeamsNamesAndIdN($competitionId, 5);
            $object->fifthRoundMatches = self::getMatchesScoreN($matches, $competitionId, $compeition);
        }
        return $object ;
    }
    public static function getTeamsNamesAndIdN($competitionId, $round)
    {
        $matches = DB::table('matches')->where('matches.competitionId', '=', $competitionId)->where('matches.roundNo', '=', $round)
      ->leftJoin('teams as t1', function ($join) {
          $join->on('matches.firstTeam', '=', 't1.id');
      })
      ->leftJoin('teams as t2', function ($join) {
          $join->on('matches.secondTeam', '=', 't2.id');
      })->select('matches.competitionId as competitionId', 'matches.firstTeam as t1id', 'matches.secondTeam as t2id', 't1.name as t1name', 't2.name as t2name', 'matches.roundNo as roundNo', 'matches.id as matchId', 'matches.matchSort')
      ->orderBy('matches.matchSort')->get();
        $matches = $matches->map(function ($obj) {
            $obj->t1name = substr($obj->t1name, 0, 17);
            $obj->t2name = substr($obj->t2name, 0, 17);
            return $obj;
        });
        return $matches;
    }
    public static function getMatchesScoreN($matches, $competitionId, $compeition = null)
    {
        $matches = $matches->map(function ($match) use ($competitionId,$compeition) {
            // $match->t1score = DB::table('scores')->where('competitionId','=',$match->competitionId)->where('roundNo','=',$match->roundNo)->where('teamId','=',$match->t1id)->where('matchId','=',$match->matchId)->sum('score');
            $t1Score = Score::where('competitionId', '=', $match->competitionId)->where('roundNo', '=', $match->roundNo)->where('teamId', '=', $match->t1id)->where('matchId', '=', $match->matchId)->get();
            $match->t1score = $t1Score->map(function ($score) {
                $score->score = $score->criterias->sum('score');
                return $score;
            })->sum('score');
            $t2Score = Score::where('competitionId', '=', $match->competitionId)->where('roundNo', '=', $match->roundNo)->where('teamId', '=', $match->t2id)->where('matchId', '=', $match->matchId)->get();
            $match->t2score = $t2Score->map(function ($score) {
                $score->score = $score->criterias->sum('score');
                return $score;
            })->sum('score');

            if ($compeition != null) {
                if ($compeition->winnerTeamId != null) {
                    $match->winner = (DB::table('teams')->where('id', '=', $compeition->winnerTeamId)->select('name')->first())->name;
                    // $match->winnerScore = DB::table('scores')->where('competitionId','=',$competitionId)->where('matchId','=',$match->matchId)->where('teamId','=',$compeition->winnerTeamId)->sum('score');
                    $scoreS = Score::where('competitionId', '=', $competitionId)->where('matchId', '=', $match->matchId)->where('teamId', '=', $compeition->winnerTeamId)->get();
                    $scoreS = $scoreS->map(function ($score) {
                        $score->score = $score->criterias->sum('score');
                        return $score;
                    });
                    $match->winnerScore = $scoreS->sum('score');
                }
            }
            return $match;
        });
        return $matches;
    }
    public static function getTeamsWithIdAndRoundForFourTeams($competitionId, $compeition = null)
    {
        $collection = (object)[];

        if ($compeition->round > 1) {
            $matches = self::getTeamsNamesAndIdN($competitionId, 2);
            $collection->secondRoundMatches = self::getMatchesScoreN($matches, $competitionId);
        }
        if ($compeition->round > 2) {
            $matches = self::getTeamsNamesAndIdN($competitionId, 3);

            $collection->thirdRoundMatches = self::getMatchesScoreN($matches, $competitionId, $compeition);
        }

        return $collection ;
    }
    public static function getTeamsWithIdAndRoundForTwoTeams($competitionId, $winnerId = null)
    {
        $matches = DB::table('matches')->where('matches.competitionId', '=', $competitionId)->where('matches.roundNo', '=', 2)
      ->leftJoin('teams as t1', function ($join) {
          $join->on('matches.firstTeam', '=', 't1.id');
      })
      ->leftJoin('teams as t2', function ($join) {
          $join->on('matches.secondTeam', '=', 't2.id');
      })->select('matches.competitionId as competitionId', 'matches.firstTeam as t1id', 'matches.secondTeam as t2id', 't1.name as t1name', 't2.name as t2name', 'matches.roundNo as roundNo', 'matches.id as matchId')
      ->get();

        // $t2score = Score::where('competitionId', '=', $competition->id)->where('roundNo', '=', $competition->round)->where('teamId', '=', $match->secondTeam)->get();
        // $t2score = $t2score->map(function ($score) {
        //     $score->score = $score->criterias->sum('score');
        //     return $score;
        // });
        //
        // $match->t2score = $t2score->sum('score');
        $matches = $matches->map(function ($match) use ($competitionId,$winnerId) {
            $t1score = Score::where('competitionId', '=', $match->competitionId)->where('roundNo', '=', $match->roundNo)->where('teamId', '=', $match->t1id)->where('matchId', '=', $match->matchId)->get();
            $t1score = $t1score->map(function ($score) {
                $score->score = $score->criterias->sum('score');
                return $score;
            });
            $t2score = Score::where('competitionId', '=', $match->competitionId)->where('roundNo', '=', $match->roundNo)->where('teamId', '=', $match->t2id)->where('matchId', '=', $match->matchId)->get();
            $t2score = $t2score->map(function ($score) {
                $score->score = $score->criterias->sum('score');
                return $score;
            });
            $match->t1score = $t1score->sum('score');
            $match->t2score = $t2score->sum('score');
            if ($winnerId != null) {
                $match->winner = (DB::table('teams')->where('id', '=', $winnerId)->select('name')->first())->name;
                $winnerScore = Score::where('competitionId', '=', $competitionId)->where('matchId', '=', $match->matchId)->where('teamId', '=', $winnerId)->get();
                $winnerScore = $winnerScore->map(function ($score) {
                    $score->score = $score->criterias->sum('score');
                    return $score;
                });

                $match->winnerScore = $winnerScore->sum('score');
            }
            return $match;
        });



        return $matches;
    }


    public static function getTeamNameScore()
    {
        DB::table('scores')->where('competitionId', '=', $match->competitionId)->where('matchId', '=', $match->matchId)->where('roundNo', '=', $match->roundNo)->where('teamId', '=', $match->t1id)->sum('score');
    }
    public static function getMatchesIdsOfRoundnCompetition($competitionId, $round, $match)
    {
        $matches = DB::Table('matches')->select('firstTeam', 'secondTeam')->where('competitionId', '=', $competitionId)->where('roundNo', '=', $round)->get() ;
        $firstTeamsIds = $matches->pluck('firstTeam');
        $secondTeamsIds = $matches->pluck('secondTeam');
        $allIds = $firstTeamsIds->merge($secondTeamsIds);
        // $allIds = $allIds->where
        $arr = $allIds->toArray();

        $firstIndex = array_search($match->t1id, $arr);
        $secondIndex = array_search($match->t2id, $arr);
        if ($firstIndex != false) {
            return $arr[$firstIndex] ;
        } else {
            return $arr[$secondIndex] ;
        }
    }



    public static function getMatchesAndThereScoreWithName($competitionId, $round)
    {
        $matchesOf3rdRound = self::getTeamsWithIdAndRound($competitionId, $round);

        $currentRound = DB::Table('competitionVenues')->where('id', '=', $competitionId)->pluck('round')->first();

        $matchesOf3rdRound = $matchesOf3rdRound->map(function ($match) use ($competitionId,$round,$currentRound) {
            $match->t1score = DB::table('scores')->where('competitionId', '=', $match->competitionId)->where('matchId', '=', $match->matchId)->where('roundNo', '=', $match->roundNo)->where('teamId', '=', $match->t1id)->sum('score');
            $match->t2score = DB::table('scores')->where('competitionId', '=', $match->competitionId)->where('matchId', '=', $match->matchId)->where('roundNo', '=', $match->roundNo)->where('teamId', '=', $match->t2id)->sum('score');


            $match->competitionCurrentRound = $currentRound;

            // let's get for fourth round
            if ($match->competitionCurrentRound > 4) {
                $winnerId = self::getMatchesIdsOfRoundnCompetition($competitionId, $match->competitionCurrentRound, $match);
                $teamScoreAndName = self::getTeamScoreAndName($competitionId);
                dd();
                // $winnerId
          // $match->fourthRound
            }
            $match->competitionCurrentRound;


            // let's get for 5th round

            // 6th round

            // 7th round

            $match->fourthRound;



            $match->round = $round;

            dd($match);

            return $match;
        });
        return $matchesOf3rdRound;
    }
    public static function getMatchesScore($competition)
    {
        $matches = DB::table('matches')->where('matches.competitionId', '=', $competition->id)->where('matches.roundNo', '=', $competition->round)->leftJoin('teams as t1', function ($join) {
            $join->on('matches.firstTeam', '=', 't1.id');
        })->leftJoin('teams as t2', function ($join) {
            $join->on('matches.secondTeam', '=', 't2.id');
        })->select('matches.*', 't1.name as t1name', 't2.name  as t2name')->get();
        $matches->map(function ($match) use ($competition) {
            $t1score = Score::where('competitionId', '=', $competition->id)->where('roundNo', '=', $competition->round)->where('teamId', '=', $match->firstTeam)->get();
            $t1score = $t1score->map(function ($score) {
                $score->score = $score->criterias->sum('score');
                return $score;
            });
            $match->t1score = $t1score->sum('score');
            $t2score = Score::where('competitionId', '=', $competition->id)->where('roundNo', '=', $competition->round)->where('teamId', '=', $match->secondTeam)->get();
            $t2score = $t2score->map(function ($score) {
                $score->score = $score->criterias->sum('score');
                return $score;
            });

            $match->t2score = $t2score->sum('score');
            return $match;
        });
        return $matches;
    }
    public static function getMatchesScoreForSpecifcRound($competition, $round)
    {
        $matches = DB::table('matches')->where('matches.competitionId', '=', $competition->id)->where('matches.roundNo', '=', $round)->leftJoin('teams as t1', function ($join) {
            $join->on('matches.firstTeam', '=', 't1.id');
        })->leftJoin('teams as t2', function ($join) {
            $join->on('matches.secondTeam', '=', 't2.id');
        })->select('matches.*', 't1.name as t1name', 't2.name  as t2name')->get();


        $matches->map(function ($match) use ($competition,$round) {
            $match->t1score = DB::table('scores')->where('competitionId', '=', $competition->id)->where('roundNo', '=', $round)->where('teamId', '=', $match->firstTeam)->sum('score');
            $match->t2score = DB::table('scores')->where('competitionId', '=', $competition->id)->where('roundNo', '=', $round)->where('teamId', '=', $match->secondTeam)->sum('score');
            return $match;
        });
        return $matches;
    }
    public static function getFillerTeam()
    {
        $team1Name = 'Team Filler One';
        $team2Name = 'Team Filler Two';

        $participant1Name = 'First Filler';
        $participant2Name = 'Second Filler';

        $teamFillerOne = DB::table('teams')->where('name', '=', $team1Name)->count();
        $teamFillerTwo = DB::table('teams')->where('name', '=', $team2Name)->count();

        if ($teamFillerOne == 0) {
            DB::table('teams')->insert([
          'name'=>$team1Name,
          'join_date'=>'2099-01-09'
        ]);
        }
        if ($teamFillerTwo == 0) {
            DB::table('teams')->insert([
          'name'=>$team2Name,
          'join_date'=>'2099-01-09'
        ]);
        }

        $personFillerOne  = DB::table('participant')->where('name', '=', $participant1Name)->count();
        $personFillerTwo  = DB::table('participant')->where('name', '=', $participant2Name)->count();

        if ($personFillerOne == 0) {
            DB::table('participant')->insert([
          'name' => $participant1Name,
          'dob' => '1993-10-02'
        ]);
        }
        if ($personFillerTwo  == 0) {
            DB::table('participant')->insert([
          'name' => $participant2Name,
          'dob' => '2006-10-02'
        ]);
        }
        $personFillerOne  = DB::table('participant')->where('name', '=', $participant1Name)->first();
        $personFillerTwo  = DB::table('participant')->where('name', '=', $participant2Name)->first();

        $teamFillerOne = DB::table('teams')->where('name', '=', $team1Name)->get();
        $teamFillerTwo = DB::table('teams')->where('name', '=', $team2Name)->get();

        $teamFillerOne = $teamFillerOne->map(function ($obj) use ($personFillerOne) {
            $teamsMembers = DB::table('teamsmembers')->where('tid', '=', $obj->id)->get();


            if ($teamsMembers->count() > 0) {
                $pid = $teamsMembers[0]->pid;
                if (!DB::table('participant')->where('id', '=', $pid)->exists()) {
                    DB::table('teamsmembers')->where('tid', '=', $obj->id)->update([
               'pid'=>$personFillerOne->id
             ]);
                }
            } else {
                DB::table('teamsmembers')->insert([
             'pid'=>$personFillerOne->id,
             'tid'=>$obj->id
           ]);
            }
            //team have two particpant let's delete 2nd one
            if ($teamsMembers->count() > 1) {
                $teamsMemberS = DB::table('teamsmembers')->where('tid', '=', $obj->id)->where('pid', '!=', $personFillerOne->id)->delete();
            }
            return $obj;
        });
        $teamFillerTwo = $teamFillerTwo->map(function ($obj) use ($personFillerOne,$personFillerTwo) {
            $teamsMembers = DB::table('teamsmembers')->where('tid', '=', $obj->id)->get();
            $needToInsertAgain = false;
            if ($teamsMembers->count() > 1) {
                $teamsMembers[0]->pid;
                $teamsMembers[1]->pid;
                if (!DB::table('participant')->where('id', '=', $teamsMembers[0]->pid)->exists()) {
                    $needToInsertAgain = true;
                }
                if (!DB::table('participant')->where('id', '=', $teamsMembers[1]->pid)->exists()) {
                    $needToInsertAgain = true;
                }
            }

            if ($needToInsertAgain || $teamsMembers->count() < 2) {
                DB::table('teamsmembers')->where('tid', '=', $obj->id)->delete();
                DB::table('teamsmembers')->insert([
             'tid'=>$obj->id,
             'pid'=>$personFillerOne->id
           ]);
                DB::table('teamsmembers')->insert([
             'tid'=>$obj->id,
             'pid'=>$personFillerTwo->id
           ]);
            }
            return $obj;
        });




        return [ $teamFillerOne->first() , $teamFillerTwo->first()];
    }
    public static function getRedTeamScore($id, $matchId)
    {
        $TotalScore = 0;
        // $connection = $GLOBALS['connection'];
        // $query = "SELECT * FROM tony_db.teamsmembers where tid = $id";
        $teamsmembers = DB::table('teamsmembers')->where('tid', '=', $id)->get();
        foreach ($teamsmembers as $teamsmember) {
            // dd($teamsmember->pid);
            $pid = $teamsmember->pid;
            $sumScore = DB::select("SELECT sum(score) as sumscore FROM scores where participantId = $pid and matchId = $matchId");
            // var_dump();
            // dd($sumScore[0]->sumscore);
            $TotalScore += $sumScore[0]->sumscore;
            // dd($sumScore);
        }
        // $result = mysqli_query($connection, $query);
        // $TotalScore = 0;
        // while ($row = mysqli_fetch_assoc($result)) {
        //   $pid = $row['pid'];
        //   $query = "SELECT sum(score) FROM tony_db.scores where participantId = $pid and matchId = $matchId";
        //   $newResult = mysqli_query($connection, $query);
        //   $sumScore = mysqli_fetch_row($newResult);
        //   $TotalScore += $sumScore[0];
        // // }
        // dd($TotalScore);
        return $TotalScore;
    }
    public static function getTeamTitleById($redTeamId)
    {
        // mydump($redTeamId );
        // var_dump($redTeamId);
        if (DB::table('teams')->where('id', $redTeamId)->exists()) {
            $team = DB::table('teams')->where('id', $redTeamId)->get();
            return $team[0]->name;
        } else {
            return 'null';
        }
    }
    public static function mydump($thing)
    {
        echo "<hr>new<hr>";
        echo "<br><pre>";
        var_export($thing);
        echo "<br></pre>";
    }
    public static function getTeamTitle($teamId)
    {
        $team = DB::table('teams')->where('id', '=', $teamId)->get();
        // dd($team->name);
        return $team[0]->name;
    }
    public static function getTeamScore($teamId, $matchId)
    {
        $teamsMembers = DB::table('teamsmembers')->where('tid', $teamId)->get();
        $scoreSum = 0;
        foreach ($teamsMembers as $teamsMember) {
            $query = "SELECT sum(score) as sumscore FROM scores where participantId = $teamsMember->pid and matchId = $matchId";
            $scores = DB::select($query);
            // dd($scores[0]->sumscore);
            $scoreSum = $scoreSum + $scores[0]->sumscore;
        }
        // echo "coming";
        return $scoreSum;
        // $scores = DB::table('scores')->where('matchId', '=', $matchId)->get();
    }








    public static function collectionToArray($Collection)
    {
        $array = array();

        foreach ($Collection as $key => $value) {
            $array[$key] = $value;
        }

        return $array;
    }
    public static function customeResponse($Collection, $f1 = null, $fC1 = null, $f2 = null, $fC2 = null, $f3 = null, $fC3 = null, $f4 = null, $fC4 = null)
    {
        $array = array();
        $Collection = json_decode(json_encode($Collection), true);
        foreach ($Collection as $key => $value) {
            $array[$key] = $value;
        }
        $array[$f1] = $fC1;
        if ($f2 != null) {
            $array[$f2] = $fC2;
        }
        if ($f3 != null) {
            $array[$f3] = $fC3;
        }
        if ($f4 != null) {
            $array[$f4] = $fC4;
        }
        return (array) $array;
    }
    public static function isValiedEmail(string $email)
    {
        $email = self::validate($email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            return true;
        } else {
            return false;
        }
    }
    public static function validate($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public static function makeJsonResponse(string $message, string $status, $fieldName = null, $addtional = null)
    {
        if ($fieldName == null) {
            $Object = (Object)[
            'status'  => $status,
            'message' => $message,
          ];
        } else {
            $Object = (Object)[
            'status'  => $status,
            'message' => $message,
            $fieldName => $addtional
          ];
        }
        return json_encode($Object);
    }
    public static function makeObject(
        $fieldName1 = null,
        $param1 = null,
        $fieldName2 = null,
        $param2 = null,
                                      $fieldName3 = null,
        $param3 = null,
        $fieldName4 = null,
        $param4 = null,
                                      $fieldName5 = null,
        $param5 = null
    ) {
        if ($fieldName1 != null && $fieldName2 != null && $fieldName3 != null && $fieldName4 != null && $fieldName5 != null) {
            return (Object)[
            $fieldName1 => $param1,
            $fieldName2 => $param2,
            $fieldName3 => $param3,
            $fieldName4 => $param4,
            $fieldName5 => $param5
          ];
        }
        if ($fieldName1 != null && $fieldName2 != null && $fieldName3 != null && $fieldName4 != null) {
            return (Object)[
            $fieldName1 => $param1,
            $fieldName2 => $param2,
            $fieldName3 => $param3,
            $fieldName4 => $param4
          ];
        }
        if ($fieldName1 != null && $fieldName2 != null && $fieldName3 != null) {
            return (Object)[
            $fieldName1 => $param1,
            $fieldName2 => $param2,
            $fieldName3 => $param3
          ];
        }
        if ($fieldName1 != null && $fieldName2 != null) {
            return (Object)[
            $fieldName1 => $param1,
            $fieldName2 => $param2
          ];
        }
        if ($fieldName1 != null) {
            return (Object)[
            $fieldName1 => $param1
          ];
        }
    }
    public static function getTopTeams($competitionId, $currentRound, $count)
    {
        $allCompetitionMatches = Match::where('competitionId', '=', $competitionId)
          ->where('roundNo', '=', $currentRound)->get();
        $firstTeams = $allCompetitionMatches->pluck('firstTeam');
        $secondTeams = $allCompetitionMatches->pluck('secondTeam');
        $allTeams = $firstTeams->merge($secondTeams);


        $allTeams = $allTeams->map(function ($teamId) use ($currentRound,$competitionId) {
            $team = new \stdClass;
            $team->teamId = $teamId;
            $score = Score::where('roundNo', '=', $currentRound)
            ->where('competitionId', '=', $competitionId)
                          ->where('teamId', '=', $teamId)->get();
            $score = $score->map(function ($score) {
                $score->score = $score->criterias->sum('score');
                return $score;
            });

            $team->score = $score->sum('score');
            return $team;
        });

        $scores = $allTeams->sortByDesc('score');

        $scores = $scores->take($count);
        $scores->allTeams = $allTeams->sortByDesc('score');

        return $scores;
    }
}
