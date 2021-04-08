<?php

namespace App\Http\Controllers;

use DB;
use App\Ranking;
use App\Participant;
use App\Team;
use Illuminate\Http\Request;

class RankingController extends Controller
{
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
        $Competitions = DB::table('competitionVenues')->select('title')->get();
        $teams        = DB::table('teams')->select('name')->get();
        $participants = DB::table('participant')->select('name')->get();
        $Rankings     = Ranking::all();
        $Rankings     = $Rankings->map(function($Ranking){
        $Ranking->competitionName = $Ranking->competition->title;
        $firstTeam                = DB::table('teams')->where('id', '=', $Ranking->match->firstTeam)->first()->name;
        $secondTeam               = DB::table('teams')->where('id', '=', $Ranking->match->secondTeam)->first()->name;
        $Ranking->matchName       = $firstTeam . ' vs ' . $secondTeam;
        $Ranking->teamName        = $Ranking->team->name;
        $Ranking->participantName = $Ranking->participant->name;
        return $Ranking;
        });
        return view("reports.adminRanks", compact('Rankings','Competitions', 'teams', 'participants'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ranking  $ranking
     * @return \Illuminate\Http\Response
     */
    public function show(Ranking $ranking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Ranking  $ranking
     * @return \Illuminate\Http\Response
     */
    public function edit(Ranking $ranking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ranking  $ranking
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ranking $ranking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ranking  $ranking
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ranking $ranking)
    {
        //
    }
}
