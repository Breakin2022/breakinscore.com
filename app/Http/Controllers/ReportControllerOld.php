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
                      <?= $criteria_html ?>
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
                      <?= $criteria_html ?>
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
                        <?= $criteria_html ?>
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
                        <?= $criteria_html ?>
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
                        <?= $criteria_html ?>
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
                        <?= $criteria_html ?>
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
