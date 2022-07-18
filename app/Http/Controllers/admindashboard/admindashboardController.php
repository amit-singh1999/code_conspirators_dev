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
        $project_name = $this->ProjectName();
        $project_name_result = $project_name->result;
        
        return view('Adminview.adminEdituserData', compact('userdata', 'logins','project_name_result'));
    }

    public function ProjectName()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/sonet_group.get',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
             "ORDER": {
             "NAME": "ASC"
             }
             }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: BITRIX_SM_SALE_UID=11; qmb=.'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response);
    }

    public function  portalUserEditUpdate(Request $req, $id)
    {
        // //validation 
        // $this->validate($req, [
        //     'Name' => 'required',
        // ]);
        $userfound = User::findOrFail($id);
        $userfound->update([
            'name' => $req->name,
            'role' => $req->role,
            'phone' =>  $req->phone,
            'project' =>  $req->project,
        ]);
        // $projects = $req->project;
        //     $req->project = implode(',',$projects);
        // dd($projects);
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
