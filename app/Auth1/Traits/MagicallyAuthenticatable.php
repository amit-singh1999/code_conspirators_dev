<?php

namespace App\Auth1\Traits;
use Mail;
use App\Mail\MagicloginRequested;
use App\Models\UsersLoginToken;
use Illuminate\Support\Str;
trait MagicallyAuthenticatable{

    public function token(){
        return $this->hasOne(UsersLoginToken::class);

    }
    public function storeToken(){
        $this->token()->create([
            'token'=>Str::random(255),
        ]);
        return $this; 

    }
    public function sendMagicLink($email){
        // dd($options);
    //    dd($email);
       
    //    .'?'.http_build_query($this->option)
        // Mail::to($this)->send(new MagicloginRequested($this,$options));


    }
}