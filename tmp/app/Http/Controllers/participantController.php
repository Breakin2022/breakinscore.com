<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Redirect;
use Session;

class participantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function __construct()
   {
       $this->middleware('auth');
   }
    public function index()
    {
        return view("participant.participant");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('participant.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

      DB::table('participant')->insert(
          [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'nick' => $request->input('nick'),
            'address' => $request->input('address'),
            'join_date' => $request->input('join_date'),
            'color' => '0'
          ]
      );
        // return "m awais";
      Session::flash('alert', 'alert alert-success');
      Session::flash('status', "Successfully Inserted!");
      return Redirect::to(route("participant.create"));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $participant = DB::table('participant')->where('id', $id)->get();
        return view('participant.edit')->with('participant',$participant);
        // return $id;
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

      DB::table('participant')
          ->where('id', $id)
          ->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'nick' => $request->input('nick'),
            'address' => $request->input('address'),
            'join_date' => $request->input('join_date')
          ]);
          return Redirect::to(route("participant.index"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // dd($request);
        DB::table('participant')->delete($id);
        return $id;
    }
}
