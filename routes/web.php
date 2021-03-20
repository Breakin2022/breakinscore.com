<?php

Route::view('/login','auth.login')->name('login');
Route::post('/logout', "authController@logout")->name("logout");
Route::post('/login', "authController@newLogin");
Route::get('addcolumn','testing@addcolumn');
Route::get('/', 'indexController@design');
Route::get('/scoreboard', 'indexController@scoreboard');
Route::get('/indexPage', 'indexController@index2');

Route::get('/password-reset',function(){
  return view('auth.passwords.reset');
});

Route::post('chnangeCompetitionRound/{cid}','competitionVenue@chnangeCompetitionRound')->name('chnangeCompetitionRound');
Route::post('password-reset','authController@passwordReset')->name('password-reset');
Route::get('password-reset/{token}','authController@passwordResetWithToken')->name('passwordResetWithToken');
Route::post('newpassword','authController@newpassword')->name('newpassword');
Route::get('/home', 'HomeController@index')->name("home");
Route::resource("/participant", "participantController");
Route::resource("/teams", "teamsController");
Route::resource("/teamsMembers", "teamsMembersController");
Route::resource("/judges", "judgesController");
Route::resource("/competitionVenue", "competitionVenue");
Route::resource("/sponsors", "sponsorsController");
Route::resource('/match', "MatchController");
Route::resource('/criteria', "CriteriaController");
Route::resource('/competitionCriteria', "CompetitionCriteriaController");
Route::get('/testing/{table}', "testing@testing");
Route::get('makeDefaultUsers','testing@makeDefaultUsers');
Route::post('/teamScoreByTeamId', "indexController@teamScoreByTeamId")->name('teamScoreByTeamId');

Route::post('/notificationAjax', "indexController@notificationAjax")->name('notificationAjax');
Route::get('/notificationAjax', "indexController@notificationAjax")->name('notificationAjax');
Route::post('/stopMatchStartTimer','indexController@stopMatchStartTimer')->name('stopMatchStartTimer');
Route::get('/competitionscores/{id}','competitionVenue@competitionScores')->name('competitionScores');

Route::get('/report/{id}','ReportController@show')->name('report');
Route::get('/reports','ReportController@reports')->name('reports');
Route::post('/reports-teams', "ReportController@reports_teams")->name('getReportsTeams');
Route::post('/reports-match', "ReportController@reports_match")->name('getReportsMatch');

Route::get('teamsRanking/{ageGroup?}','indexController@teamsRanking')->name('teamsRanking');
Route::get('participantsRanking/{ageGroup?}','indexController@participantsRanking')->name('participantsRanking');

Route::resource("/options", "optionsController");
Route::get('/privacy', "indexController@privacy");

Route::get('/currentTime', function(){
  return Date('Y-m-d h:i:s:a');
});

Route::get('/ajaxx', function($request){
  dd($request->start_date);
});
Route::get('/abc','testController@testingnow');

  Route::get('/insertscoresofmatch/{matchid}/{competitionid}', 'ApiControllerV2@insertscoresofmatch')->name('insertscoresofmatch');

  Route::prefix('v2')->group(function () {
  Route::post('/register', 'ApiControllerV2@register')->name('api-register');
  Route::post('/login', 'ApiControllerV2@login')->name('api-login');
  Route::post('/insertScore', 'ApiControllerV2@insertScorefromadmin')->name('api-insertScore');
  Route::post('/insertMyScore', 'ApiControllerV2@insertMyScore')->name('api-insertMyScore');
  Route::post('/getSponsors', 'ApiControllerV2@SponsorsList')->name('api-getSponsors');
  Route::get('/getCompetition', 'ApiControllerV2@CompetitionList')->name('api-getCompetition');

  Route::POST('/matchStatusUpdate', 'ApiControllerV2@matchStatusUpdate')->name('api-matchStatusUpdate');
  Route::get('/matchStatusUpdate', 'ApiControllerV2@matchStatusUpdate')->name('api-matchStatusUpdate');
  Route::post('/deductScore', 'ApiControllerV2@deductScore')->name('api-deductScore');
  Route::post('/scoreInitialize', 'ApiControllerV2@scoreInitialize')->name('api-scoreInitialize');
  Route::post('/test', 'ApiControllerV2@test')->name('api-test');
  Route::post('/stopMatchStartTimer','ApiControllerV2@stopMatchStartTimer')->name('api-stopMatchStartTimer');
});