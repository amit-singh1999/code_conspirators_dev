<?php

namespace App\Http\Controllers\admindashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\logins;


class admindashboardController extends Controller
{
    public function index()
    {
        $data = User::all();
        $data =  json_decode($data, true);
        $newarray = [];
        $countter  = 0;
        foreach ($data as $newdata) {
            $userid = $newdata['id'];
            $logindetails =   $this->NumberoFlogin_and_lastLogin($userid);
            $data[$countter]['login_details'] = $logindetails;
            $countter++;
        }
        return view('Adminview.index', compact('data'));
    }

    public function  portalUserDelete($id)
    {
        $userfound = User::findOrFail($id);
        $userfound->delete();
        return redirect('/dashboard');
    }

    public function  portalUserEdit($id)
    {
        $userdata = User::findOrFail($id);
        $logins = logins::where('user_id', $id)->orderBy('created_at', 'DESC')->get();
        return view('Adminview.adminEdituserData', compact('userdata', 'logins'));
    }

    public function  portalUserEditUpdate(Request $req, $id)
    {
        // //validation 
        // $this->validate($req, [
        //     'Name' => 'required',
        // ]);

        $userfound = User::findOrFail($id);
        $userfound->update([
            'name' => $req->Name,
            'role' => $req->role,
            'phone' =>  $req->Phone,
        ]);
        return redirect()->back();
    }

    private function NumberoFlogin_and_lastLogin($id)
    {
        $user_login_count = logins::where('user_id', $id)->get()->count();
        $user_login_last = logins::where('user_id', $id)->get()->last();

        if (isset($user_login_last))

            $user_login_last = $user_login_last['created_at'];

        else

            $user_login_last = '';
        // $user_login_last = $user_login_last['created_at'];
        return [$user_login_count, $user_login_last];
    }
}
