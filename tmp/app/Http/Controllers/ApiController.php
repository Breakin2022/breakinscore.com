<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Redirect;
use Session;
use App\User;
use Hash;
use Auth;

use App\Helpers\Helpers as Helper;

class ApiController extends Controller
{
  public function matchStatusUpdate(Request $request){
    $competitionId = $request->competitionId;
    $matchId       = $request->matchId;
    $isStartOrEnd  = $request->isStartOrEnd;
    // $competitionId = '15';
    // $matchId = '26';
    // $isStartOrEnd = '1';

    if ($competitionId == null || $matchId == null || is_null($isStartOrEnd) == true) {
      return Helper::makeJsonResponse('One of field is missing', '0');
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
      // $tmp = DB::table('notifications')->where('competitionId', $competitionId)->where('matchId', $matchId)->get();
      $status = DB::table('notifications')->where('competitionId', $competitionId)->where('matchId', $matchId)->update([
        'isFinished'    => date('Y-m-d H:i:s')
      ]);
      // dd($tmp);
      return Helper::makeJsonResponse('Match Finisehd Successfully', '1');
    }
  }
  public function scoreInitialize(Request $request){
    $matchId = $request->matchId;
    // $judgeId = $request->judgeId;

    if ($matchId == null ) {
      return Helper::makeJsonResponse('One of field is missing', '0');
    }
    // if (!DB::table('judges')->where('id', $judgeId)->exists()) {
    //   return Helper::makeJsonResponse('Judge does not exists', '0');
    // }
    if (!DB::table('matches')->where('id', $matchId)->exists()) {
      return Helper::makeJsonResponse('Match does not exists', '0');
    }
    $deleteRecord = DB::table('scores')->where('matchId', $matchId)->delete();
    // $match = DB::table('matches')->where('id', $matchId)->get();
    // $redTeamId = $match[0]->redtid;
    // $blueTeamId = $match[0]->bluetid;

    // $redTeamParticipants = DB::table('teamsmembers')->where('tid', $redTeamId)->get();
    // foreach ($redTeamParticipants as $participant) {
    //
    //   if (DB::table('scores')->where('judgeId', $judgeId)->where('participantId', $participant->pid)->where('matchId', $matchId)->exists()) {
    //     DB::table('scores')->where('judgeId', $judgeId)->where('participantId', $participant->pid)->where('matchId', $matchId)->delete();
    //   }
    //   $save = DB::table('scores')->insert([
    //     'judgeId'        => $judgeId,
    //     'participantId'  => $participant->pid,
    //     'matchId'        => $matchId,
    //     'score'          => 0
    //   ]);
    //
    //
    // }

    // $blueTeamParticipants = DB::table('teamsmembers')->where('tid', $blueTeamId)->get();
    // foreach ($blueTeamParticipants as $participant) {
    //   if (DB::table('scores')->where('judgeId', $judgeId)->where('participantId', $participant->pid)->where('matchId', $matchId)->exists()) {
    //     DB::table('scores')->where('judgeId', $judgeId)->where('participantId', $participant->pid)->where('matchId', $matchId)->delete();
    //   }
    //
    //   $save = DB::table('scores')->insert([
    //     'judgeId'        => $judgeId,
    //     'participantId'  => $participant->pid,
    //     'matchId'        => $matchId,
    //     'score'          => 0
    //   ]);
    // }
    return Helper::makeJsonResponse('Score Reset Successfully', '1','record Deleted', $deleteRecord);
  }


