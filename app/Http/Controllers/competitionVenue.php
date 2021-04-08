<?php

namespace App\Http\Controllers;

use App\match;
use App\team;



use Illuminate\Http\Request;
use DB;
use Redirect;
use Session;
use App\Helpers\Helpers as Helper;
use stdClass;

class competitionVenue extends Controller
{
  public function competitionScores(Request $request,$cid){
    $allMatches = DB::table('matches')->where('competitionId','=',$cid)->get();
    $allMatches = $allMatches->map(function($obj)use($cid){
      $obj->t1name = DB::table('teams')->where('id','=',$obj->firstTeam)->pluck('name')->first();
      $obj->t2name = DB::table('teams')->where('id','=',$obj->secondTeam)->pluck('name')->first();
      $obj->t1score = DB::table('scores')->where('competitionId','=',$cid)->where('matchId','=',$obj->id)->where('teamId','=',$obj->firstTeam)->join('criteriaScore','criteriaScore.scoreId','=','scores.id')->sum('criteriaScore.score');
      $obj->t2score = DB::table('scores')->where('competitionId','=',$cid)->where('matchId','=',$obj->id)->where('teamId','=',$obj->secondTeam)->join('criteriaScore','criteriaScore.scoreId','=','scores.id')->sum('criteriaScore.score');
      return $obj;
    });
    return view('competitionVenue.allRoundsScore',compact('allMatches'));
  }
  public function chnangeCompetitionRound(Request $request,$cid){
    $currentRound = DB::table('competitionVenues')->where('id','=',$cid)->first();
    $round = $currentRound->round;

    DB::table('competitionVenues')->where('id','=',$cid)->update([
      'round' => $round + 1
    ]);
    return $round + 1;
  }
  public function __construct()
  {
      $this->middleware('auth');
  }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      // $query = "Select matches.*,Tred.name as redteam,Tblue.name as blueteam From matches INNER JOIN teams Tblue ON Tblue.id = matches.bluetid INNER JOIN teams Tred ON Tred.id = matches.redtid";
      // $collection = DB::select($query);
        $competitionVenues = DB::table('competitionVenues')->orderByDesc('id')->get();


