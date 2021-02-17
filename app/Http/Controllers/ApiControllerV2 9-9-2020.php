<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Redirect;
use Session;
use App\User;
use Hash;
use Auth;
use App\CriteriaScore;

use App\Helpers\Helpers as Helper;

class ApiControllerV2 extends Controller
{
  public function matchStatusUpdate(Request $request){
    $competitionId = $request->competitionId;
    $matchId       = $request->matchId;
    $isStartOrEnd  = $request->isStartOrEnd;


    if ($competitionId == null || $matchId == null || is_null($isStartOrEnd) == true) {
      return Helper::makeJsonResponse('One of field is missing', '0');
    }
    $competition = DB::table('competitionVenues')->where('id',$competitionId)->first();

    if ($competition == null) {
      return Helper::makeJsonResponse('Competition does not exists', '0');
    }
    if ($isStartOrEnd == 1) {
      if (DB::table('notifications')->where('competitionId', $competitionId)->where('matchId', $matchId)->exists()) {
        return Helper::makeJsonResponse('Match already Started', '2');
      }
      DB::table('notifications')->insert([
        'competitionId' => $competitionId,
        'matchId'       => $matchId,
        'isStarted'     => date('Y-m-d H:i:s'),
        'isFinished'    => 'null'
      ]);
      return Helper::makeJsonResponse('Match Started Successfully', '1');
    }elseif ($isStartOrEnd == 0) {
      //first we will decide which team is winner
      //we have match id so we will get teams and and then palyers score
      //there we will have the winner
      $notification = DB::table('notifications')->where('competitionId', $competitionId)->where('matchId', $matchId)->first();

      $isTeamHasSameScore = 0;
      if ($notification != null) {
        if ($notification->isFinished == "null" || $notification->isTie == 1 ) {
          if (!$competition->isTrial) {
            $isTeamHasSameScore = helper::updateWinnerTeamAndPlayersAndScore($competitionId,$matchId);
          }
        }
        // dd($notification);
      }
      // dd($notification);
      //let calculate players Rank
      // dd($matches);
      // dd($abc,'eee');
      $status = DB::table('notifications')->where('competitionId', $competitionId)->where('matchId', $matchId)->update([
        'isFinished'    => date('Y-m-d H:i:s')
      ]);
      if ($isTeamHasSameScore == 2) {
        DB::table('notifications')->where('competitionId', $competitionId)->where('matchId', $matchId)->update([
          'isTie'    => 1
        ]);
      }elseif ($isTeamHasSameScore == 3) {
        DB::table('notifications')->where('competitionId', $competitionId)->where('matchId', $matchId)->update([
          'isTie'    => 3
        ]);
      }else {
        DB::table('notifications')->where('competitionId', $competitionId)->where('matchId', $matchId)->update([
          'isTie'    => 0
        ]);
      }

      return Helper::makeJsonResponse('Match finisehd successfully', '1');
    }
  }
  public function scoreInitialize(Request $request){
    $matchId = $request->matchId;

    if ($matchId == null ) {
      return Helper::makeJsonResponse('One of field is missing', '0');
    }


    if (!DB::table('matches')->where('id', $matchId)->exists()) {
      return Helper::makeJsonResponse('Match does not exists', '0');
    }
    $recordGoingtoDelete = DB::table('scores')->where('matchId', $matchId)->get();
    $scoreIds = $recordGoingtoDelete->pluck('id')->toArray();
    CriteriaScore::whereIn('scoreId',$scoreIds)->delete();
    $deleteRecord = DB::table('scores')->where('matchId', $matchId)->delete();


    return Helper::makeJsonResponse('Score Reset Successfully', '1','record Deleted', $deleteRecord);
  }

