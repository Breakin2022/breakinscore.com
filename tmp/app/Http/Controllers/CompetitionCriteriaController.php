<?php

namespace App\Http\Controllers;

use App\competitionCriteria;
use Illuminate\Http\Request;
use DB;

class CompetitionCriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $competitionid = $request['competitionid'];
        $criteriaid    = $request['criteriaid'];
        $result = DB::table('competition_criterias')->where('competitionid', $competitionid)->where('criteriaid' , $criteriaid)->exists();
        if ($result) {
          return 'error';
        }
        $competitionCriteria = new competitionCriteria;
        $competitionCriteria->competitionid = $competitionid;

        $competitionCriteria->criteriaid = $criteriaid;
        $saved = $competitionCriteria->save();
        // dd($competitionCriteria->id);
        if ($saved) {
          return $competitionCriteria->id;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\competitionCriteria  $competitionCriteria
     * @return \Illuminate\Http\Response
     */
    public function show(competitionCriteria $competitionCriteria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\competitionCriteria  $competitionCriteria
     * @return \Illuminate\Http\Response
     */
    public function edit(competitionCriteria $competitionCriteria)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\competitionCriteria  $competitionCriteria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, competitionCriteria $competitionCriteria)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\competitionCriteria  $competitionCriteria
     * @return \Illuminate\Http\Response
     */
    public function destroy(competitionCriteria $competitionCriteria, $id)
    {
        // dd($id);
        DB::table('competition_criterias')->where('id', $id)->delete();
        return $id;
    }
}
