<?php

namespace App\Http\Controllers;



use Illuminate\Http\Request;
use DB;
use App\Helpers\Helpers as Helper;

class testController extends Controller
{
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

  public function table($name){
    $collection = DB::table($name)->get();
    dd($collection);
  }
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
