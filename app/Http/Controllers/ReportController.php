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

use App\Helpers\Helpers as Helper;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
