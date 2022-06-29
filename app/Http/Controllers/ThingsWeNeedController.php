<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use DB;
use Image;

class ThingsWeNeedController extends Controller
{
    function updatethingsweneed(Request $req)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://cc.codeconspirators.com/rest/22/n0iixre6ucmshyad/tasks.task.update',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
        "taskId":'.$req->taskid.',
          "fields":{	
            "DESCRIPTION":"'.$req->taskcomment.'",
            "UF_AUTO_697796185795":0
          }
          }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: BITRIX_SM_SALE_UID=0; qmb=.'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        
        // for image
       
        $name =  $req->file('imgforthingsweneed')->getClientOriginalName();
        $taskid=$req->taskid;
      
    
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://cc.codeconspirators.com/rest/22/n0iixre6ucmshyad/task.item.addfile.xml');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, array( 
            'TASK_ID' => $taskid, 
            'FILE[NAME]' => "'.$name.'", 
            'FILE[CONTENT]' => base64_encode(file_get_contents($req->file('imgforthingsweneed'))) 
        ));
        
        $out = curl_exec($curl);
        
        curl_close($curl); 



        return back()->withStatus(__('Thank you! Document successfully uploaded.'));
    }
}
