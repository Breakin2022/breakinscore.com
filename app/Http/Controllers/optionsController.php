<?php

namespace App\Http\Controllers;

use App\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Redirect;
use Session;

class optionsController extends Controller
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
        $website_title             = DB::table('options')->where('option_name', 'website_title')->value('option_value');
        $website_identity          = DB::table('options')->where('option_name', 'website_identity')->value('option_value');
        $scoreboard_layout         = DB::table('options')->where('option_name', 'scoreboard_layout')->value('option_value');
        $website_background_status = DB::table('options')->where('option_name', 'website_background_status')->value('option_value');
        $sponsors_slider           = DB::table('options')->where('option_name', 'sponsors_slider')->value('option_value');
        $sponsors_slider_width     = DB::table('options')->where('option_name', 'sponsors_slider_width')->value('option_value');
        $privacy_policy            = DB::table('options')->where('option_name', 'privacy_policy')->value('option_value');
        $website_logo              = DB::table('options')->where('option_name', 'website_logo')->value('option_value');
        $website_background        = DB::table('options')->where('option_name', 'website_background')->value('option_value');
        $vs_scoreboard             = DB::table('options')->where('option_name', 'vs_scoreboard')->value('option_value');
        return view('options.options',compact(
            'website_title',
            'website_identity',
            'scoreboard_layout',
            'website_background_status',
            'sponsors_slider',
            'sponsors_slider_width',
            'privacy_policy',
            'website_logo',
            'website_background',
            'vs_scoreboard'
        ));
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
        $website_title = $request->website_title;
        if($website_title ){
            $website_title_update               = Options::firstOrNew(array('option_name' => 'website_title'));
            $website_title_update->option_value = $website_title;
            $website_title_update->save();
        }

        $website_identity = $request->website_identity;
        if($website_identity){
            $website_identity_update               = Options::firstOrNew(array('option_name' => 'website_identity'));
            $website_identity_update->option_value = $website_identity;
            $website_identity_update->save();
        }

        $scoreboard_layout = $request->scoreboard_layout;
        if($scoreboard_layout ){
            $scoreboard_layout_update               = Options::firstOrNew(array('option_name' => 'scoreboard_layout'));
            $scoreboard_layout_update->option_value = $scoreboard_layout;
            $scoreboard_layout_update->save();
        }

        $website_background_status = $request->website_background_status;
        if($website_background_status){
            $website_background_status_update               = Options::firstOrNew(array('option_name' => 'website_background_status'));
            $website_background_status_update->option_value = $website_background_status;
            $website_background_status_update->save();
        }

        $sponsors_slider = $request->sponsors_slider;
        if($sponsors_slider){
            $sponsors_slider_update               = Options::firstOrNew(array('option_name' => 'sponsors_slider'));
            $sponsors_slider_update->option_value = $sponsors_slider;
            $sponsors_slider_update->save();
        }

        $sponsors_slider_width = $request->sponsors_slider_width;
        if($sponsors_slider_width){
            $sponsors_slider_width_update               = Options::firstOrNew(array('option_name' => 'sponsors_slider_width'));
            $sponsors_slider_width_update->option_value = $sponsors_slider_width;
            $sponsors_slider_width_update->save();
        }

        $privacy_policy = $request->privacy_policy;
        if($privacy_policy){
            $privacy_policy_update               = Options::firstOrNew(array('option_name' => 'privacy_policy'));
            $privacy_policy_update->option_value = htmlentities($privacy_policy);
            $privacy_policy_update->save();
        }

        $vs_scoreboard = $request->vs_scoreboard;
        if($vs_scoreboard){
            $vs_scoreboard_update               = Options::firstOrNew(array('option_name' => 'vs_scoreboard'));
            $vs_scoreboard_update->option_value = htmlentities($vs_scoreboard);
            $vs_scoreboard_update->save();
        }

        $images = request()->validate([
            'website_logo'       => 'image',
            'website_background' => 'image'
        ]);

        if( $request->hasFile('website_logo') ){
            $website_logo_path                 = $request->file('website_logo')->store('image');
            $website_logo_update               = Options::firstOrNew(array('option_name' => 'website_logo'));
            $website_logo_update->option_value = $website_logo_path;
            $website_logo_update->save();
        }
        if( $request->hasFile('website_background') ){
            $website_background_path                 = $request->file('website_background')->store('image');
            $website_background_update               = Options::firstOrNew(array('option_name' => 'website_background'));
            $website_background_update->option_value = $website_background_path;
            $website_background_update->save();
        }
               Session:: flash('alert' ,'alert alert-success');
               Session:: flash('status', "Successfully Updated!");
        return Redirect:: to(route('options.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Options  $options
     * @return \Illuminate\Http\Response
     */
    public function show(Options $options)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Options  $options
     * @return \Illuminate\Http\Response
     */
    public function edit(Options $options)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Options  $options
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Options $options)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Options  $options
     * @return \Illuminate\Http\Response
     */
    public function destroy(Options $options)
    {
        //
    }
}
