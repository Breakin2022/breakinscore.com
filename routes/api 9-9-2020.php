<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v2')->group(function () {
  Route::post('/register', 'ApiControllerV2@register');
  Route::post('/login', 'ApiControllerV2@login');
  Route::post('/insertScore', 'ApiControllerV2@insertScore');
  Route::post('/insertMyScore', 'ApiControllerV2@insertMyScore');
  Route::post('/getSponsors', 'ApiControllerV2@SponsorsList');
  Route::post('/getCompetition', 'ApiControllerV2@CompetitionList');

  Route::POST('/matchStatusUpdate', 'ApiControllerV2@matchStatusUpdate');
  Route::get('/matchStatusUpdate', 'ApiControllerV2@matchStatusUpdate');
  Route::post('/deductScore', 'ApiControllerV2@deductScore');
  Route::post('/scoreInitialize', 'ApiControllerV2@scoreInitialize');
  Route::post('/test', 'ApiControllerV2@test');
  Route::post('/stopMatchStartTimer','ApiControllerV2@stopMatchStartTimer');
});


Route::post('teamScoreByTeamId','indexController@teamScoreByTeamId');
Route::post('/register', 'ApiController@register');
Route::post('/login', 'ApiController@login');
Route::post('/getSponsors', 'ApiController@SponsorsList');
Route::post('/getCompetitionOld', 'ApiController@CompetitionList');
Route::post('/getCompetition', 'ApiController@CompetitionList2');
Route::POST('/matchStatusUpdate', 'ApiController@matchStatusUpdate');
Route::get('/matchStatusUpdate', 'ApiController@matchStatusUpdate');

Route::post('/insertScore', 'ApiController@insertScore');
Route::post('/deductScore', 'ApiController@deductScore');
Route::post('/scoreInitialize', 'ApiController@scoreInitialize');

Route::post('/test', 'ApiController@test');
Route::post('/stopMatchStartTimer','ApiController@stopMatchStartTimer');

Route::post('lhr','indexController@stopMatchStartTimer');
Route::post('getAllCompetition','scoreBoardController@getCompetition');
Route::post('getNotificationDetails','scoreBoardController@getNotificationDetails');
Route::post('getStopTimerStatus','scoreBoardController@getStopTimerStatus');
Route::post('getTeamScoreA','scoreBoardController@getTeamScore');
Route::post('getScoreOf8teams','scoreBoardController@getScoreOf8teams');
Route::post('getdisplay16ViewUpdate','scoreBoardController@getdisplay16ViewUpdate');
Route::post('getSpecficRoundTeamsScore','scoreBoardController@getSpecficRoundTeamsScore');
Route::post('getScoreOf2teams','scoreBoardController@getScoreOf2teams');
Route::post('getScoreOf4teams','scoreBoardController@getScoreOf4teams');
Route::post('getScoreOf8teams','scoreBoardController@getScoreOf8teams');
Route::post('getScoreOf16teams','scoreBoardController@getScoreOf16teams');
Route::post('replaceMatchTeam','MatchController@replaceMatchTeam');
Route::post('testingnow','scoreBoardController@getScoreOf4teams');

Route::get('addcolumn','testing@addcolumn');
