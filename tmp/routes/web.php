<?php



Route::get('/login', function(){
  return view('auth.login');
})->name('login');
Route::get('/setpassword',function(){
  // $password = Hash::make('passwordnewhai');

});
Route::post('/logout', "authController@logout")->name("logout");
Route::post('/login', "authController@newLogin");
// Route::get('/',  'indexController@index')->name("indexPage");
Route::get('/', 'indexController@index');
Route::get('/indexPage', 'indexController@index2');
// Route::get('/', 'HomeController@index');
Route::get('/password-reset',function(){
  return view('auth.passwords.reset');
});
Route::get('queryyy',function( ){
  // DB::statment($query);
  DB::table('password_resets')->update([
    'created_at'=>'2017-12-09 10:56:38'
  ]);
});
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
Route::post('/teamScoreByTeamId', "indexController@teamScoreByTeamId")->name('teamScoreByTeamId');
// Route::post('/ajax', "indexController@ajax")->name('ajax');
// Route::post('/', "indexController@ajax2")->name('ajax');
// Route::post('/ajax2', "indexController@ajax2")->name('ajax2');

Route::post('/notificationAjax', "indexController@notificationAjax")->name('notificationAjax');
Route::get('/notificationAjax', "indexController@notificationAjax")->name('notificationAjax');

Route::get('/testreq',function(){

});
Route::any('/runquery', function(){
  $now = date('Y-m-d H:i:s');

  $result = $isStarted = DB::select("select * ,timestampdiff(second,isStarted, '$now' ) as timediff from notifications where timestampdiff(second,isStarted, '$now' ) <= 12015 and competitionId = 19 and isFinished = 'null'");
  dd($result);
});



















Route::get('/currentTime', function(){
  return Date('Y-m-d h:i:s:a');
});
Route::get("table/{name}", "testController@table")->name("table");
Route::get('/ajaxx', function($request){
  dd($request->start_date);
});
// Route::get('/routesList', function(){
//   $exitCode = Artisan::call('route:list');
//   $output = Artisan::output();
//   dd($output);
//
// });
// Route::get('/emptyTable', function(){
//   $result = DB::table('scores')->delete();
//   dd($result);
//
//   // $exitCode = Artisan::call('route:list');
//   // $output = Artisan::output();
//   // dd($output);
//   dd('are you sure ?');
// });
// Route::get('/routeList', function(){
//   $exitCode = Artisan::call('route:list');
//   $output = Artisan::output();
//   dd($output);
// });
// Route::get('runCommand', function(){
//   // $exitCode = Artisan::call('make:migration', [
//   //   'name' => 'create_notifications_table',
//   //   '--create' => 'notifications'
//   // ]);
//
//
// $exitCode = Artisan::call('migrate');
//   // $exitCode = Artisan::call('route:list');
//   // $exitCode = Artisan::call('make:controller', [
//   //   'name' => "indexController",
//   //   '--resource' => true
//   // ]);
//   // create_participant_table --create="participant"
//   // $exitCode = Artisan::call('route:list');
//   $output = Artisan::output();
//   dd($output);
//   // return 'is it awais ?';
//
// });

Route::get('/yes', function(){
  // echo date('Y-m-d H:i:s');
  // $colleciton = DB::table('scores')->where
  // $data = DB::table('scores')->get();
  // // echo "<table>"
  // foreach ($data as $obj) {
  //   echo $obj->id . " . " . " judgeId " . $obj->judgeId . " " . " . " . " matchId " . $obj->matchId . " " . "<br>";
  // }
});















// Route::get('dropalltables', function(){
//   foreach(\DB::select('SHOW TABLES') as $table) {
//     $table_array = get_object_vars($table);
//     \Schema::drop($table_array[key($table_array)]);
//   }
// });













Route::get("tableList", function(){
  $tables = DB::select('show tables');
  echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>';
  foreach ($tables as $table) {
    // dd($table);
    echo "<a href='table\\$table->Tables_in_database2017'>$table->Tables_in_database2017 </a> <br>";
  }
});
