<?php

namespace App\Http\Controllers;

use App\Criteria;
use Illuminate\Http\Request;
use Redirect;
use Session;

class CriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $criterias = criteria::all();
        return view('criteria.index')->with([
            'criterias' => $criterias
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('criteria.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $title = $request->title;
        if (criteria::where('title', $title)->exists()) {
          Session::flash('alert' ,'alert alert-danger');
          Session::flash('status', 'Record with same title already exists');
          return Redirect::to(route('criteria.create'));
        }
        $criteria = new criteria;
        $criteria->title = $title;
        $saved = $criteria->save();
        Session::flash('alert', 'alert alert-success');
        Session::flash('status', 'Criteria inserted !');
        return Redirect::to(route('criteria.create'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\criteria  $criteria
     * @return \Illuminate\Http\Response
     */
    public function show(criteria $criteria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\criteria  $criteria
     * @return \Illuminate\Http\Response
     */
    public function edit(criteria $criteria)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\criteria  $criteria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, criteria $criteria)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\criteria  $criteria
     * @return \Illuminate\Http\Response
     */
    public function destroy(criteria $criteria ,$id)
    {
        $criteria = criteria::find($id);
        $criteria->delete();
        return $id;
    }
}