  public function insertScore(Request $request){


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
    // dd($matchId);
    if (!DB::table('matches')->where('id', $matchId)->exists()) {
      return Helper::makeJsonResponse('Match does not exists', '0');
    }

    // if (!($score <= 10 && $score >= 1)) {
    //   return Helper::makeJsonResponse('Score is not in range' ,'0');
    // }

    $matchWithRedNBlueTeam = DB::table('matches')->where('id', $matchId)->get();

    // $test = DB::table('teamsmembers')->where('tid', $);

    // dd($isLast);
    if ($isLast == 1) {
      DB::table('matches')->where('id', $matchId)->update([
        'isFinished' => 1
      ]);
      // dd(DB::table('matches')->where('id', $matchId)->get());
    }
    // dd($score);

    if (DB::table('scores')->where('judgeId', $judgeId)->where('participantId', $participantId)->where('matchId',$matchId)->exists()) {

      $ExistingScore = DB::table('scores')->where('judgeId', $judgeId)->where('participantId', $participantId)->where('matchId',$matchId)->get();
      $newScore = $ExistingScore[0]->score + $score;
      DB::table('scores')->where('judgeId', $judgeId)->where('participantId', $participantId)->where('matchId',$matchId)->update([
        'judgeId'        => $judgeId,
        'participantId'  => $participantId,
        'matchId'        => $matchId,
        'score'          => $newScore
      ]);
      DB::table('matches')->where('id', $matchId)->update([
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      return Helper::makeJsonResponse('Score Updated Successfully', '1');

    }

    $save = DB::table('scores')->insert([
      'judgeId'        => $judgeId,
      'participantId'  => $participantId,
      'matchId'        => $matchId,
      'score'          => $score
    ]);
    DB::table('matches')->where('id', $matchId)->update([
      'updated_at' => date('Y-m-d H:i:s')
    ]);

    if ($save) {
      return Helper::makeJsonResponse('Score Inserted Successfully', '1');
    }else{
      return Helper::makeJsonResponse('score insertion failed', '0');
    }
  }



  public function CompetitionList(Request $reqeust){
    // $time = "1493056693674";
    $time = $reqeust->time;
    // $time = "1492635878260";

    if ($time == null) {
      return Helper::makeJsonResponse('Time is not given' , '0');
    }
    $date = date("Y-m-d", $time / 1000);
    $collection = Array();
    $competitionCollection = Array();
    $matchesCollection = Array();
    $participantCollection = Array();
    $Competitions = DB::table('competitionVenues')->whereDate('start_date', ">=", $date)->get();
    $competitionI = 0;
    $matchI = 0;
    $participantI = 0;
    foreach ($Competitions as $Competition) {

      $newCompeition = Helper::customeResponse($Competition, 'matches', null);
      $countCriteras = DB::table('competition_criterias')->where('competitionid', $Competition->id)->join('criterias', 'criterias.id' ,'=', 'competition_criterias.criteriaid')->count();
      if ($countCriteras < 3) {
        continue;
      }
      $criterias = DB::table('competition_criterias')->where('competitionid', $Competition->id)->join('criterias', 'criterias.id' ,'=', 'competition_criterias.criteriaid')->get();
      $tmpArray = Array();
      foreach ($criterias as $criteria) {
        $tmpArray[] = (object)[
          'id' => $criteria->id,
          'title' => $criteria->title
        ];
      }
      $newCompeition['Criterias'] = $tmpArray;
      $competitionCollection[$competitionI] = $newCompeition;
      $matches = DB::table('matches')->where('competitionId', $Competition->id)->get();
      $matchesCollection = Array();
      $matchI = 0;
      foreach ($matches as $match) {
        $newMatch = Helper::customeResponse($match, 'redTeam', null, 'blueTeam', null);
        $competitionCollection[$competitionI]['matches'][$matchI] = $newMatch;
        $redTeam = DB::table('teams')->where('id', $match->redtid)->get();
        $blueTeam = DB::table('teams')->where('id', $match->bluetid)->get();
        $competitionCollection[$competitionI]['matches'][$matchI]['redTeam'] = Helper::customeResponse($redTeam[0], 'participants');
        $competitionCollection[$competitionI]['matches'][$matchI]['blueTeam'] = Helper::customeResponse($blueTeam[0], 'participants');
        $redTeamParticipants = DB::table('teamsmembers')->where('tid', $redTeam[0]->id)->join('participant', 'participant.id', '=' , 'pid')->get();
        $blueTeamParticipants = DB::table('teamsmembers')->where('tid', $blueTeam[0]->id)->join('participant', 'participant.id', '=' , 'pid')->get();
        $competitionCollection[$competitionI]['matches'][$matchI]['redTeam']['participants'] = $redTeamParticipants;
        $competitionCollection[$competitionI]['matches'][$matchI]['blueTeam']['participants'] = $blueTeamParticipants;
        $matchI++;
      }
      $competitionI++;
    }
    $collection = [
      "Competitions" => $competitionCollection
    ];
    // dd($collection);
    return json_encode($collection);
  }

  public function CompetitionListtest(Request $reqeust){
    $time = $reqeust->time;
    // $time = "1492635878260";

    if ($time == null) {
      return Helper::makeJsonResponse('Time is not given' , '0');
    }
    $date = date("Y-m-d", $time / 1000);
    $collection = Array();
    $competitionCollection = Array();
    $matchesCollection = Array();
    $participantCollection = Array();
    $Competitions = DB::table('competitionVenues')->whereDate('start_date', ">=", $date)->get();
    $competitionI = 0;
    $matchI = 0;
    $participantI = 0;
    foreach ($Competitions as $Competition) {

      $newCompeition = Helper::customeResponse($Competition, 'matches', null);
      $countCriteras = DB::table('competition_criterias')->where('competitionid', $Competition->id)->join('criterias', 'criterias.id' ,'=', 'competition_criterias.criteriaid')->count();
      if ($countCriteras < 3) {
        continue;
      }
      $criterias = DB::table('competition_criterias')->where('competitionid', $Competition->id)->join('criterias', 'criterias.id' ,'=', 'competition_criterias.criteriaid')->get();
      $tmpArray = Array();
      foreach ($criterias as $criteria) {
        $tmpArray[] = (object)[
          'id' => $criteria->id,
          'title' => $criteria->title
        ];
      }
      $newCompeition['Criterias'] = $tmpArray;
      $competitionCollection[$competitionI] = $newCompeition;
      $matches = DB::table('matches')->where('competitionId', $Competition->id)->get();
      $matchesCollection = Array();
      foreach ($matches as $match) {
        $newMatch = Helper::customeResponse($match, 'redTeam', null, 'blueTeam', null);
        $competitionCollection[$competitionI]['matches'][$matchI] = $newMatch;
        $redTeam = DB::table('teams')->where('id', $match->redtid)->get();
        $blueTeam = DB::table('teams')->where('id', $match->bluetid)->get();
        $competitionCollection[$competitionI]['matches'][$matchI]['redTeam'] = Helper::customeResponse($redTeam[0], 'participants');
        $competitionCollection[$competitionI]['matches'][$matchI]['blueTeam'] = Helper::customeResponse($blueTeam[0], 'participants');
        $redTeamParticipants = DB::table('teamsmembers')->where('tid', $redTeam[0]->id)->join('participant', 'participant.id', '=' , 'pid')->get();
        $blueTeamParticipants = DB::table('teamsmembers')->where('tid', $blueTeam[0]->id)->join('participant', 'participant.id', '=' , 'pid')->get();
        $competitionCollection[$competitionI]['matches'][$matchI]['redTeam']['participants'] = $redTeamParticipants;
        $competitionCollection[$competitionI]['matches'][$matchI]['blueTeam']['participants'] = $blueTeamParticipants;
        $matchI++;
      }
      $competitionI++;
    }
    $collection = [
      "Competitions" => $competitionCollection
    ];
    return json_encode($collection);
  }

  public function register(Request $request){
    $name = $request->input('name');
    $email = $request->input('email');
    // $password = $request->input('password');
    if ($name == null || $email == null) {
      return Helper::makeJsonResponse('One of field is missing', '0');
    }
    $name = Helper::validate($name);
    if (!Helper::isValiedEmail($email)) {
        return Helper::makeJsonResponse('Email is not valied', '0');
    }
    // $password = Helper::validate($password);
    // $password = Hash::make($password);
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
    // dd($result);
    return Helper::makeJsonResponse('Success', '1');

  }

  public function login(Request $request){
    // return 'yes';
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











    public function test(Request $request){
      // $match1 = 17;
      // $match2 = 18;

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
      // dd($matchId);
      if (!DB::table('matches')->where('id', $matchId)->exists()) {
        return Helper::makeJsonResponse('Match does not exists', '0');
      }

      // if (!($score <= 10 && $score >= 1)) {
      //   return Helper::makeJsonResponse('Score is not in range' ,'0');
      // }

      $matchWithRedNBlueTeam = DB::table('matches')->where('id', $matchId)->get();

      // dd($matchWithRedNBlueTeam[0]);
      $test = DB::table('teamsmembers')->where('tid', $matchWithRedNBlueTeam[0]->redtid)->orWhere('tid', $matchWithRedNBlueTeam[0]->bluetid)->where('pid', $participantId)->exists();
      if (!$test) {
        return Helper::makeJsonResponse('Participant does not belongs to match', '0');
      }
      dd($test);
      if ($isLast) {
        DB::table('matches')->where('id', $matchId)->update([
          'isFinished' => 1
        ]);
      }
      if (DB::table('scores')->where('judgeId', $judgeId)->where('participantId', $participantId)->where('matchId',$matchId)->exists()) {
        DB::table('scores')->where('judgeId', $judgeId)->where('participantId', $participantId)->where('matchId',$matchId)->update([
          'judgeId'        => $judgeId,
          'participantId'  => $participantId,
          'matchId'        => $matchId,
          'score'          => $score
        ]);
        DB::table('matches')->where('id', $matchId)->update([
          'updated_at' => date('Y-m-d H:i:s')
        ]);
        return Helper::makeJsonResponse('Score Updated Successfully', '1');
      }

      $save = DB::table('scores')->insert([
        'judgeId'        => $judgeId,
        'participantId'  => $participantId,
        'matchId'        => $matchId,
        'score'          => $score
      ]);
      DB::table('matches')->where('id', $matchId)->update([
        'updated_at' => date('Y-m-d H:i:s')
      ]);

      if ($save) {
        return Helper::makeJsonResponse('Score Inserted Successfully', '1');
      }else{
        return Helper::makeJsonResponse('score insertion failed', '0');
      }
    }








}
