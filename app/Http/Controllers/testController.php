<?php

namespace App\Http\Controllers;



use Illuminate\Http\Request;
use DB;
use App\Helpers\Helpers as Helper;

class testController extends Controller
{
  public function testingnow(){

    // $totalMatchesNScore = DB::table('matches')->leftJoin('scores','matches.id','=','scores.matchId')
    // ->select(DB::raw( 'matches.id as matchId, matches.firstTeam ,matches.secondTeam ,sum(scores.score) as TotalScore'))
    // ->groupBy('matches.id')->get();
    // dd($totalMatchesNScore);
    //
    //
    //
    // dd('e');



// //   teamssss start

    // $teams = [];
    // for ($i=80; $i <= 280; $i++) {
    //
    //   $teams[] = [
    //     'name'=> 'team '.$i,
    //     'join_date'=>'2018-01-17'
    //   ];
    // }
    // // // // dd($teams);
    // DB::table('teams')->insert($teams);
    // // dd('team Inserted');



    // $participantss = [];
    // for ($i=1; $i <= 170; $i++) {
    //   $participantss[] = [
    //     'name' => "person " . $i,
    //     'email' => "mailno".$i."@mail.com",
    //     'phone'=> "0321758850".$i
    //   ];
    // }
    // //
    // DB::table('participant')->insert($participantss);
    // dd('participents and teams added');
    // // add teams and participents end here


    // 
    // $teamMembers = [];
    // $j = 1;
    // $k = 1;
    // for ($i=65; $i <=  180; $i++) {
    //   $pid1 = $j;
    //   $pid2 = $j + 1;
    //
    //   $teamMembers[] = [
    //     'pid'=>$pid1,
    //     'tid'=>$i
    //   ];
    //   $teamMembers[] = [
    //     'pid'=>$pid2,
    //     'tid'=>$i
    //   ];
    //
    //
    //   $j = $j + 2;
    // }
    // // //
    // // // // dd($teamMembers,'not yet');
    // DB::table('teamsmembers')->insert($teamMembers);
    // dd('teamsMembers added');


    // $matches = DB::table('matches')->select('firstTeam','secondTeam','id','competitionId','roundNo')->get();
    // dd($matches);
    // $matches = $matches->map(function($obj){
    //   $t1 = DB::table('teamsmembers')->where('tid','=',$obj->firstTeam)->select('pid')->get();
    //   $t2 = DB::table('teamsmembers')->where('tid','=',$obj->secondTeam)->select('pid')->get();
    //   $obj->firstTeamMembers = $t1->toArray();
    //   $obj->secondTeamMembers = $t2->toArray();
    //   $obj->more = false;
    //   $obj->less = false;
    //   if (count($obj->firstTeamMembers) > 2) {
    //     dd('ee,',$obj);
    //   }
    //   if (count($obj->secondTeamMembers) > 2) {
    //     dd('eee');
    //   }
    //   if (count($obj->firstTeamMembers) < 2) {
    //     dd('eeee',$obj);
    //   }
    //
    //
    //   return $obj;
    // });
    // $scores = [];
    // // dd($scores);
    // for ($i=0; $i < count($matches); $i++) {
    //   $obj = $matches[$i];
    //   $scores[] = [
    //     'judgeId'=>mt_rand(1,8),
    //     'participantId'=>$obj->firstTeamMembers[0]->pid,
    //     'matchId'=>$obj->id,
    //     'score'=>mt_rand(1,200),
    //     'competitionId'=>$obj->competitionId,
    //     'roundNo' => $obj->roundNo
    //   ];
    //   $scores[] = [
    //     'judgeId'=>mt_rand(1,8),
    //     'participantId'=>$obj->firstTeamMembers[1]->pid,
    //     'matchId'=>$obj->id,
    //     'score'=>mt_rand(1,200),
    //     'competitionId'=>$obj->competitionId,
    //     'roundNo' => $obj->roundNo
    //   ];
    //   $scores[] = [
    //     'judgeId'=>mt_rand(1,8),
    //     'participantId'=>$obj->secondTeamMembers[0]->pid,
    //     'matchId'=>$obj->id,
    //     'score'=>mt_rand(1,200),
    //     'competitionId'=>$obj->competitionId,
    //     'roundNo' => $obj->roundNo
    //   ];
    //   $scores[] = [
    //     'judgeId'=>mt_rand(1,8),
    //     'participantId'=>$obj->secondTeamMembers[1]->pid,
    //     'matchId'=>$obj->id,
    //     'score'=>mt_rand(1,200),
    //     'competitionId'=>$obj->competitionId,
    //     'roundNo' => $obj->roundNo
    //   ];
    //
    //
    //
    // }
    // $scores = collect($scores);
    // $scores = $scores->map(function ($obj){
    //   // dd($obj);
    //   $team = DB::table('teamsmembers')->where('pid','=',$obj['participantId'])->first();
    //   $obj['teamId'] = $team->tid;
    //
    //   return $obj;
    // });
    // // dd($scores);
    //
    //
    //
    //
    //
    // DB::table('scores')->insert($scores->toArray());
    // dd('scored inserted', $scores);

    dd('commeted all');

  }
  public function CompetitionList(){
    $time = "1490969052499";
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
      $competitionI++;
      $newCompeition = Helper::customeResponse($Competition, 'matches', null);
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
        $matchI++;
        $newMatch = Helper::customeResponse($match, 'redTeam', null, 'blueTeam', null);

        $matchesCollection[] = $newMatch;
        $competitionCollection[$competitionI]['matches'] = $matchesCollection;
        //geting red teams and blue team
        // echo "<br><br><br><pre>";
        // var_export($competitionCollection);
        // echo $matchI;
        // echo "</pre><br><br>";
        $redTeam = DB::table('teams')->where('id', $match->redtid)->get();
        $blueTeam = DB::table('teams')->where('id', $match->bluetid)->get();
        $competitionCollection[$competitionI]['matches'][0]['redTeam'] = Helper::customeResponse($redTeam, 'participants');
        $competitionCollection[$competitionI]['matches'][0]['blueTeam'] = Helper::customeResponse($blueTeam, 'participants');
        // echo "<br><br><br><pre>";
        // var_export($competitionCollection[$competitionI]['matches'][0]['blueTeam']['participants']);
        // echo "</pre><br><br>";
        // die();
        $redTeamParticipants = DB::table('teamsmembers')->where('tid', $redTeam[0]->id)->join('participant', 'participant.id', '=' , 'pid')->get();
        $blueTeamParticipants = DB::table('teamsmembers')->where('tid', $blueTeam[0]->id)->join('participant', 'participant.id', '=' , 'pid')->get();
        $competitionCollection[$competitionI]['matches'][0]['redTeam']['participants'] = $redTeamParticipants;
        $competitionCollection[$competitionI]['matches'][0]['blueTeam']['participants'] = $blueTeamParticipants;
      }
    }
    $collection = [
      "Competitions" => $competitionCollection
    ];
    // dd($collection);
    return json_encode($collection);
  }

  // public function table($name){
  //   $collection = DB::table($name)->get();
  //   $columns = \Schema::getColumnListing($name);
  //
  //   dd($columns);
  // }
}

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
