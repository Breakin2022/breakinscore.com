<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Redirect;
use Session;
use App\User;
use Hash;
use Auth;

use App\Helpers\Helpers as Helper;

class authController extends Controller
{
    public function passwordResetWithToken(Request $request,$token){

       if(db::table('password_resets')->where('email','=','competitivebreakin@gmail.com')->where('token','=',$token)->exists()){
         $data = db::table('password_resets')->where('email','=','competitivebreakin@gmail.com')->first();
         $now = new \DateTime();
         // $result = $now->format('Y-m-d H:i:s');

          $dateTime = new \DateTime($data->created_at);
// ();

          $rst = $dateTime->diff($now);
          if ($rst->d > 0) {
            return view('auth.passwords.reset_show')->with('isExpired','true');
          }
         $isFound = true;
         return view('auth.passwords.reset_show')->with('isFound',$isFound);
       }else{
         $isFound = false;
       }
       // dd('s');
       return view('auth.passwords.reset_show')->with('isFound',$isFound);
    }
    public function newpassword(Request $request){
      $password = $request->password;
      $orignalPassword = $password;
      $password =  Hash::make($password);
      DB::table('judges')->where('email','=','competitivebreakin@gmail.com')->update(
        ['password' => $password]
      );

              // $link = route('passwordResetWithToken',$code);

$msg = <<<MSG
Hi,<br>
Your new password for B-scored.com is: $orignalPassword
MSG;
      $headers  = 'MIME-Version: 1.0' . "\r\n";
      $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
      mail("competitivebreakin@gmail.com","b-scored new password ",$msg,$headers);
      db::table('password_resets')->where('email','=','competitivebreakin@gmail.com')->delete();

      session::flash('status','Password Reset Successfully');
      return Redirect::to('login');
    }
    public function passwordReset(Request $request){
      $email = $request->email;
      if ($email == "competitivebreakin@gmail.com") {
        $code = str_random(10);
        db::table('password_resets')->where('email','=','competitivebreakin@gmail.com')->delete();
        $createdAt = new \DateTime();

        db::table('password_resets')->insert([
          'email'=>$email,
          'token'=>$code,
          'created_at'=>$createdAt
        ]);

        $link = route('passwordResetWithToken',$code);

$msg = <<<MSG
Hi,<br>
Please follow this link to reset password
<br>
<a href="$link">Reset Password</a>
MSG;
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
        mail("competitivebreakin@gmail.com","b-scored password reset",$msg,$headers);
        return view('auth.passwords.reset' )->with('resetsucessfull','true');
         // dd('es');
      }else{
        dd('only admin can reset password');
      }
    }
    public function logout(){
      Auth::logout();
      return Redirect::to('login');
    }
    public function newLogin(Request $request){
      $email = $request->input('email');
      $password = $request->input('password');

      if ($email == null || $password == null) {
        Session::flash('status', "Email And Password is required");
        return Redirect::back();
      }
      if ($email != "competitivebreakin@gmail.com") {

        Session::flash('status', "Only Admin can login");
        return Redirect::back();
      }
      $countUserName = User::where('name', $email)->count();
      $countEmail = User::where('email', $email)->count();

      /*if ($countUserName == 0 && $countEmail == 0) {
        Session::flash('status', "These credentials do not match our records.");
        return Redirect::back();
      }*/
      // $hashed = Hash::make('newpasswordhai');
      // // dd($hashed);
      // db::table('judges')->where('id','=',8)->update([
      //   'password'=>$hashed
      // ]);


      if ($countEmail > 0) {
        // dd($password,$email);
          if (Auth::attempt(['email' => $email, 'password' => $password])) {
            return Redirect::to(route('home'));
          }else{

            Session::flash('status', "These credentials do not match our records.");
            return Redirect::back();
          }
      }
    }
    public function login(Request $request){
      $email = $request->input('email');
      $password = $request->input('password');

      $countUserName = User::where('name', $email)->count();
      $countEmail = User::where('email', $email)->count();

      if ($countUserName == 0 && $countEmail == 0) {
        Session::flash('status', "These credentials do not match our records.");
        return Redirect::back();
      }


      if ($countEmail > 0) {
          // return 'yes';
          if (Auth::attempt(['email' => $email, 'password' => $password])) {
            return Redirect::to(route('home'));
          }else{

            Session::flash('status', "These credentials do not match our records.");
            return Redirect::back();
          }
      }

      if ($countUserName > 0) {
        $user =  User::where('name', $email)->first();
        $DBpassword = $user->password;
        $result = Hash::check($password, $DBpassword);
        // dd($result);
        if ($result) {
          $newResult = Auth::login($user);
          // dd($newResult);
          return Redirect::to(route('home'));

        }else{
          Session::flash('status', "These credentials do not match our records.");
          return Redirect::back();
        }
      }
    }
}
