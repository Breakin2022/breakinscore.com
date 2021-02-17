<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Redirect;
use Session;

class teamsController extends Controller
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

      return view('teams.teams');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('teams.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $color = strtolower($request->input('color'));
      $colors = array("blue", "red");
      if (!in_array($color, $colors)) {
        Session::flash('status', "color is different than blue or red");
        Session::flash('alert', 'alert alert-danger');
        return Redirect::back();
      }
      DB::table('teams')->insert(
          [
            'name' => $request->input('name'),
            'join_date' => $request->input('join_date'),
            'color' => $request->input('color')

          ]
      );
        // return "m awais";
      Session::flash('alert', 'alert alert-success');
      Session::flash('status', "Successfully Inserted!");
      // $request->session()->flash('status', 'Successfully Inserted!');
      return Redirect::to(route("teams.create"));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

      $team = DB::table('teams')->where('id', $id)->get();
      // $totalParticipants = DB::table('teamsmembers')->where('tid', $id)->count();
      // dd($totalParticipants);
      $count = DB::table('teamsmembers')->where('tid', $id)->join('participant', 'teamsmembers.pid' ,'=', 'participant.id')->count();
      $existingTeamsMembers = DB::table('teamsmembers')->where('tid', $id)->join('participant', 'teamsmembers.pid' ,'=', 'participant.id')->get();
      // dd($team[0]->color);
      // $teamColor = strtolower($team[0]->color);
      $participants = DB::table('participant')->get();
      if ($count == 2) {
        Session::flash('participant1', $existingTeamsMembers[0]->name . " - " . $existingTeamsMembers[0]->email);
        Session::flash('participant2', $existingTeamsMembers[1]->name . " - " . $existingTeamsMembers[1]->email);
      }elseif($count == 1){
        $participants = DB::table('participant')->where('id', '!=' , $existingTeamsMembers[0]->pid)->get();
        Session::flash('participant1', $existingTeamsMembers[0]->name . " - " . $existingTeamsMembers[0]->email);
      }
      // $firstParticipant = DB::table('teamsmembers')->where('tid', $id)->get();
      return view('teams.show')->with(['teams'=>$team, 'participants'=>$participants]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $teams = DB::table('teams')->where('id', $id)->get();
      // return view('teams.edit')->with('teams', $teams);

      // $team = $teams;
      $count = DB::table('teamsmembers')->where('tid', $id)->join('participant', 'teamsmembers.pid' ,'=', 'participant.id')->count();
      $existingTeamsMembers = DB::table('teamsmembers')->where('tid', $id)->join('participant', 'teamsmembers.pid' ,'=', 'participant.id')->get();

      // $teamColor = strtolower($teams[0]->color);
      // $participants = DB::table('participant')->where('color', $teamColor)->get();

      $participants = DB::table('participant')->get();
      Session::flash('participant1id', ''); //set dummy data
      Session::flash('participant1', ''); //set dummy data
      Session::flash('participant2id', ''); //set dummy data
      Session::flash('participant2', ''); //set dummy data
      // Session::flash('participant1id', ''); //set dummy data

      if ($count == 2) {
        Session::flash('participant1', $existingTeamsMembers[0]->name . " - " . $existingTeamsMembers[0]->email);
        Session::flash('participant2', $existingTeamsMembers[1]->name . " - " . $existingTeamsMembers[1]->email);


        Session::flash('participant1id', $existingTeamsMembers[0]->id);
        Session::flash('participant2id', $existingTeamsMembers[1]->id);
      }elseif($count == 1){
        $participants = DB::table('participant')->get();
        Session::flash('participant1', $existingTeamsMembers[0]->name . " - " . $existingTeamsMembers[0]->email);
        Session::flash('participant1id', $existingTeamsMembers[0]->id);
      }
      // dd(Session('participant2'));
      // $firstParticipant = DB::table('teamsmembers')->where('tid', $id)->get();
      return view('teams.edit')->with(['teams'=>$teams, 'participants'=>$participants]);
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
      $color = strtolower($request->input('color'));
      $colors = array("blue", "red");
      if (!in_array($color, $colors)) {
        Session::flash('status', "color is different than blue or red");
        return Redirect::back();
      }

      $teamid = $id;
      $firstParticipant = $request->Input('firstParticipantselected');
      $secondParticipant = $request->Input('secondParticipantselected');
      // dd("first " . $firstParticipant . "<br> Second " .  $secondParticipant );
      DB::table('teamsmembers')->where('tid', $teamid)->delete();
      // die();
      if(!empty($firstParticipant)){
          DB::table('teamsmembers')->insert(['tid' => $teamid, 'pid' => $firstParticipant]);
      }
      if (!empty($secondParticipant)) {
        DB::table('teamsmembers')->insert(['tid' => $teamid, 'pid' => $secondParticipant]);
      }

      DB::table('teams')
          ->where('id', $id)
          ->update([
            'name' => $request->input('name'),
            'join_date' => $request->input('join_date'),
            'color' => $request->input('color')
          ]);





      return Redirect::to(route("teams.index"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      // return 'yes';
      $deleted = DB::table('matches')->where('redtid', '=', $id)->orWhere('bluetid', '=', $id)->delete();
      DB::table('teams')->delete($id);
      return $id;
    }
}
