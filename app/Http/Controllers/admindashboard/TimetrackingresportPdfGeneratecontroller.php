<?php

namespace App\Http\Controllers\admindashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Image;
use PDF;

class TimetrackingresportPdfGeneratecontroller extends Controller
{
    
    public function index(Request $request){
        
    $startdate = '';        
    $Enddate   = '';
    $counter = 0 ;
    $arraydata = $request->arrarOFuserID;
	$data = explode(",",$arraydata);
//	print_r($request); die(1);
	$newmainarrayfinal = [];
	
	foreach($data as $perdata){
	 
	
		$userid =  explode("+",$perdata);
		if($counter == 0){
		        $firstdate = $userid[1];
				$seconddate = $userid[2];
				
				$startdate = $startdate.$firstdate ;
				$Enddate = $Enddate.$seconddate ;
		}
		$counter  = 2;
		$date = $startdate  ;
		$id = $userid[0];
		$userID = (int)$id;
    
        $subal = '[
        {"ID":"desc"
        },
        {">=CREATED_DATE": "' . $date . '","USER_ID":' . $userID . '}
        ]';
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/task.elapseditem.getlist',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $subal,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: BITRIX_SM_SALE_UID=11; qmb=.'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $newResponse = json_decode($response, true);
        $newResponse =  $newResponse['result'];
    
        $newmainarray = [];
        
        foreach ($newResponse as $value) {
            $date_start = $value['DATE_START'];
            $date_stop = $value['DATE_STOP'];
            
            if ($date_start >= $startdate and $date_stop <= $Enddate) {
                $taskID = $value['TASK_ID'];
                $taskname = $this->getTaskTitle($taskID);
                $value['TaskName'] =  $taskname[0];
                $project_idHere =  $taskname[1];
                $ProjectNameFromresponse =  $this->GetProjectName($project_idHere);
                $username = $this->Getusername($userID);
                $username= json_decode($username,true);
                $value['username']= $username['result'][0]['NAME']." ".$username['result'][0]['LAST_NAME'];
                $value['projectname'] = $ProjectNameFromresponse;
                $value['MINUTES']= number_format((float)round($value['MINUTES']/60,2),2,'.','');
                $newmainarray[] = $value;
            }
            
        }
        $newResponse =  $newmainarray;
        array_push($newmainarrayfinal,$newResponse);

	}
	
					  $html = view('dowloadasPdf.timetrackingReport',compact('newmainarrayfinal'))->render();
                      $pdf=  PDF::loadHTML($html)->setPaper('a4', 'landscape')->setWarnings(false)->
                      setOptions(['defaultFont' => 'sans-serif'])->setOptions(['isRemoteEnabled' => false]);
                      return $pdf->download('time_tracking.pdf');

// 	return view('dowloadasPdf.timetrackingReport',compact('newmainarrayfinal'));
// // 	dd($newmainarrayfinal);
    
                    
    }
    
        private function getTaskTitle($id)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/tasks.task.get?id=' . $id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Cookie: BITRIX_SM_SALE_UID=11; qmb=.'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response, true);
        $Taskdata = [];
        $tasktitle = $response['result']['task']['title'];
        $taskGroupID = $response['result']['task']['groupId'];
        array_push($Taskdata, $tasktitle);
        array_push($Taskdata, $taskGroupID);
        return $Taskdata;
    }

    private function GetProjectName($id)
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
                "FILTER": {
                    "ID": "' . $id . '"
                }
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: BITRIX_SM_SALE_UID=11; qmb=.'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response, true);
        
        // echo "<pre>";
        // print_r($response);
        // echo "</pre>";
        // echo "<br>";
        // echo "<br>";
        // echo "<br>";
        
        if(isset($response['result'][0]['NAME'])){
           $Projectname = $response['result'][0]['NAME'];   
           return  $Projectname;
        }
        return "Project not assigned";
        
    }
    
   private function Getusername($id)
        {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/user.get?id=' . $id,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_HTTPHEADER => array(
                    'Cookie: BITRIX_SM_SALE_UID=11; qmb=.'
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            return $response;
        }
     
   
}
