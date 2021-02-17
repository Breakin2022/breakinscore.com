<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Redirect;
use Session;


class sponsorsController extends Controller
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
      return view("sponsors.sponsors");
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
      return view('sponsors.add');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $path = $request->file('image')->store('image');

    DB::table('sponsors')->insert(
        [
          'title' => $request->input('title'),
          'link' => $request->input('link'),
          'image' => $path

        ]
    );
      // return "m awais";
    Session::flash('alert' ,'alert alert-success');
    Session::flash('status', "Successfully Inserted!");
    // $request->session()->flash('status', 'Successfully Inserted!');
    return Redirect::to(route("sponsors.create"));

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
      $sponsors = DB::table('sponsors')->where('id', $id)->get();
      return view('sponsors.edit')->with('sponsors',$sponsors);
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
    $path =  $request->file('image')->store('image');
    DB::table('sponsors')
        ->where('id', $id)
        ->update([
          'title' => $request->input('title'),
          'link' => $request->input('link'),
          'image' => $path
        ]);
        return Redirect::to(route("sponsors.index"));
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
      DB::table('sponsors')->delete($id);
      return $id;
  }
}
