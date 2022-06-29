<?php

namespace App\Http\Controllers\admindashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\logins;

class logindetailcontroller extends Controller
{
    //
    public function logighistoryShow($id)
    { 

        $user_login_data = logins::where('user_id', $id)->get();
        return view('Adminview.loginDetail_history',compact('user_login_data'));
     

    }
}
