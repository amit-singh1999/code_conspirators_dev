<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  App\Auth1\MagicAuthentication;
use App\Models\UsersLoginToken;
use Illuminate\Support\Facades\Auth;
class MagicLoginController extends Controller
{

    protected $redirectOnRequested = '/login/magic';
    public function show(){
        // return view('auth.magic.login');
    }
    public function sendToken($email){

  
        $email =$email;
        $data  = new  MagicAuthentication($email);
       // dd($data);
        $link = $data->requestLink();  
       dd($link);
        $link=$link->token->token;
        // dd($link);
        $newlink='https://portal-dev.codeconspirators.com/codeconspirators/public/magic/'.$link;
        return  $newlink;
        

        // $this->validateLogin($request);    
        
        // return redirect()->to($this->redirectOnRequested)->with('success', 'we have sent you a magic link');
    }

    
    
    //   validating email <address></address>

    // protected function validateLogin(Request  $request){
    //     $this->validate($request,[
    //         'email'=>'required|email|max:255|exists:users,email'
    //     ]);
    // }

    public function  ValidateToken(Request $request, UsersLoginToken $token){

        // dd($token);
        $token->delete();    
        Auth::login($token->user);
        return redirect()->intended(); 
    }
   
}