          return view('competitionVenue.competitionVenue',compact( 'competitionVenues'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('competitionVenue.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $isTrial = $request->trial;
      if ($isTrial == null) {
        $isTrial = 0;
      }
      DB::table('competitionVenues')->insert(
          [
            'title' => $request->input('title'),
            'address' => $request->input('address'),
            'type' => $request->input('competitionType'),
            'phone' => $request->input('phone'),
            'start_date' => $request->input('start_date'),
            'isTrial' => $isTrial
          ]
      );


      Session::flash('alert', 'alert alert-success');
      Session::flash('status', "Successfully Inserted!");
      // $request->session()->flash('status', 'Successfully Inserted!');
      return Redirect::to(route("competitionVenue.index"));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $fillerTeam = Helper::getFillerTeam();
        $fillerTeams = [
          $fillerTeam[0]->id,
          $fillerTeam[1]->id
        ];
        $competitionMainObj = DB::table("competitionVenues")->where('id', $id)->first();
        $matchesSizeInCurrent = DB::table('matches')->where('competitionId','=',$id )->where('roundNo','=',$competitionMainObj->round)->count();
        $teamsCounts = DB::table('teams')->Join('teamsmembers',function($join){
          $join->on('teams.id','=','teamsmembers.tid');
        })->select(DB::raw('count(teamsmembers.pid) as total'),'teams.id')->groupBy('teams.id')->get();
        $teamsWithOneMember = $teamsCounts->where('total','=',1);
        $teamsWithTwoMember = $teamsCounts->where('total','=',2);

        $teamThoseAreGoing = [];
        $teamThoseAreNotGoing = [];
        if ($competitionMainObj->type == 1) {
          $teamThoseAreGoing =  $teamsWithOneMember->pluck('id')->toArray();
          $teamThoseAreNotGoing =  $teamsWithTwoMember->pluck('id')->toArray();
        }elseif ($competitionMainObj->type == 2) {
          $teamThoseAreGoing =  $teamsWithTwoMember->pluck('id')->toArray();
          $teamThoseAreNotGoing =  $teamsWithOneMember->pluck('id')->toArray();
        }


        $filteredTeams = [];
        if ($competitionMainObj->round == 1 ) {
          $matchesAlreadyIn = DB::table('matches')->where('competitionId','=',$id)->where('roundNo','=','1')->get();
          $alreadySelectedTeamsR1  = array_values($matchesAlreadyIn->pluck('firstTeam')->toArray());
          $alreadySelectedTeamsR2  = array_values($matchesAlreadyIn->pluck('secondTeam')->toArray());
          $alreadySelectedTeamsR = array_merge($alreadySelectedTeamsR1,$alreadySelectedTeamsR2);

          $filteredTeams = DB::table('teams')->whereIn('id',$teamThoseAreGoing)->whereNotIn('id',$alreadySelectedTeamsR)->get();
        }
        $competitionName = Db::table('competitionVenues')->where('id',$id)->get();
        $currentCompetition = $competitionName->first();
        $criteriaList = DB::table('criterias')->get();
        $competitionList = DB::table('matches')->where('competitionId','=',$id)->orderBy('id')->get();
        $competitionList = $competitionList->map(function($competition){
            $firstTeam =  DB::table('teams')->where('id','=',$competition->firstTeam )->first();
            $secondTeam =  DB::table('teams')->where('id','=',$competition->secondTeam)->first();
            if ($firstTeam != null) {
                $competition->firstTeam =  $firstTeam->name;
                $competition->firstTeamId =  $firstTeam->id;
            }

            if ($secondTeam != null) {
                $competition->secondTeam =  $secondTeam->name;
                $competition->secondTeamId =  $secondTeam->id;
            }

            return $competition;
        });



        $criteriasName = DB::table('criterias')->join('competition_criterias', 'competition_criterias.criteriaid', '=' , 'criterias.id')->where('competition_criterias.competitionid', $id)->get();

        $currentRound = $currentCompetition->round;

        if ($currentRound == 0) {
            $allTeams = DB::table('teams')->whereIn('id',array_values($teamThoseAreGoing))->whereNotIn('id',$fillerTeams)->get();

        }else{
            $allTeams = DB::table('teams')->leftjoin('matches', function($join){
                $join->on('teams.id','=','matches.firstTeam'); // i want to join the users table with either of these columns
                $join->orOn('teams.id','=','matches.secondTeam');
            })->where('competitionId','=',$id)
                ->select('teams.id as id','name','competitionId','isFinished','firstTeam','secondTeam','roundNo')
                ->where('roundNo','=',$currentRound)
                ->get();

        }

        $allTeams = $allTeams->unique('id');
        $teams = $allTeams ;
        $teams = $teams->pluck('id');
        $teams = $teams->toArray( );
        $teams = implode(',',$teams);


        $allCompetitionMatches = DB::table('matches')->where('competitionId','=',$id)
            ->where('roundNo','=',$currentCompetition->round)
            ->get();

        $allTeamsTwoInOne = collect();
        $allCompetitionMatches = $allCompetitionMatches->map(function($match)use($allTeamsTwoInOne){
            $match->firstTeamScore = DB::table('scores')->where('matchId','=',$match->id)->where('teamId','=',$match->firstTeam)->sum('score');
            $match->secondTeamScore = DB::table('scores')->where('matchId','=',$match->id)->where('teamId','=',$match->secondTeam)->sum('score');
            $obj = new stdClass;
            $obj->teamId = $match->firstTeam;
            $obj->teamScore = $match->firstTeamScore;
            $obj->matchId = $match->id;
            $allTeamsTwoInOne->push($obj);

            $obj = new stdClass;
            $obj->teamId = $match->secondTeam;
            $obj->teamScore = $match->secondTeamScore;
            $obj->matchId = $match->id;
            $allTeamsTwoInOne->push($obj);

            return $match;
        });


        $allCompetitionMatchesSorted = $allTeamsTwoInOne->sortByDesc('teamScore');

        // dd($allCompetitionMatchesSorted);

        $TopTeams =  [];
        // dd($allCompetitionMatchesSorted);
        $collectionLongResponse = $allCompetitionMatchesSorted->pluck('teamId');
        // dd($allTeamsTwoInOne,$collectionLongResponse);
        $collectionSize = count($collectionLongResponse);

        if ($collectionSize > 32 ) {
            $TopTeams['Top 16'] = implode(',', ($collectionLongResponse->take('32'))->unique()->toArray() );
            $TopTeams['Top 8'] = implode(',',($collectionLongResponse->take('16'))->toArray());
            $TopTeams['Top 4'] = implode(',',($collectionLongResponse->take('8'))->toArray());
            $TopTeams['Top 2'] = implode(',',($collectionLongResponse->take('4'))->toArray());
            $TopTeams['Finals Match'] = implode(',',($collectionLongResponse->take('2'))->toArray());
        }elseif ($collectionSize >= 16 && $collectionSize <= 32) {
            $TopTeams['Top 8'] = implode(',',($collectionLongResponse->take('16'))->toArray());
            $TopTeams['Top 4'] = implode(',',($collectionLongResponse->take('8'))->toArray());
            $TopTeams['Top 2'] = implode(',',($collectionLongResponse->take('4'))->toArray());
            $TopTeams['Finals Match'] = implode(',',($collectionLongResponse->take('2'))->toArray());
        }elseif ($collectionSize >= 8 && $collectionSize < 16) {
            $TopTeams['Top 4'] = implode(',',($collectionLongResponse->take('8'))->toArray());
            $TopTeams['Top 2'] = implode(',',($collectionLongResponse->take('4'))->toArray());
            $TopTeams['Finals Match'] = implode(',',($collectionLongResponse->take('2'))->toArray());
        } elseif ($collectionSize >= 4 && $collectionSize < 8) {
            $TopTeams['Top 2'] = implode(',',($collectionLongResponse->take('4'))->toArray());
            $TopTeams['Finals Match'] = implode(',',($collectionLongResponse->take('2'))->toArray());
        } elseif ($collectionSize >= 2 && $collectionSize < 4) {
            $TopTeams['Finals Match'] = implode(',',($collectionLongResponse->take('2'))->toArray());
        }
        $allTeamsCollection = DB::table('teams')->select('id','name as text')->get();
        $allTeamsCollection = $allTeamsCollection->toJson();

        $competitionList = $competitionList->sortByDesc('roundNo');

        return view('competitionVenue.teamsAdd',
            [
                'cid'     => $id,
                'competitionName' => $competitionName[0]->title,
                'round'=>$competitionName[0]->round,
                'criteriaList' => $criteriaList,
                'criteriasName' => $criteriasName,
                'competitionList'=> $competitionList,
                'allTeams'=>$allTeams,
                'teamsId'=>$teams,
                'topTeams'=>$TopTeams,
                'filteredTeams' => $filteredTeams,
                'matchesSizeInCurrent'=>$matchesSizeInCurrent,
                'allTeamsCollection'=>$allTeamsCollection
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $competitionVenues = DB::table('competitionVenues')->where('id', $id)->get();
      return view('competitionVenue.edit')->with('competitionVenues',$competitionVenues);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      DB::table('competitionVenues')
          ->where('id', $id)
          ->update([
            'title' => $request->input('title'),
            'address' => $request->input('address'),
            'phone' => $request->input('phone'),
            'start_date' => $request->input('start_date')

          ]);
          return Redirect::to(route("competitionVenue.index"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      DB::table('competitionVenues')->delete($id);
      DB::table('matches')->where('competitionId','=',$id)->delete();
      DB::table('scores')->where('competitionId','=',$id)->delete();
      return $id;
    }
}
