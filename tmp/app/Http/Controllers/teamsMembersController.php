<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Redirect;
use Session;

class teamsMembersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // echo json_encode(DB::table('participant')->get());
        // return 'yes';
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
      $teamid = $request->input('teamid');
      $existtingParticipantscount = DB::table('teamsmembers')->where('tid', $teamid)->count();
      if ($existtingParticipantscount == 2) {
        Session::flash('alert', 'alert alert-danger');
        Session::flash('status', "Team already has two members");
        return Redirect::to(route("teams.show", $teamid));
      }
      $firstParticipant = $request->Input('firstParticipantselected');
      $secondParticipant = $request->Input('secondParticipantselected');

    if(!empty($firstParticipant)){
        DB::table('teamsmembers')->insert(['tid' => $teamid, 'pid' => $firstParticipant]);
    }
    if (!empty($secondParticipant)) {
      DB::table('teamsmembers')->insert(['tid' => $teamid, 'pid' => $secondParticipant]);
    }
    Session::flash('alert', 'alert alert-success');
    Session::flash('status', "Successfully Inserted!");
    return Redirect::to(route("teams.index"));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
      // echo $request->method();
      // echo 'here';

        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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

      // dd('yes');
        // echo $request->Input('firstParticipant');
        // return "<br>" . 'updateController hit' ;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