  public function deductScore(Request $request){
    $currentTime = date('Y-m-d H:i:s');
    $judgeId        = $request->judgeId;
    $participantId  = $request->participantId;
    $matchId        = $request->matchId;
    $score          = $request->score;
    $isLast         = $request->isLast;
    if ($judgeId == null || $participantId == null || $matchId == null || $score == null) {
      return Helper::makeJsonResponse('One of field is missing', '0');
    }

    if (!DB::table('judges')->where('id', $judgeId)->exists()) {
      return Helper::makeJsonResponse('Judge does not exists', '0');
    }
    if (!DB::table('participant')->where('id', $participantId)->exists()) {
      return Helper::makeJsonResponse('Participant does not exists', '0');
    }


    if (!DB::table('matches')->where('id', $matchId)->exists()) {
      return Helper::makeJsonResponse('Match does not exists', '0');
    }


    $scoreA = DB::table('scores')->where('judgeId','=',$judgeId)->where('participantId','=',$participantId)
    ->where('matchId','=',$matchId)->first();
    if ($scoreA->score < $score) {
      return helper::makeJsonResponse('Deduction score can not be greater than existing score','0');
    }
    DB::table('scores')->where('judgeId','=',$judgeId)->where('participantId','=',$participantId)
    ->where('matchId','=',$matchId)->update([
      'score'=> $scoreA->score - $score
    ]);

    return Helper::makeJsonResponse('Score deduction Successfull', '1');
  }
  public function insertScore(Request $request){
    $currentTime = date('Y-m-d H:i:s');
    $judgeId        = $request->judgeId;
    $participantId  = $request->participantId;
    $matchId        = $request->matchId;
    $score          = $request->score;
    $isLast         = $request->isLast;
    // $scoreArray = json_decode($score);
    $scoreArray = $score;
    // dd($scoreArray);
    // die('');
    if ($judgeId == null || $participantId == null || $matchId == null || $score == null) {
      return Helper::makeJsonResponse('One of field is missing', '0');
    }

    if (!DB::table('judges')->where('id', $judgeId)->exists()) {
      return Helper::makeJsonResponse('Judge does not exists', '0');
    }
    if (!DB::table('participant')->where('id', $participantId)->exists()) {
      return Helper::makeJsonResponse('Participant does not exists', '0');
    }


    if (!DB::table('matches')->where('id', $matchId)->exists()) {
      return Helper::makeJsonResponse('Match does not exists', '0');
    }


    $match = DB::table('matches')->where('id','=',$matchId)->first();
    $competitionId = $match->competitionId;
    $matchRound = $match->roundNo;
    $firstTeamId = $match->firstTeam;
    $secondTeamId = $match->secondTeam;

    $teams = DB::table('teams')->whereIn('id',[$firstTeamId,$secondTeamId])->get();

    if (DB::table('teamsmembers')->where('pid','=',$participantId)->where('tid','=',$firstTeamId)->exists()) {
      $teamsWhichHavethisParticipent =  $firstTeamId;
    }elseif (DB::table('teamsmembers')->where('pid','=',$participantId)->where('tid','=',$secondTeamId)->exists()) {
      $teamsWhichHavethisParticipent =  $secondTeamId;
    }


    if ($isLast == 1) {
      DB::table('matches')->where('id', $matchId)->update([
        'isFinished' => 1
      ]);
    }
    //new logic here
    $lastRound = DB::table('scores')->select('matchRoundNumber')->where('judgeId',$judgeId)->where('participantId',$participantId)->where('matchId',$matchId)
    ->where('teamId',$teamsWhichHavethisParticipent)->where('competitionId',$competitionId)
    ->where('roundNo',$matchRound)->orderBy('matchRoundNumber','desc')->first();
    $matchRoundNumber = 0;
    if ($lastRound == null) {
      $matchRoundNumber = 1;
    }else {
      $firstCriteriaScore = $scoreArray[0];
      $firstCriteriaScoreObj = json_decode($firstCriteriaScore);

      if ($firstCriteriaScoreObj->score < 0) {
        $matchRoundNumber = $lastRound->matchRoundNumber;
      }else {
        $matchRoundNumber = $lastRound->matchRoundNumber + 1;
      }
    }
    $scoreId = DB::table('scores')->insertGetId([
      'judgeId'        => $judgeId,
      'participantId'  => $participantId,
      'matchId'        => $matchId,
      'score'          => 0,
      'teamId'         => $teamsWhichHavethisParticipent,
      'competitionId'  => $competitionId,
      'roundNo'        => $matchRound,
      'matchRoundNumber'=> $matchRoundNumber
    ]);
    $criteriaScore = [];
    foreach ($scoreArray as $obj) {
      $obj = json_decode($obj);
      $criteriaScore[] = [
        'scoreId'        => $scoreId,
        'score'  => $obj->score,
        'criteriaId'        => $obj->id,
      ];
    }

    DB::table('criteriaScore')->insert($criteriaScore);
    if ($scoreId) {
      return Helper::makeJsonResponse('Score Inserted Successfully', '1');
    }else{
      return Helper::makeJsonResponse('score insertion failed', '0');
    }

  }
  public function insertMyScore(Request $request){
    $currentTime = date('Y-m-d H:i:s');
    $judgeId        = $request->judgeId;
    $participantId  = $request->participantId;
    $matchId        = $request->matchId;
    $score          = $request->score;
    $isLast         = $request->isLast;
    // $scoreArray = json_decode($score);
    $scoreArray = $score;
    // dd($scoreArray);
    // die('');
    // dd($score);
    if ($judgeId == null || $participantId == null || $matchId == null || $score == null) {
      return Helper::makeJsonResponse('One of field is missing', '0');
    }

    if (!DB::table('judges')->where('id', $judgeId)->exists()) {
      return Helper::makeJsonResponse('Judge does not exists', '0');
    }
    if (!DB::table('participant')->where('id', $participantId)->exists()) {
      return Helper::makeJsonResponse('Participant does not exists', '0');
    }


    if (!DB::table('matches')->where('id', $matchId)->exists()) {
      return Helper::makeJsonResponse('Match does not exists', '0');
    }


    $match = DB::table('matches')->where('id','=',$matchId)->first();
    $competitionId = $match->competitionId;
    $matchRound = $match->roundNo;
    $firstTeamId = $match->firstTeam;
    $secondTeamId = $match->secondTeam;

    $teams = DB::table('teams')->whereIn('id',[$firstTeamId,$secondTeamId])->get();

    if (DB::table('teamsmembers')->where('pid','=',$participantId)->where('tid','=',$firstTeamId)->exists()) {
      $teamsWhichHavethisParticipent =  $firstTeamId;
    }elseif (DB::table('teamsmembers')->where('pid','=',$participantId)->where('tid','=',$secondTeamId)->exists()) {
      $teamsWhichHavethisParticipent =  $secondTeamId;
    }


    if ($isLast == 1) {
      DB::table('matches')->where('id', $matchId)->update([
        'isFinished' => 1
      ]);
    }
    //new logic here
    $lastRound = DB::table('scores')->select('matchRoundNumber')->where('judgeId',$judgeId)->where('participantId',$participantId)->where('matchId',$matchId)
    ->where('teamId',$teamsWhichHavethisParticipent)->where('competitionId',$competitionId)
    ->where('roundNo',$matchRound)->orderBy('matchRoundNumber','desc')->first();
    $matchRoundNumber = 0;
    if ($lastRound == null) {
      $matchRoundNumber = 1;
    }else {
      // $firstCriteriaScore = $scoreArray[0];
      $firstCriteriaScoreObj = json_decode($firstCriteriaScore);

      if ($firstCriteriaScoreObj->score < 0) {
        $matchRoundNumber = $lastRound->matchRoundNumber;
      }else {
        $matchRoundNumber = $lastRound->matchRoundNumber + 1;
      }
    }
    $scoreId = DB::table('scores')->insertGetId([
      'judgeId'        => $judgeId,
      'participantId'  => $participantId,
      'matchId'        => $matchId,
      'score'          => 0,
      'teamId'         => $teamsWhichHavethisParticipent,
      'competitionId'  => $competitionId,
      'roundNo'        => $matchRound,
      'matchRoundNumber'=> $matchRoundNumber
    ]);
    $criteriaScore = [];
    foreach ($scoreArray as $obj) {
      $obj = json_decode($obj);
      $criteriaScore[] = [
        'scoreId'        => $scoreId,
        'score'  => $obj->score,
        'criteriaId'        => $obj->id,
      ];
    }

    DB::table('criteriaScore')->insert($criteriaScore);
    if ($scoreId) {
      return Helper::makeJsonResponse('Score Inserted Successfully', '1');
    }else{
      return Helper::makeJsonResponse('score insertion failed', '0');
    }

  }


