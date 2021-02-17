<?php

namespace App\Helpers;

use Blade;
use DB;

class Helpers
{
    public static function getRedTeamScore($id , $matchId){
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
    public static function getTeamTitleById($redTeamId){
      // mydump($redTeamId );
      // var_dump($redTeamId);
      if (DB::table('teams')->where('id',$redTeamId)->exists()) {
      $team = DB::table('teams')->where('id',$redTeamId)->get();
      return $team[0]->name;
      }else{
        return 'null';
      }
    }
    public static function mydump($thing){
      echo "<hr>new<hr>";
      echo "<br><pre>";
      var_export($thing);
      echo "<br></pre>";
    }
    public static function getTeamTitle($teamId){
      $team = DB::table('teams')->where('id', '=', $teamId)->get();
      // dd($team->name);
      return $team[0]->name;
    }
    public static function getTeamScore($teamId, $matchId){
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








    public static function collectionToArray($Collection){
      $array = Array();

      foreach ($Collection as $key => $value) {
        $array[$key] = $value;

      }

      return $array;
    }
    public static function customeResponse($Collection, $f1 = null, $fC1 = null, $f2 = null, $fC2 = null
          , $f3 = null, $fC3 = null, $f4 = null, $fC4 = null){
      $array = Array();
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
      }else{
        return false;
      }
    }
    public static function validate($data){
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }

    public static function makeJsonResponse(string $message, string $status,$fieldName = null, $addtional = null){
        if ($fieldName == null ) {
          $Object = (Object)[
            'status'  => $status,
            'message' => $message,
          ];
        }else{
          $Object = (Object)[
            'status'  => $status,
            'message' => $message,
            $fieldName => $addtional
          ];
        }
        return json_encode($Object);
    }
    public static function makeObject($fieldName1 = null, $param1 = null,$fieldName2 = null, $param2 = null,
                                      $fieldName3 = null, $param3 = null,$fieldName4 = null, $param4 = null,
                                      $fieldName5 = null, $param5 = null){
        if ($fieldName1 != null && $fieldName2 != null && $fieldName3 != null && $fieldName4 != null && $fieldName5 != null ) {
          return (Object)[
            $fieldName1 => $param1,
            $fieldName2 => $param2,
            $fieldName3 => $param3,
            $fieldName4 => $param4,
            $fieldName5 => $param5
          ];
        }
        if ($fieldName1 != null && $fieldName2 != null && $fieldName3 != null && $fieldName4 != null ) {
          return (Object)[
            $fieldName1 => $param1,
            $fieldName2 => $param2,
            $fieldName3 => $param3,
            $fieldName4 => $param4
          ];
        }
        if ($fieldName1 != null && $fieldName2 != null && $fieldName3 != null ) {
          return (Object)[
            $fieldName1 => $param1,
            $fieldName2 => $param2,
            $fieldName3 => $param3
          ];
        }
        if ($fieldName1 != null && $fieldName2 != null ) {
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
}
