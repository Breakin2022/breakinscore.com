<?php

namespace App\Http\Controllers;

use App\match;
use App\team;

use Illuminate\Http\Request;
use DB;
use Session;
use Redirect;
use App\Helpers\Helpers as Helper;

class MatchController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $matches = DB::table('matches')->get();
        return view('match.match', ['matches'=>$matches]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $redTeams = team::teamByColor('red');

        $blueTeams = team::teamByColor('blue');
        ;

        return view(
          'match.add',
        [
          'redTeams'=>$redTeams,
          'blueTeams'=>$blueTeams
        ]
      );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      // dd($request->all());
        if($request->input("nextRoundBtn") == 'yes')
        {

            $cid = $request->input("competitionid");
            $round = $request->input("round");
            $allMatches = DB::table('matches')->where('competitionId', $cid)->where('roundNo', $round)->orderBy('matchSort')->select('competitionId','firstTeam','secondTeam','roundNo','matchSort')->get();

            $allMatches = $allMatches->map(function($match){
              $match->t1score = DB::table('scores')->where('competitionId','=',$match->competitionId)->where('roundNo','=',$match->roundNo)->where('teamId','=',$match->firstTeam)->sum('score');
              $match->t2score = DB::table('scores')->where('competitionId','=',$match->competitionId)->where('roundNo','=',$match->roundNo)->where('teamId','=',$match->secondTeam)->sum('score');
              if ($match->t1score > $match->t2score) {

                  $match->winnerId = $match->firstTeam;
                  $match->winnerScore = $match->t1score;
              }elseif ($match->t1score < $match->t2score) {

                $match->winnerId = $match->secondTeam;
                $match->winnerScore = $match->t2score;
              }else {
                $team = DB::table('teams')->whereIn('id',[ $match->firstTeam, $match->secondTeam ])->orderBy('join_date')->first();
                $match->winnerId = $team->id;
                $match->winnerScore = $match->t1score; //as they are equal so does matter one or two
              }
              return $match;
            });
            $winners = $allMatches->sortByDesc('winnerScore');


            if (count($allMatches) < 2) {
              $winnerTeamId = DB::table('competitionVenues')->where('id','=',$cid)->update([
                'winnerTeamId'=>$allMatches[0]->winnerId
              ]);
              $team = DB::table('teams')->where('id','=',$allMatches[0]->winnerId)->first();
              Session::flash('alert','alert alert-success');
              Session::flash('status', 'Winner: '. $team->name."  Score: ".$allMatches[0]->winnerScore );
              return Redirect::back();
            }



            $totalSize = count($winners);
            $ll = 0;
            $jj = 1;
            $matches = collect([]);

            for ($i=0; $i < $totalSize / 2; $i++) {
              // $winners
              $firstWinner = $winners[$ll];
              $secondWinner = $winners[$jj];
              $item = [
                  'firstTeam'=> $firstWinner->winnerId,
                  'secondTeam'=> $secondWinner->winnerId,
                  'competitionId'=>$cid,
                  'roundNo'      => $round + 1,
                  'matchSort'=> $i + 1
                ];
              $matches->push($item);
              $ll = $ll + 2;
              $jj = $jj + 2;
            }


            DB::Table('matches')->insert($matches->toArray());
            DB::table('competitionVenues')->where('id', $cid)->increment('round');
            Session::flash('status', 'Next Round Started');
            return Redirect::back();
        }
        if($request->input('newTeams') == true) {

            $cid = $request->input('competitionid');
            $teams = $request->input('tteams');
            $extra = false;
            $total = count($teams);
            shuffle($teams);

            if ($total % 2 != 0)
            {
                $extraTeam = end($teams);
                $extra = true;
                $total--;

                $checkExtra = DB::table('teamsmembers')->where('tid',$extraTeam);
                $fillerTeam = Helper::getFillerTeam();
                $fillerTeam1 = $fillerTeam[0]->id;
                $fillerTeam2 = $fillerTeam[1]->id;
                if (count($checkExtra) > 1) //extra team has two members
                {
                    $checkFillerTeam2 = DB::table('matches')->where('competitionId', $cid)->where(function ($query) use ($fillerTeam2) {
                    $query->where('firstTeam', $fillerTeam2)->orWhere('secondTeam', $fillerTeam2);
                    })->first();

                    if ($checkFillerTeam2) //check if filler team2 is already playing a match
                    {
                        if ($checkFillerTeam2->firstTeam == $fillerTeam2)//is team one the filler team?
                        {

                            DB::table('matches')->where('competitionId', $cid)->where(function ($query) use ($fillerTeam2) {
                                $query->where('firstTeam', $fillerTeam2)->orWhere('secondTeam', $fillerTeam2);
                                })->update(['firstTeam' => $extraTeam]);//update the filler team with the odd team
                        }
                        else { //second team is the filler team
                            DB::table('matches')->where('competitionId', $cid)->where(function ($query) use ($fillerTeam2) {
                                $query->where('firstTeam', $fillerTeam2)->orWhere('secondTeam', $fillerTeam2);
                                })->update(['secondTeam' => $extraTeam]); //update the filler team with the odd team
                        }

                    }
                    else //fillerteam 2 is not playing a match
                    {
                        $matches[] = [
                        'competitionId' => $cid,
                        'firstTeam' => $extraTeam,
                        'secondTeam' => $fillerTeam2
                        ];
                    }


                }
                else { //extra team has one member

                     $checkFillerTeam1 = DB::table('matches')->where('competitionId', $cid)->where(function ($query) use ($fillerTeam1) {
                           $query->where('firstTeam', $fillerTeam1)->orWhere('secondTeam', $fillerTeam1);
                            })->first();

                    if ($checkFillerTeam1) //check if filler team1 is already playing a match
                    {
                        if ($checkFillerTeam1->firstTeam == $fillerTeam1)//is team one the filler team?
                        {

                            DB::table('matches')->where('competitionId', $cid)->where(function ($query) use ($fillerTeam1) {
                                $query->where('firstTeam', $fillerTeam1)->orWhere('secondTeam', $fillerTeam1);
                                })->update(['firstTeam' => $extraTeam]);//update the filler team with the odd team
                        }
                        else { //second team is the filler team
                            DB::table('matches')->where('competitionId', $cid)->where(function ($query) use ($fillerTeam1) {
                                $query->where('firstTeam', $fillerTeam1)->orWhere('secondTeam', $fillerTeam1);
                                })->update(['secondTeam' => $extraTeam]); //update the filler team with the odd team
                        }

                    }

                    else { //fillerteam 1 is not playing a match
                        $matches[] = [
                        'competitionId' => $cid,
                        'firstTeam' => $extraTeam,
                        'secondTeam' => $fillerTeam1
                        ];
                    }
                }

            }

            for ($i=0; $i < $total; $i = $i + 2) { //match of all the teams except the last one
                    $matches[] = [
                        'competitionId'=>$cid,
                        'firstTeam'=>$teams[$i],
                        'secondTeam'=>$teams[$i+1]
                    ];
                }

            DB::table('matches')->insert($matches);
            Session::flash('status', 'Teams are added');
            return Redirect::back();

        }

//--------------------------------------------------------------

            $cid = $request->input('competitionid');
            $teams = $request->input('teams');
            $extra = false;
            $total = count($teams);
            shuffle($teams);
            $currentCompetition = DB::table('competitionVenues')->where('id', '=', $cid)->first();
            if ($currentCompetition->round == 0) {


                if ($total % 2 != 0)
                {
                    // dd('we are in start of if');
                    $extraTeam = end($teams);
                    $extra = true;
                    $total--;

                    $checkExtra = DB::table('teamsmembers')->where('tid',$extraTeam)->get();

                    $fillerTeam = Helper::getFillerTeam();
                    $fillerTeam1 = $fillerTeam[0]->id;
                    $fillerTeam2 = $fillerTeam[1]->id;
                    if (count($checkExtra) > 1) //extra team has two members
                    {

                        $matches[] = [
                        'competitionId' => $cid,
                        'firstTeam' => $extraTeam,
                        'secondTeam' => $fillerTeam2
                        ];


                    }
                    else { //extra team has one member
                            $matches[] = [
                            'competitionId' => $cid,
                            'firstTeam' => $extraTeam,
                            'secondTeam' => $fillerTeam1
                            ];
                    }

                }

                for ($i=0; $i < $total; $i = $i + 2) { //match of all the teams except the last one
                    $matches[] = [
                        'competitionId'=>$cid,
                        'firstTeam'=>$teams[$i],
                        'secondTeam'=>$teams[$i+1]
                    ];
                }

                DB::table('matches')->insert($matches);
                DB::table('competitionVenues')->where('id', '=', $cid)->increment('round');
                Session::flash('status', 'Teams are added');
                return Redirect::back();
            }

            else {
            // dd('we are going to 2nd round');
            /*
            Now first round is complete we are going to 2nd round
            so now will make matach based on winner teams from preview round
            we will get heigest and make match with lower one from all selected teams
            so on :P
            */

            $teams = $request->teams;

            $topTeamChoice = $request->topTeamsChoice;
            $teamSplitSize = explode(' ',$topTeamChoice );
            $topTeamChoice = $teamSplitSize[1];

            $round = $request->round;

            $teamsCollection = collect([ ]);
            for ($i=0; $i < count($teams); $i++) {
              $team = $teams[$i];

              $match  = DB::table('matches')->where('competitionId', '=', $cid)
              ->where(function($query)use($team){
                $query->where('firstTeam','=',$team)
                ->orWhere('secondTeam', '=', $team);
              })
              ->where('roundNo', '=', $round)
              ->select('id')->first();

              // dd($round,$cid,$match,$team);
              $score = DB::table('scores')->where('competitionId', '=', $cid)
              ->where('teamId', '=', $team)->where('matchId', '=', $match->id)
              ->where('roundNo', '=', $round)->sum('score') ;


              $teamsCollection->push((object)[
                'teamId'=>$team ,
                'score' =>$score
              ]);
            }
            // dd($teamsCollection);

            $teamsCollection = $teamsCollection->sortByDesc('score');
            // dd($teamsCollection);
            $sizeOfTeamsCollecion = count($teamsCollection);
            $teamsCollectionArr = $teamsCollection->toArray();
            $matchesCollection = collect([]);
            $testingCollection = collect([]);

            $j = 1;
            $k = $sizeOfTeamsCollecion ;

            for ($i=1; $i <= $sizeOfTeamsCollecion / 2; $i++) {
              $ft = $teamsCollectionArr[$j - 1];
              $st = $teamsCollectionArr[$k - 1];


              $matchesCollection->push([
                    'competitionId'=>$cid,
                    'firstTeam'=> $ft->teamId,
                    'secondTeam'=>$st->teamId,
                    'roundNo'=>$round + 1,
                    'matchSort'=>$i
              ]);
              $j = $j + 4;
              if ($j > $sizeOfTeamsCollecion) {
                $j = $j % $sizeOfTeamsCollecion;
                $j = $j + 1;
              }
              $k = $k - 4;
              if ($k < 1) {
                $k = abs($k);
                $k = $k + $sizeOfTeamsCollecion;
                $k = $k - 1;
              }
            }

            DB::table('matches')->insert($matchesCollection->toArray());
            DB::table('competitionVenues')->where('id', '=', $cid)->increment('round');
            DB::table('competitionVenues')->where('id', '=', $cid)->update([
              'topTeamChoice'=>$topTeamChoice
            ]);
            Session::flash('status', 'Teams are added');
            return Redirect::back();

        }



        Session::flash('alert', 'alert alert-success');
        Session::flash('status', 'Matches are added');
        return Redirect::back();
    }

//--------------------------------------------------------------


    /**
     * Display the specified resource.
     *
     * @param  \App\match  $match
     * @return \Illuminate\Http\Response
     */
    public function show(match $match)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\match  $match
     * @return \Illuminate\Http\Response
     */
    public function edit(match $match)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\match  $match
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // if(request()->ajax()){
        //   dd($request->all());
        // }else{
        //   return "no";
        // }
        //  return $request;
        //  return $request->all();
        //  return $id;
        //  echo $request->start_time;
        //  return "reaching ?";
        // dd($request['competitionId']);
        $competitionId = $request['competitionId'];
        $matchid = $request['match_id'];
        $match = DB::Table('matches')->where('competitionId', $competitionId)->where('id', $matchid)->update(
          ['start_time' => $request['start_time']]
        );
        // dd($match);
        return $competitionId;

        // $match = new match::where('competitionId', $competitionId);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\match  $match
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = DB::table('matches')->where('id', $id)->delete();
        return $id;
    }
}