  public function CompetitionList(Request $request){
    $judgeId = $request->judgeId;
    if ($judgeId == null) {
            return Helper::makeJsonResponse('judgeId is missing.', '0');
    }
    $time = $request->time;
    if ($time == null) {
      return Helper::makeJsonResponse('Time is not given' , '0');
    }

    $date = date("Y-m-d", $time / 1000);
    $competitions = DB::table('competitionVenues')->whereDate('start_date', ">=", $date)->get();
    $competitions = $competitions->map(function($competition)use($judgeId){
      $countCriteras = DB::table('competition_criterias')->where('competitionid', $competition->id)->join('criterias', 'criterias.id' ,'=', 'competition_criterias.criteriaid')->count();

      // dd($countCriteras);
      if ($countCriteras < 3) {
        return;
      }
      $criterias = DB::table('competition_criterias')->where('competitionid', $competition->id)->join('criterias', 'criterias.id' ,'=', 'competition_criterias.criteriaid')->get();

      $matches = DB::table('matches')->where('competitionId','=',$competition->id)->where('roundNo','=',$competition->round)->orderBy('id')->get();
      $matches = $matches->map(function($match)use($judgeId,$competition){
        $teamOne = DB::table('teams')->where('id','=',$match->firstTeam)->first();
        $teamTwo = DB::table('teams')->where('id','=',$match->secondTeam)->first();
        $match->teamOne = $teamOne;
        $match->teamTwo = $teamTwo;

        // DB::raw("( select matchRoundNumber from scores where judgeId = $judgeId and participantId = pid and matchId = $match->id and teamId = $match->firstTeam and competitionId = $competition->id and roundNo = $competition->round) as matchRoundNumber ")
        $teamOne->participants = DB::table('teamsmembers')
        ->select('teamsmembers.*', 'participant.*',
DB::raw("(select matchRoundNumber from scores where judgeId = $judgeId and participantId = pid and matchId = $match->id and teamId = $match->firstTeam and competitionId = $competition->id and roundNo = $competition->round order By matchRoundNumber DESC limit 1) as matchRoundNumber")
        )->where('tid', $teamOne->id)->join('participant', 'participant.id', '=' , 'pid')->get();

        $teamTwo->participants = DB::table('teamsmembers')
        ->select('teamsmembers.*', 'participant.*',
DB::raw("( select matchRoundNumber from scores where judgeId = $judgeId and participantId = pid and matchId = $match->id and teamId = $match->secondTeam and competitionId = $competition->id and roundNo = $competition->round  order By matchRoundNumber DESC limit 1) as matchRoundNumber ")

        )->where('tid', $teamTwo->id)->join('participant', 'participant.id', '=' , 'pid')->get();

        $teamOne->participants->map(function($obj){
          if ($obj->matchRoundNumber == null) {
            $obj->matchRoundNumber = 1;
          }
          return $obj;
        });
        $teamTwo->participants->map(function($obj){
          if ($obj->matchRoundNumber == null) {
            $obj->matchRoundNumber = 1;
          }
          return $obj;
        });

        return $match;
      });

      $competition->criterias = $criterias;
      $competition->matches = $matches;

      return $competition;
    });
    $competitions = $competitions->filter(function($competition){
      if ($competition == null) {
        return false;
      }else{
        return true;
      }

    });
    $arryColletion = [];
    foreach ($competitions as $competition) {
      $arryColletion[] = $competition;
    }
    // dd($arryColletion);
    return json_encode($arryColletion);
  }



