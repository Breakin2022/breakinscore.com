<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Redirect;
use Session;
use App\User;
use Hash;
use Auth;
use Illuminate\Support\Facades\Schema;

use App\Helpers\Helpers as Helper;

class testing extends Controller
{
  public function addcolumn(){
    Schema::table('notifications', function($table)
    {
        $table->string('stopTimer')->default('0');
    });
  }
  public function runCommand(){
  // DB::unprepared(file_get_contents('/aaaa.sql'));
  // dd('e');
    // $exitCode = \Artisan::call('migrate:rollback', [
    //         '--force' => true,
    //     ]);
        // dd($exitCode);
  }
  public function makeDefaultUsers(){
    DB::table('judges')->where('email','=',"mawaisnow@gmail.com")->delete();
    DB::table('judges')->where('email','=',"competitivebreakin@gmail.com")->delete();

    DB::table('judges')->insert([
      'name'=>"M Awais",
      'email'=>"mawaisnow@gmail.com",
      'password'=>bcrypt("mawaisnow@gmail.com")
    ]);
    DB::table('judges')->insert([
      'name'=>"Competitive Breakin",
      'email'=>"competitivebreakin@gmail.com",
      'password'=>bcrypt("bscorednewpass")
    ]);

  }
  public function testing(){

  }

  public function CompetitionList(Request $reqeust){
    $time = $reqeust->time;
    $time = "1492635878260";
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

      $matchI=0;

      foreach ($matches as $match) {

        $newMatch = Helper::customeResponse($match, 'redTeam', null, 'blueTeam', null);

        $matchesCollection[] = $newMatch;

        $competitionCollection[$competitionI]['matches'] = $matchesCollection;


        $redTeam = DB::table('teams')->where('id', $match->redtid)->get();
        $blueTeam = DB::table('teams')->where('id', $match->bluetid)->get();
        // var_dump($redTeam);
        $competitionCollection[$competitionI]['matches'][$matchI]['redTeam'] = Helper::customeResponse($redTeam, 'participants');
        $competitionCollection[$competitionI]['matches'][$matchI]['blueTeam'] = Helper::customeResponse($blueTeam, 'participants');

        $redTeamParticipants = DB::table('teamsmembers')->where('tid', $redTeam[0]->id)->join('participant', 'participant.id', '=' , 'pid')->get();
        $blueTeamParticipants = DB::table('teamsmembers')->where('tid', $blueTeam[0]->id)->join('participant', 'participant.id', '=' , 'pid')->get();

        $competitionCollection[$competitionI]['matches'][$matchI]['redTeam']['participants'] = $redTeamParticipants;
        $competitionCollection[$competitionI]['matches'][$matchI]['blueTeam']['participants'] = $blueTeamParticipants;
        var_dump($competitionCollection);

        $matchI++;
      }
      $competitionI++;
      // dd($competitionCollection);
    }
    $collection = [
      "Competitions" => $competitionCollection
    ];
    dd($collection);
    return json_encode($collection);
  }
}
