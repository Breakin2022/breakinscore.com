<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Redirect;
use Session;
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
use Hash;
use Auth;
use Response;
use stdClass;

use App\Helpers\Helpers as Helper;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * * function updateWinnerTeamAndPlayersAndScore($competitionId, $matchId) used in App\Helpers\Helpers  Helpers to update rank
     */

    public static function updateWinnerTeamAndPlayersAndScore($competitionId = 16, $matchId = 64)
    {
        $competition = DB::table('competitionVenues')->where('id', $competitionId)->first();
        $matches     = DB::table('matches')->where('id', $matchId)->first();

        $competition->round = 1;

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

    /**--------------------------------------------------------------------------------- */

    /**
     * * getTeamScore( ) is used in scoreBoardController.php to fetch and current match score on 
     *  vs. screen to show scores to participants
     */
    public function getTeamScore(Request $request){
        $competitionId = 14;
        $matchId = 74;
  
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
  
        dd($match);
      }

    public function reports(){
        $competitionVenues = DB::table('competitionVenues')->orderByDesc('id')->get();
        return view('reports.adminReports', compact( 'competitionVenues'));
    }
    public function reports_teams(Request $request){
        $id              = $request->competition_id;
        $competitionList = DB::table('matches')->where('competitionId','=',$id)->orderBy('id')->get(array('id', 'firstTeam', 'secondTeam', 'competitionId', 'roundNo'));
        if( $competitionList->count() > 0 ){
        $competitionList = $competitionList->map(function($competition){
            $firstTeam  = DB::table('teams')->where('id','=',$competition->firstTeam )->first();
            $secondTeam = DB::table('teams')->where('id','=',$competition->secondTeam)->first();
            if ($firstTeam != null) {
                $competition->firstTeam   = $firstTeam->name;
                $competition->firstTeamId = $firstTeam->id;
            }
            if ($secondTeam != null) {
                $competition->secondTeam   = $secondTeam->name;
                $competition->secondTeamId = $secondTeam->id;
            }
            return $competition;
        });
        $data = $competitionList->sortBy('roundNo');
        $status          = 'success';
        }else{
            $status      = 'error';
            $data        = 'Data Not Found!';
        }
        return (json_encode(array('status' => $status, 'data' => $data)));
    }

    protected function scores_list($scores){
        $scores_html = '';
        foreach($scores as $scoresRow){
            $scores_html .= '<td id="scoresRow_'.$scoresRow->id.'" ScoreId="'.$scoresRow->scoreId.'" criteriaId="'.$scoresRow->criteriaId.'"><a class="btn btn-primary btn-xs edit-score"  data-toggle="modal" data-target="#scoreModal">'.$scoresRow->score.'</a></td>';
        } 
        return $scores_html;
    }

    public function update_score(Request $request){
        $id    = $request->id;
        $score = $request->score;
        $update = CriteriaScore::find($id);
        if($update != null && $score){
           $update->score = $score;
           $update->save();
           return (json_encode(array('status' => 'suucess', 'data' => 'Score updated successfully.')));
        }else{
            return (json_encode(array('status' => 'error', 'data' => 'Score ID doen\'t exists!' )));
        }
        return (json_encode(array('status' => 'error', 'data' => 'Damm...')));
    }

    public function reports_match(Request $request){
        $match_id = $request->match_id;
        $match    = Match::find($match_id);
        if ($match == null) {
            return (json_encode(array('status' => 'error', 'data' => 'Match Not Exists in Database!')));
        }

        $competition = Competition::find($match->competitionId);
        $obj         = new \stdClass;

        $obj->type             = $competition->type;     /* * 1 OR 2 */
        $obj->competitionId    = $competition->id;
        $obj->competitionTitle = $competition->title;
        $obj->team1Id          = $match->teamOne->id;    /* * 'Get Table::Team id of firstTeam */
        $obj->team1Name        = $match->teamOne->name;
        $obj->team2Id          = $match->teamTwo->id;    /* * 'Get Table::Team id of secondTeam */
        $obj->team2Name        = $match->teamTwo->name;
        $obj->matchId          = $match->id;

        if ($obj->type == 1) {
            /** 'Get Table::TeamsMember | participant_id using Team id */
            $obj->team1Member1Id        = $match->teamOne->members[0]->pid;
            $obj->team1Member1Name      = Participant::find($obj->team1Member1Id)->name;
            $obj->team2Member1Id        = $match->teamTwo->members[0]->pid;
            $obj->team2Member1Name      = Participant::find($obj->team2Member1Id)->name;
            /** 'Get Table::competitionCriteria  using competitionId */
            $obj->criterias             = $competition->criterias;
            /** Get Table::Score participant's scores Data using TeamMemeber ID | participantId where  competitionId = Current match competitionId AND matchId = current matchId*/
            $p1Data = Score::where('participantId', $obj->team1Member1Id)->where('competitionId', $obj->competitionId)->where('matchId', $obj->matchId)->get();

            $p2Data = Score::where('participantId', $obj->team2Member1Id)->where('competitionId', $obj->competitionId)->where('matchId', $obj->matchId)->get();

            $p1DataScores = array();
            foreach($p1Data as $score){
                $judge = Judge::find($score->judgeId);
                if($judge != null ){
                    $judgeName   = $judge->name;
                }else{
                    $judgeName   = 'Judge '.$score->judgeId;
                }
                $scores = CriteriaScore::where('scoreId', $score->id)->get();
                if(!array_key_exists($score->matchRoundNumber,$p1DataScores)){
                    $p1DataScores[$score->matchRoundNumber] = array();
                }
                array_push($p1DataScores[$score->matchRoundNumber],array('Round' => $score->matchRoundNumber, 'judgeName'=>$judgeName, 'judgeId' => $score->judgeId, 'scores'=>$scores)); 
            }

            $p2DataScores = array();
            foreach($p2Data as $score){
                $judge = Judge::find($score->judgeId);
                if($judge != null ){
                    $judgeName   = $judge->name;
                }else{
                    $judgeName   = 'Judge '.$score->judgeId;
                }
                $scores = CriteriaScore::where('scoreId', $score->id)->get();
                if(!array_key_exists($score->matchRoundNumber,$p2DataScores)){
                    $p2DataScores[$score->matchRoundNumber] = array();
                }
                array_push($p2DataScores[$score->matchRoundNumber],array('Round' => $score->matchRoundNumber, 'judgeName'=>$judgeName, 'judgeId' => $score->judgeId, 'scores'=>$scores)); 
            }
             
            $criteria_html = ''; 
            foreach ($obj->criterias as $criteria) { 
                $criteriaTitle = "";
                if($criteria->criteria != null){
                   $criteriaTitle =  $criteria->criteria->title;
                }else{
                    $criteriaTitle =  'Criteria ID: '.$criteria->criteriaid;
                }
                $criteria_html .= '<th criteriaid="criteria_'.$criteria->criteriaid.'" competitionid="competition_'.$criteria->competitionid.'">'.$criteriaTitle.'</th>';
            }
            ob_start();
        ?>
        <div class="row panel-body"  id="scores_data">
            <div class="col-md-12">
                <h2 class="text-center">Competition: <strong><?= $obj->competitionTitle; ?></strong> <em>(1 vs. 1)</em></h2>
                <br>
                <h4>Team: <font color="#0152a1"><?= $obj->team1Name ?></font> vs. <font color="#D71A21"><?= $obj->team2Name ?></font></h4>
                <br>
            </div>
              <div class="col-md-6">
                <h5>Participant: <strong><font color="#0152a1"><?=  $obj->team1Member1Name ?></font></strong></h5>
                <hr>
                <?php 
                    foreach($p1DataScores as $round => $p1DataScore){
                ?>
                <h5>Match Round <strong><?= $round ?></strong>: </h5>
                <br>
                <table class="table table-hover table-striped table-sm">
                  <tbody>
                    <tr>
                      <th>Judge</th>
                      <?= $criteria_html; ?>
                    </tr>
                    <?php foreach($p1DataScore as $p1DataRow){ ?>
                        <tr judgeid="<?= $p1DataRow['judgeId'] ?>">
                            <th><?= $p1DataRow['judgeName'] ?> </th>
                            <?= self::scores_list($p1DataRow['scores']); ?>
                        </tr>
                    <?php } ?>
                  </tbody>
                </table>
                <hr>
                <?php 
                    }  
                ?>
              </div>
              <div class="col-md-6">
                <h5>Participant: <strong><font color="#D71A21"><?=  $obj->team2Member1Name ?></font></strong></h5>
                <hr>
                <?php 
                    foreach($p2DataScores  as $round => $p2DataScore){
                ?>
                <h5>Match Round <strong><?= $round ?></strong>: </h5>
                <br>
                <table class="table table-hover table-striped table-sm">
                  <tbody>
                    <tr>
                      <th>Judge</th>
                      <?= $criteria_html; ?>
                    </tr>
                    <?php foreach($p2DataScore as $p2DataRow){ ?>
                        <tr judgeid="<?= $p2DataRow['judgeId'] ?>">
                            <th><?= $p2DataRow['judgeName'] ?> </th>
                            <?= self::scores_list($p2DataRow['scores']); ?>
                        </tr>
                    <?php } ?>
                  </tbody>
                </table>
                <hr>
                <?php 
                    }  
                ?>
              </div>
            </div>
        <?php
        $data = ob_get_clean();
        return (json_encode(array('status' => 'success', 'data' => $data)));
        } /** End $obj->type == 1 */

        if ($obj->type == 2) {
            $obj->team1Member1Id   = $match->teamOne->members[0]->pid;
            $obj->team1Member1Name = Participant::find($obj->team1Member1Id)->name;
            $obj->team1Member2Id   = $match->teamOne->members[1]->pid;
            $obj->team1Member2Name = Participant::find($obj->team1Member2Id)->name;
            $obj->team2Member1Id   = $match->teamTwo->members[0]->pid;
            $obj->team2Member1Name = Participant::find($obj->team2Member1Id)->name;
            $obj->team2Member2Id   = $match->teamTwo->members[1]->pid;
            $obj->team2Member2Name = Participant::find($obj->team2Member2Id)->name;
            $obj->criterias        = $competition->criterias;

            $p1Data = Score::where('participantId', $obj->team1Member1Id)->where('competitionId', $obj->competitionId)->where('matchId', $obj->matchId)->get();
            $p2Data = Score::where('participantId', $obj->team1Member2Id)->where('competitionId', $obj->competitionId)->where('matchId', $obj->matchId)->get();
            $p3Data = Score::where('participantId', $obj->team2Member1Id)->where('competitionId', $obj->competitionId)->where('matchId', $obj->matchId)->get();
            $p4Data = Score::where('participantId', $obj->team2Member2Id)->where('competitionId', $obj->competitionId)->where('matchId', $obj->matchId)->get();

            $p1DataScores = array();
            foreach($p1Data as $score){
                $judge = Judge::find($score->judgeId);
                if($judge != null ){
                    $judgeName   = $judge->name;
                }else{
                    $judgeName   = 'Judge '.$score->judgeId;
                }
                $scores = CriteriaScore::where('scoreId', $score->id)->get();
                if(!array_key_exists($score->matchRoundNumber,$p1DataScores)){
                    $p1DataScores[$score->matchRoundNumber] = array();
                }
                array_push($p1DataScores[$score->matchRoundNumber],array('Round' => $score->matchRoundNumber, 'judgeName'=>$judgeName, 'judgeId' => $score->judgeId, 'scores'=>$scores)); 
            }

            $p2DataScores = array();
            foreach($p2Data as $score){
                $judge = Judge::find($score->judgeId);
                if($judge != null ){
                    $judgeName   = $judge->name;
                }else{
                    $judgeName   = 'Judge '.$score->judgeId;
                }
                $scores = CriteriaScore::where('scoreId', $score->id)->get();
                if(!array_key_exists($score->matchRoundNumber,$p2DataScores)){
                    $p2DataScores[$score->matchRoundNumber] = array();
                }
                array_push($p2DataScores[$score->matchRoundNumber],array('Round' => $score->matchRoundNumber, 'judgeName'=>$judgeName, 'judgeId' => $score->judgeId, 'scores'=>$scores)); 
            }
            
            

            $p3DataScores = array();
            foreach($p3Data as $score){
                $judge = Judge::find($score->judgeId);
                if($judge != null ){
                    $judgeName   = $judge->name;
                }else{
                    $judgeName   = 'Judge '.$score->judgeId;
                }
                $scores = CriteriaScore::where('scoreId', $score->id)->get();
                if(!array_key_exists($score->matchRoundNumber,$p3DataScores)){
                    $p3DataScores[$score->matchRoundNumber] = array();
                }
                array_push($p3DataScores[$score->matchRoundNumber],array('Round' => $score->matchRoundNumber, 'judgeName'=>$judgeName, 'judgeId' => $score->judgeId, 'scores'=>$scores)); 
            }

            $p4DataScores = array();
            foreach($p4Data as $score){
                $judge = Judge::find($score->judgeId);
                if($judge != null ){
                    $judgeName   = $judge->name;
                }else{
                    $judgeName   = 'Judge '.$score->judgeId;
                }
                $scores = CriteriaScore::where('scoreId', $score->id)->get();
                if(!array_key_exists($score->matchRoundNumber,$p4DataScores)){
                    $p4DataScores[$score->matchRoundNumber] = array();
                }
                array_push($p4DataScores[$score->matchRoundNumber],array('Round' => $score->matchRoundNumber, 'judgeName'=>$judgeName, 'judgeId' => $score->judgeId, 'scores'=>$scores)); 
            }

            $criteria_html = ''; 
            foreach ($obj->criterias as $criteria) { 
                $criteriaTitle = "";
                if($criteria->criteria != null){
                   $criteriaTitle =  $criteria->criteria->title;
                }else{
                    $criteriaTitle =  'Criteria ID: '.$criteria->criteriaid;
                }
                $criteria_html .= '<th criteriaid="criteria_'.$criteria->criteriaid.'" competitionid="competition_'.$criteria->competitionid.'">'.$criteriaTitle.'</th>';
            }
            ob_start();
            ?>
            <div class="row">
                <div class="col-md-12">
                    <h2 class="text-center">Competition: <strong><?= $obj->competitionTitle; ?></strong> <em>(2 vs. 2)</em></h2>
                    <br>
                    <h4>Team: <font color="#0152a1"><?= $obj->team1Name ?></font> vs. <font color="#D71A21"><?= $obj->team2Name ?></font></h4>
                    <br>
                </div>
            <div>
            <div class="row">
                <div class="col-md-6">
                    <h5>Participant: <strong><font color="#0152a1"><?=  $obj->team1Member1Name ?></font></strong></h5>
                    <hr>
                    <?php 
                        foreach($p1DataScores as $round => $p1DataScore){
                    ?>
                    <h5>Match Round <strong><?= $round ?></strong>: </h5>
                    <br>
                    <table class="table table-hover table-striped table-sm">
                    <tbody>
                        <tr>
                        <th>Judge</th>
                        <?= $criteria_html; ?>
                        </tr>
                        <?php foreach($p1DataScore as $p1DataRow){ ?>
                            <tr judgeid="<?= $p1DataRow['judgeId'] ?>">
                                <th><?= $p1DataRow['judgeName'] ?> </th>
                                <?= self::scores_list($p1DataRow['scores']); ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                    </table>
                    <hr>
                    <?php 
                        }  
                    ?>
                    <br>
                    <h5>Participant: <strong><font color="#0152a1"><?=  $obj->team1Member2Name ?></font></strong></h5>
                    <hr>
                    <?php 
                    foreach($p2DataScores  as $round => $p2DataScore){
                    ?>
                    <h5>Match Round <strong><?= $round ?></strong>: </h5>
                    <br>
                    <table class="table table-hover table-striped table-sm">
                    <tbody>
                        <tr>
                        <th>Judge</th>
                        <?= $criteria_html; ?>
                        </tr>
                        <?php foreach($p2DataScore as $p2DataRow){ ?>
                            <tr judgeid="<?= $p2DataRow['judgeId'] ?>">
                                <th><?= $p2DataRow['judgeName'] ?> </th>
                                <?= self::scores_list($p2DataRow['scores']); ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                    </table>
                    <hr>
                    <?php 
                        }  
                    ?>
                </div>
                <div class="col-md-6">
                    <h5>Participant: <strong><font color="#D71A21"><?=  $obj->team2Member1Name ?></font></strong></h5>
                    <hr>
                    <?php 
                        foreach($p3DataScores as $round => $p3DataScore){
                    ?>
                    <h5>Match Round <strong><?= $round ?></strong>: </h5>
                    <br>
                    <table class="table table-hover table-striped table-sm">
                    <tbody>
                        <tr>
                        <th>Judge</th>
                        <?= $criteria_html; ?>
                        </tr>
                        <?php foreach($p3DataScore as $p3DataRow){ ?>
                            <tr judgeid="<?= $p3DataRow['judgeId'] ?>">
                                <th><?= $p3DataRow['judgeName'] ?> </th>
                                <?= self::scores_list($p3DataRow['scores']); ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                    </table>
                    <hr>
                    <?php 
                        }  
                    ?>
                    <br>
                    <h5>Participant: <strong><font color="#D71A21"><?=  $obj->team2Member2Name ?></font></strong></h5>
                    <hr>
                    <?php 
                        foreach($p4DataScores as $round => $p4DataScore){
                    ?>
                    <h5>Match Round <strong><?= $round ?></strong>: </h5>
                    <br>
                    <table class="table table-hover table-striped table-sm">
                    <tbody>
                        <tr>
                        <th>Judge</th>
                        <?= $criteria_html; ?>
                        </tr>
                        <?php foreach($p4DataScore as $p4DataRow){ ?>
                            <tr judgeid="<?= $p4DataRow['judgeId'] ?>">
                                <th><?= $p4DataRow['judgeName'] ?> </th>
                                <?= self::scores_list($p4DataRow['scores']); ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                    </table>
                    <hr>
                    <?php 
                        }  
                    ?>
                </div>
            </div>
            <?php
            $data = ob_get_clean();
            return (json_encode(array('status' => 'success', 'data' => $data)));
        } /** End $obj->type == 2 */

    }

    public function show(Request $request, $id)
    {
        $match = Match::find($id);


        if ($match == null) {
            Session::flash('status', 'No match found...');
            return Redirect::back();
        }
        $competition = Competition::find($match->competitionId);

        $allData = [];


        $obj = new \stdClass;

        $obj->type = $competition->type;
        $obj->competitionId = $competition->id;
        $obj->totalRounds = $competition->round;
        $obj->team1Id = $match->teamOne->id;
        $obj->team2Id = $match->teamTwo->id;

        $obj->matchId = $match->id;

        if ($obj->type == 1) {
            $obj->team1Member1Id = $match->teamOne->members[0]->pid;

            $obj->team2Member1Id = $match->teamTwo->members[0]->pid;

            $obj->criterias      = $competition->criterias;

            ///hereeeeee
            // for ($i=1; $i <= $obj->totalRounds; $i++) {
            $obj->participant1Data = [];
            $obj->participant2Data = [];

            // $obj->currentRound = $i;

            $participant1Data = Score::where('participantId', $obj->team1Member1Id)->where('competitionId', $obj->competitionId)->get();
            $participant2Data = Score::where('participantId', $obj->team2Member1Id)->where('competitionId', $obj->competitionId)->get();

            $p1Data = $participant1Data->where('matchId', $obj->matchId);
            $p2Data = $participant2Data->where('matchId', $obj->matchId);

            foreach ($obj->criterias as $criteria) {
                for ($matchRound = 1; $matchRound <= $p1Data->max('matchRoundNumber'); $matchRound++) {
                    $data = $p1Data
            ->where('matchRoundNumber', $matchRound)->map(function ($score) use ($criteria) {
                $s = $score->criterias->where('criteriaId', $criteria->criteriaid)->first();
                $score->criteriaScore = $s->score;
                return $score;
            });
                    $obj->participant1Data[$criteria->criteria->title][$matchRound] = $data->sum('criteriaScore');
                }

                for ($matchRound = 1; $matchRound <= $p2Data->max('matchRoundNumber'); $matchRound++) {
                    $data = $p2Data
            ->where('matchRoundNumber', $matchRound)->map(function ($score) use ($criteria) {
                $s = $score->criterias->where('criteriaId', $criteria->criteriaid)->first();

                $score->criteriaScore = $s->score;
                return $score;
            });
                    $obj->participant2Data[$criteria->criteria->title][$matchRound] = $data->sum('criteriaScore');
                }
            }


            $newObj = clone $obj;
            $allData[] = $newObj;
            // dd($allData);
            // $newObj = new \stdClass;
            // }
        }

        if ($obj->type == 2) {
            $obj->team1Member1Id = $match->teamOne->members[0]->pid;
            $obj->team1Member2Id = $match->teamOne->members[1]->pid;
            $obj->team2Member1Id = $match->teamTwo->members[0]->pid;
            $obj->team2Member2Id = $match->teamTwo->members[1]->pid;
            $obj->criterias      = $competition->criterias;

            //hereeeeee
            // for ($i=1; $i <= $obj->totalRounds; $i++) {
                $obj->participant1Data = [];
                $obj->participant2Data = [];
                $obj->participant3Data = [];
                $obj->participant4Data = [];

                // $obj->currentRound = $i;

                $participant1Data = Score::where('participantId', $obj->team1Member1Id)->where('competitionId', $obj->competitionId)->get();
                $participant2Data = Score::where('participantId', $obj->team1Member2Id)->where('competitionId', $obj->competitionId)->get();
                $participant3Data = Score::where('participantId', $obj->team2Member1Id)->where('competitionId', $obj->competitionId)->get();
                $participant4Data = Score::where('participantId', $obj->team2Member2Id)->where('competitionId', $obj->competitionId)->get();

                $p1Data = $participant1Data->where('matchId', $obj->matchId);
                $p2Data = $participant2Data->where('matchId', $obj->matchId);
                $p3Data = $participant3Data->where('matchId', $obj->matchId);
                $p4Data = $participant4Data->where('matchId', $obj->matchId);

                foreach ($obj->criterias as $criteria) {
                    for ($matchRound = 1; $matchRound <= $p1Data->max('matchRoundNumber'); $matchRound++) {
                        $data = $p1Data
            ->where('matchRoundNumber', $matchRound)->map(function ($score) use ($criteria) {
                $s = $score->criterias->where('criteriaId', $criteria->criteriaid)->first();

                $score->criteriaScore = $s->score;
                return $score;
            });
                        $obj->participant1Data[$criteria->criteria->title][$matchRound] = $data->sum('criteriaScore');
                        // dd($obj);
                    }

                    for ($matchRound = 1; $matchRound <= $p2Data->max('matchRoundNumber'); $matchRound++) {
                        $data = $p2Data
            ->where('matchRoundNumber', $matchRound)->map(function ($score) use ($criteria) {
                $s = $score->criterias->where('criteriaId', $criteria->criteriaid)->first();

                $score->criteriaScore = $s->score;
                return $score;
            });
                        $obj->participant2Data[$criteria->criteria->title][$matchRound] = $data->sum('criteriaScore');
                    }

                    for ($matchRound = 1; $matchRound <= $p3Data->max('matchRoundNumber'); $matchRound++) {
                        $data = $p3Data
            ->where('matchRoundNumber', $matchRound)->map(function ($score) use ($criteria) {
                $s = $score->criterias->where('criteriaId', $criteria->criteriaid)->first();

                $score->criteriaScore = $s->score;
                return $score;
            });
                        $obj->participant3Data[$criteria->criteria->title][$matchRound] = $data->sum('criteriaScore');
                    }

                    for ($matchRound = 1; $matchRound <= $p4Data->max('matchRoundNumber'); $matchRound++) {
                        $data = $p4Data
            ->where('matchRoundNumber', $matchRound)->map(function ($score) use ($criteria) {
                $s = $score->criterias->where('criteriaId', $criteria->criteriaid)->first();
                $score->criteriaScore = $s->score;
                return $score;
            });
                        $obj->participant4Data[$criteria->criteria->title][$matchRound] = $data->sum('criteriaScore');
                    }
                }

                // dd($obj);
                $newObj = clone $obj;
                $allData[] = $newObj;
                // $newObj = new \stdClass;
            // }
        }
        if ($obj->type == 2) {
            return view('reports.report', compact('allData', 'competition', 'match'));
        }
        if ($obj->type == 1) {
            return view('reports.reportsingle', compact('allData', 'competition', 'match'));
        }
        // return view('reports.rankReport',compact('allData','competition','match'));
    }
}
