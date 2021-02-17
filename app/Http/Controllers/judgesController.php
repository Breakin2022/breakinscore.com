<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Redirect;
use Session;
use Hash;

class judgesController extends Controller
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
        return view('judges.judges');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('judges.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      if ($request->email != "" && $request->email != null) {
        if (DB::table('judges')->where('email',$request->email)->exists()) {
          Session::flash('status','Email already exists');
          return Redirect::back();
        }

      }
      DB::table('judges')->insert(
          [
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'password' => Hash::make($request->input('password')),
            'email' => $request->input('email'),
            'phone' => $request->input('phone')

          ]
      );
        // return "m awais";
      Session::flash('alert' , 'alert alert-success');
      Session::flash('status', "Successfully Inserted!");
      // $request->session()->flash('status', 'Successfully Inserted!');
      return Redirect::to(route("judges.create"));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
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
      $judges = DB::table('judges')->where('id', $id)->get();
      return view('judges.edit')->with('judges',$judges);
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
      DB::table('judges')
          ->where('id', $id)
          ->update([
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'password' => Hash::make($request->input('password')),
            'email' => $request->input('email'),
            'phone' => $request->input('phone')

          ]);
          return Redirect::to(route("judges.index"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      DB::table('judges')->delete($id);
      return $id;
    }
}