  public function register(Request $request){
    $name = $request->input('name');
    $email = $request->input('email');
    if ($name == null || $email == null) {
      return Helper::makeJsonResponse('One of field is missing', '0');
    }
    $name = Helper::validate($name);
    if (!Helper::isValiedEmail($email)) {
        return Helper::makeJsonResponse('Email is not valied', '0');
    }
    $body = '
    <!DOCTYPE html>
    <html>
    <head>
    </head>
    <body><div class="row">
     <div class="col-md-12">
     	<div class="panel panel-default">
          <div class="panel-heading">
        <h3 class="panel-title">New Judge Registration</h3>
      </div>
      <table style="padding: 20px">
      	<tr>
      		<th>Name:</th>
      		<td>'. $name .'</td>
      	</tr>
      	<tr>
      		<th>Email</th>
      		<td>'. $email .'</td>
      	</tr>
      </table>
      <div class="panel-footer">

      </div>
      </div></div></div></body></html>';


    $headers = "Content-Type: text/html; charset=UTF-8\r\n";

    mail("thegr8awais@gmail.com","New Judge Registration",$body, $headers);
    return Helper::makeJsonResponse('Success', '1');
  }

  public function login(Request $request){
    $email = $request->input('email');
    $password = $request->input('password');

    $countUserName = User::where('username', $email)->count();
    $countEmail = User::where('email', $email)->count();

    // checking we have user with email


    if ($countUserName == 0 && $countEmail == 0) {
      return Helper::makeJsonResponse('These credentials do not match our records.', '0');
    }


    if ($countEmail > 0) {
        // return 'yes';
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
          $user = User::where('email', $email)->first();

          $id    = $user->id;
          $name  = $user->name;
          $email = $user->email;

          $userObject = Helper::makeObject('id', $id, 'name', $name, 'email', $email);
          return Helper::makeJsonResponse('Login Successfully', '1', 'user', $userObject);
        }else{
          return Helper::makeJsonResponse('These credentials do not match our records.', '0');
        }
    }
    if ($countUserName > 0) {
      $user =  User::where('username', $email)->first();
      $DBpassword = $user->password;
      $result = Hash::check($password, $DBpassword);
      if ($result) {
        $id    = $user->id;
        $name  = $user->name;
        $email = $user->email;

        $userObject = Helper::makeObject('id', $id, 'name', $name, 'email', $email);
        Auth::login($user);
        return Helper::makeJsonResponse('Login Successfully', '1', 'user', $userObject);

      }else{

        return Helper::makeJsonResponse('These credentials do not match our records.', '0');
      }
    }

  }
  public function SponsorsList(){
    return $sponsors = DB::table('sponsors')->get();
  }

    public function stopMatchStartTimer(Request $request){
      $competitionId = $request->competitionId;
      $matchId       = $request->matchId;
      DB::table('notifications')->where('competitionId','=',$competitionId)->where('matchId','=',$matchId)->update([
        'stopTimer'=> 1
      ]);
      return Helper::makeJsonResponse('Bit updated Successfully', '1');
    }




}
