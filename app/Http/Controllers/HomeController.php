<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;

use DB;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $date = date('Y-m-d');
      $yearback = strtotime($date ." -1 year");
      $date = date('Y-m-d', $yearback);
      $participants = DB::table('participant')->whereBetween('join_date', [$date ,date('Y-m-d') ])->get();

      for ($i=1; $i <= 12; $i++) {
        $months[0][$i] = $i;

      }
      $months[1][1] = "January";
      $months[1][2] = "February";
      $months[1][3] = "March";
      $months[1][4] = "April";
      $months[1][5] = "May";
      $months[1][6] = "June";
      $months[1][7] = "July";
      $months[1][8] = "August";
      $months[1][9] = "September";
      $months[1][10] = "October";
      $months[1][11] = "November";
      $months[1][12] = "December";
      $months[0][1] = DB::table('participant')->whereBetween('join_date', [$date ,date('Y-m-d') ])->whereMonth('join_date' , '1')->count();
      $months[0][2] = DB::table('participant')->whereBetween('join_date', [$date ,date('Y-m-d') ])->whereMonth('join_date' , '2')->count();
      $months[0][3] = DB::table('participant')->whereBetween('join_date', [$date ,date('Y-m-d') ])->whereMonth('join_date' , '3')->count();
      $months[0][4] = DB::table('participant')->whereBetween('join_date', [$date ,date('Y-m-d') ])->whereMonth('join_date' , '4')->count();
      $months[0][5] = DB::table('participant')->whereBetween('join_date', [$date ,date('Y-m-d') ])->whereMonth('join_date' , '5')->count();
      $months[0][6] = DB::table('participant')->whereBetween('join_date', [$date ,date('Y-m-d') ])->whereMonth('join_date' , '6')->count();
      $months[0][7] = DB::table('participant')->whereBetween('join_date', [$date ,date('Y-m-d') ])->whereMonth('join_date' , '7')->count();
      $months[0][8] = DB::table('participant')->whereBetween('join_date', [$date ,date('Y-m-d') ])->whereMonth('join_date' , '8')->count();
      $months[0][9] = DB::table('participant')->whereBetween('join_date', [$date ,date('Y-m-d') ])->whereMonth('join_date' , '9')->count();
      $months[0][10] = DB::table('participant')->whereBetween('join_date', [$date ,date('Y-m-d') ])->whereMonth('join_date' , '10')->count();
      $months[0][11] = DB::table('participant')->whereBetween('join_date', [$date ,date('Y-m-d') ])->whereMonth('join_date' , '11')->count();
      $months[0][12] = DB::table('participant')->whereBetween('join_date', [$date ,date('Y-m-d') ])->whereMonth('join_date' , '12')->count();
      // for ($i=1; $i <= 12; $i++) {
      //   echo "month " . $i . " ..  ". $months[0][$i] . "<br>";
      // }
      return view('home')->with('months', $months) ;

    }
}
