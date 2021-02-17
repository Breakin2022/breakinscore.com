<?php

namespace App\Http\Controllers;

use App\match;
use App\team;



use Illuminate\Http\Request;
use DB;
use Redirect;
use Session;

class competitionVenue extends Controller
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
      // $query = "Select matches.*,Tred.name as redteam,Tblue.name as blueteam From matches INNER JOIN teams Tblue ON Tblue.id = matches.bluetid INNER JOIN teams Tred ON Tred.id = matches.redtid";
      // $collection = DB::select($query);

      // dd($collection);
          return view('competitionVenue.competitionVenue');
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
      DB::table('competitionVenues')->insert(
          [
            'title' => $request->input('title'),
            'address' => $request->input('address'),

            'phone' => $request->input('phone'),
            'start_date' => $request->input('start_date')
          ]
      );
        // return "m awais";
      Session::flash('alert', 'alert alert-success');
      Session::flash('status', "Successfully Inserted!");
      // $request->session()->flash('status', 'Successfully Inserted!');
      return Redirect::to(route("competitionVenue.create"));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $query = "Select matches.*,Tred.name as redteam,Tblue.name as blueteam
      From matches INNER JOIN teams Tblue ON Tblue.id = matches.bluetid INNER JOIN teams Tred ON Tred.id = matches.redtid
      ";
      $competitionName = Db::table('competitionVenues')->where('id',$id)->get();
      $criteriaList = DB::table('criterias')->get();
      $competitionList = collect(DB::select($query))->where('competitionId' , $id);

      $criteriasName = DB::table('criterias')->join('competition_criterias', 'competition_criterias.criteriaid', '=' , 'criterias.id')->where('competition_criterias.competitionid', $id)->get();
      // dd($criteriasName);

      $redTeams = team::teamByColor('red');
      $blueTeams = team::teamByColor('blue');;

      return view('competitionVenue.teamsAdd',
        [
          'cid'     => $id,
          'competitionName' => $competitionName[0]->title,
          'criteriaList' => $criteriaList,
          'criteriasName' => $criteriasName,
          'redTeams'=>$redTeams,
          'blueTeams'=>$blueTeams,
          'competitionList'=> $competitionList
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
      return $id;
    }
}
