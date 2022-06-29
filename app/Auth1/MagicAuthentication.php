<?php

namespace App\Auth1;

use App\Models\User;
use Illuminate\Http\Request;

class MagicAuthentication
{
    protected $request;
    protected $identifier = 'email';

    public function __construct($email)
    {
        $this->email = $email;
        
    }
    public function requestLink()
    { 
        $email= $this->email;
    //   dd($email);
        $user= User::where($this->identifier,$email)->firstOrFail();
        $user->token()->delete();
        // dd("hey");
     
         $Link =  $user->storeToken();
         return $Link;
    }

    protected function getUserByIdentifier($value)
    {
        return User::where($this->identifier, $value)->firstOrFail();
    }

    public function subal(){
        echo "hey";
    }

}
