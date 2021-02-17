<?php

namespace App\Http\Controllers;

use App\match;
use App\team;

use Illuminate\Http\Request;
use DB;
use Session;
use Redirect;

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
        return view('match.match' , ['matches'=>$matches]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $redTeams = team::teamByColor('red');

      $blueTeams = team::teamByColor('blue');;

      return view('match.add',
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
      // dd("dd ooy ");
      // dd($request->input('redTeamhidden'));
      $competitionId = $request->input('competitionid');
      $redtid = $request->input('redTeamhidden');
      $bluetid = $request->input('blueTeamhidden');
      // $redTeamCount = DB::table('matches')->where('competitionId', $competitionId)->where('redtid' , $redtid)->count();
      // $blueTeamCount = DB::table('matches')->where('competitionId', $competitionId)->where('bluetid' , $bluetid)->count();
      // // dd($blueTeamCount);
      //
      //
      // if ($redTeamCount > 0) {
      //   Session::flash('alert', 'alert alert-danger');
      //   Session::flash('status', 'Selected Red Team is already invloved in same Competition');
      //   return Redirect::back();
      // }
      // if ($blueTeamCount > 0) {
      //   Session::flash('alert', 'alert alert-danger');
      //   Session::flash('status', 'Selected Blue Team is already invloved in same Competition');
      //   return Redirect::back();
      // }
      $redTeamTotalMembers = Array();
      $blueTeamTotalMembers = Array();
      $redTeamMembers = DB::table('teamsmembers')->where('tid', $redtid)->get();
      $blueTeamMembers = DB::table('teamsmembers')->where('tid', $bluetid)->get();
      foreach ($redTeamMembers as $redTeam) {
        $redTeamTotalMembers[] = $redTeam->pid;
      }
      foreach ($blueTeamMembers as $blueTeam) {
        $blueTeamTotalMembers[] = $blueTeam->pid;
      }
      foreach ($redTeamTotalMembers as $redTeam) {
        if (in_array($redTeam,$blueTeamTotalMembers)) {
          Session::flash('alert', 'alert alert-danger');
          Session::flash('status', 'Teams have common participant in theme');
          return Redirect::back();
        }
      }
      // dd("after out ");
        $match = new match;
        // id, redtid, bluetid, competitionId, start_time, created_at, updated_at
        $match->competitionId = $competitionId;
        $match->start_time = '0';
        $match->redtid = $redtid;
        $match->bluetid = $bluetid;
        // $match->start_date = $request->input('start_date');
        $match->save();
        Session::flash('alert', 'alert alert-success');
        Session::flash('status', 'Match is added');
        return Redirect::back();
    }

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
