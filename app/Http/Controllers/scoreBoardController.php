<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use DateTime;
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

use App\Helpers\Helpers as Helper;

class scoreBoardController extends Controller
{
    public function getScoreOf8teams(Request $request)
    {
      $competition = (object)$request->competition ;
      $competitionId = $competition->id;
      $compeition = DB::table('competitionVenues')->where('id','=',$competitionId)->first();
      $matches = helper::getTeamsWithIdAndRoundFor8Teams($competitionId,$compeition);
      return [
        'matches'=>$matches,
        'competitionId'=>$competitionId,
        'notifications'=>helper::notificationData($competitionId)
      ];
    }
    public function getScoreOf16teams(Request $request){
      $competition = (object)$request->competition ;
      $competitionId = $competition->id;

      $compeition = DB::table('competitionVenues')->where('id','=',$competitionId)->first();
      $matches = helper::getTeamsWithIdAndRoundFor16Teams($competitionId,$compeition);
      return [
        'matches'=>$matches,
        'competitionId'=>$competitionId,
        'notifications'=>helper::notificationData($competitionId)
      ];
    }
    public function getScoreOf4teams(Request $request){
      $competition = (object)$request->competition ;
      $competitionId = $competition->id;
      $topChoice = $competition->topTeamChoice;

      $compeition = DB::table('competitionVenues')->where('id','=',$competitionId)->first();

      $matches = helper::getTeamsWithIdAndRoundForFourTeams($competitionId,$compeition);

      return [
        'matches'=>$matches,
        'competitionId'=>$competitionId,
        'notifications'=>helper::notificationData($competitionId)
      ];
    }
    public function getScoreOf2teams(Request $request){
      $competition = (object)$request->competition ;

      $competitionId = $competition->id;
      $topChoice = $competition->topTeamChoice;
      $compeition = DB::table('competitionVenues')->where('id','=',$competitionId)->first();
      $competition->winnerTeamId = $compeition->winnerTeamId;
      if ($competition->winnerTeamId != null) {
        $matches = helper::getTeamsWithIdAndRoundForTwoTeams($competitionId,$competition->winnerTeamId );
      }else{
        $matches = helper::getTeamsWithIdAndRoundForTwoTeams($competitionId );
      }
      $matches->notifications = helper::notificationData($competitionId);
      $notifications = helper::notificationData($competitionId);
      return [
        'matches'=>$matches,
        'competitionId'=>$competitionId,
        'notifications'=> $notifications
      ];

    }
    public function getdisplay16ViewUpdate(Request $request){
      $competition = (object)$request->competition ;
      $competitionId = $competition->id;
      $topChoice = $competition->topTeamChoice;
      $competitionLatestUpdated = DB::Table('competitionVenues')->where('id','=',$competitionId)->first();
      if ($topChoice == 32) {
        $matchesOf3rdRound = Helper::getMatchesAndThereScoreWithName($competitionId,3);
        return [
          'matchesOf3rdRound'=>$matchesOf3rdRound
        ];
      }
    }

    public function getTeamScore(Request $request){
      $competition = (object)$request->competition[0];
      $competitionId = $competition->competitionId;
      $matchId = $competition->matchId;

      $match = DB::table('matches')->where('matches.id','=',$matchId)
      ->leftJoin('teams as t1',function($join){
        $join->on('matches.firstTeam','=','t1.id');
      })
      ->leftjoin('teams as t2',function($join){
        $join->on('matches.secondTeam','=','t2.id');
      })
      ->select('matches.id','t1.name as t1name','t2.name as t2name','t1.id as t1id','t2.id as t2id' )
      ->get();

      $match->map(function($match)use($competitionId,$matchId){
        $t1Score = Score::where('competitionId','=',$competitionId)->where('teamId','=',$match->t1id)->where('matchId','=',$matchId)->get();
        $t1Score->map(function($score){
          $score->score = $score->criterias->sum('score')/$score->criterias->count();
          return $score;
        });
        $match->t1score = round( $t1Score->sum('score')/$t1Score->count() , 2);

        $t2Score = Score::where('competitionId','=',$competitionId)->where('teamId','=',$match->t2id)->where('matchId','=',$matchId)->get();
        $t2Score = $t2Score->map(function($score){
          $score->score = $score->criterias->sum('score')/$score->criterias->count();
          return $score;
        });
        $match->t2score = round( $t2Score->sum('score')/$t2Score->count() , 2);
        return $match;
      });

      return $match;
    }


    public function getStopTimerStatus(Request $request){
      $competition = (object)$request->competition[0];
      $competitionId = $competition->competitionId;
      $matchId = $competition->matchId;
      $startTime = $competition->isStarted;
      $notification = DB::table('notifications')->where('competitionId','=',$competitionId)->where('matchId','=',$matchId)->where('isStarted','=',$startTime)->first();
      return [
        $notification
      ];
    }
    public function getNotificationDetails(Request $request){
      if (!isset($request->competition['id'])) {
        return [
          'isStarted'=> [] ,
          'isFinished'=>[]
        ];
      }
      $competitionId    = $request->competition['id'];
      $competitionRound = $request->competition['round'];
      $now = date('Y-m-d H:i:s');

      $isStarted = DB::select("select * ,timestampdiff(second,isStarted, '$now' ) as timediff from notifications where timestampdiff(second,isStarted, '$now' ) <= 2 and competitionId = $competitionId and isFinished = 'null'");
      $isFinished = DB::select("select * ,timestampdiff(second,isFinished, '$now' ) as timediff from notifications where timestampdiff(second,isFinished, '$now' ) <= 2 and competitionId = $competitionId");

      $currentRound = DB::table('competitionVenues')->where('id','=',$competitionId)->first();

      return [
        'isStarted'=>$isStarted,
        'isFinished'=>$isFinished,
        'currentRound'=>$currentRound->round
      ];
    }
    public function getSpecficRoundTeamsScore(Request $request){
      $competition = (object)$request->competition  ;
      $roundwanted = $request->roundwanted;

      $competitions = DB::table('competitionVenues')->where('id','=',$competition->id )->get();
      $competitions = $competitions->map(function($competition)use($roundwanted){
        $competition->needTable = true;

        $competition->matches = Helper::getMatchesScoreForSpecifcRound($competition,$roundwanted);
        $competition->needTable = true;

        return $competition;
      });

      return $competitions;
    }
    public function getCompetition(){
      $dateCurrent = new DateTime(); 
      $dateOld = new DateTime();
      date_sub($dateOld, date_interval_create_from_date_string("1 days"));
      $dateCurrent = date_format($dateCurrent, "Y-m-d");
      $dateOld = date_format($dateOld, "Y-m-d");

      $competitions = DB::table('competitionVenues')->whereDate('start_date', '=', $dateOld)->orWhere('start_date', '=', $dateCurrent)->orderBy('id','desc')->get();

      $competitions = $competitions->map(function($competition){
        $competition->matches = Helper::getMatchesScore($competition);
        $competition->matches = $competition->matches->sortBy('id');
        // dd($competition);
        $competition->competition_criterias   = DB::table('competition_criterias')->where('competitionid', $competition->id)->join('criterias', 'criterias.id' ,'=', 'competition_criterias.criteriaid')->count();

        return $competition;
      });

      return $competitions;
    }
}
