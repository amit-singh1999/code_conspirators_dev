<?php

namespace App\Http\Controllers\admindashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use PDF;

class TimeTrackingController extends Controller
{
    public function index()
    {
        return view('Adminview.Timetracking.index');
    }
    public function reporting()
    {
        return view('Adminview.reportmanagement');
    }
    
    public function getTimetrackingAjax(Request $request)
    {
        // $data = [1, 2, 3, 4, 5, 6];
        $Filterdate = $request->VarA;
        $enddateUi = $request->VarB;
        // return response()->json(array('success' => true, 'data' => $enddate));
        $filterdatevar =  '[
            {"ID": "desc"
            },
            {">=CREATED_DATE": "' . $Filterdate . '"
            }
        ]';
        function Getusername($id)
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
            CURLOPT_POSTFIELDS =>  $filterdatevar,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: BITRIX_SM_SALE_UID=11; qmb=.'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response, true);
        $responseResult =  $response['result'];
        foreach ($responseResult as $result) {
            $date_start = $result['DATE_START'];
            $date_end = $result['DATE_STOP'];
            if ($date_start >= $Filterdate and  $date_end <= $enddateUi) {
                $mainarra_formatted_by_id[$result['USER_ID']][] = $result;
            }
        }
        //   return response()->json(array('success' => true, 'data' => $mainarra_formatted_by_id));        
        // if (empty($mainarra_formatted_by_id)) {
        //     return response()->json(array('success' => true, 'data' => "Empty" ));
        // }
        function convertMinutesToDecimal($minutes)
        {
            return $minutes / 60;
        }

        $count = 1;
        $loophere = 0;
        foreach ($mainarra_formatted_by_id as $key => $item) {
            $userNameHere = Getusername($key);
            $usernameHereResponse = json_decode($userNameHere, true);
            $data = $usernameHereResponse;
            $firstname = $data['result'][0]['NAME'];
            $lastName = $data['result'][0]['LAST_NAME'];
            $emailID = $data['result'][0]['EMAIL'];
            $userInfor['firstname'] = $firstname;
            $userInfor['lastname'] = $lastName;
            $userInfor['email'] = $emailID;
            $newarray = $mainarra_formatted_by_id[$key];
            $minutes  = 0;
            foreach ($newarray as $newarr) {
                $minutesdata = $newarr["MINUTES"];
                $minutes = $minutes + $minutesdata;
            }
            $minutes = convertMinutesToDecimal($minutes);
            $minutes = round($minutes, 2);
            $datahere[$loophere]['Inforarray'] = $userInfor;
            $datahere[$loophere]['totalMinutes'] = $minutes;
            $datahere[$loophere]['KeyID'] = $key;
            $datahere[$loophere]['startdate'] = $Filterdate;
            $datahere[$loophere]['enddate'] = $enddateUi;
            $loophere++;
        }
        return response()->json(array('success' => true, 'data' => $datahere));
    }
    public function getTimetrackingSingleData(Request $request, $id)
    {
        $idnew =  explode("+", $id);
        $id = $idnew[0];
        $date = $idnew[1];
        $enddateUI = $idnew[2];
        $name = $idnew[3];
        $name = str_replace("-", " ", $name );
        // dd($enddate);
        $filterdatecopy = $date;
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
            if ($date_start >=  $filterdatecopy and $date_stop <= $enddateUI) {
                $taskID = $value['TASK_ID'];
                $taskname = $this->getTaskTitle($taskID);
                $value['TaskName'] =  $taskname[0];
                $value['name']=$name;
                $project_idHere =  $taskname[1];
                $ProjectNameFromresponse =  $this->GetProjectName($project_idHere);
                $value['projectname'] = $ProjectNameFromresponse;
                $newmainarray[] = $value;
              
            }
        }
        $newResponse =  $newmainarray;
        // dd($newResponse );
        return view('Adminview.Timetracking.Showusertime', compact('newResponse'));
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
      //  print_r($response); die();
        if(isset($response['result'][0]['NAME'])){
           $Projectname = $response['result'][0]['NAME'];   
           return  $Projectname;
        }
        return "Project not assigned";
    }
    
    
    
    
    
    
    
    
    
    
    public function getTimetrackingSinglePDF(Request $request, $id){
      //  dd($request);
        $idnew =  explode("+", $id);
        $id = $idnew[0];
        $date = $idnew[1];
        $enddateUI = $idnew[2];
        // dd($enddate);
        $filterdatecopy = $date;
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
            if ($date_start >=  $filterdatecopy and $date_stop <= $enddateUI) {
                $taskID = $value['TASK_ID'];
                $taskname = $this->getTaskTitle($taskID);
                $value['TaskName'] =  $taskname[0];
                $project_idHere =  $taskname[1];
                $ProjectNameFromresponse =  $this->GetProjectName($project_idHere);
                $value['projectname'] = $ProjectNameFromresponse;
                $newmainarray[] = $value;
              
            }
        }
        $newResponse =  $newmainarray;
        // dd($newResponse );
    
    
         $html = view('dowloadasPdf.singletimetrackingreport',compact('newResponse'))->render();
                      $html .= '<link type="text/css" href="https://files.codeconspirators.com/_resources/proposal/style.css" rel="stylesheet" />';
                      $pdf=  PDF::loadHTML($html)->setPaper('a4', 'landscape')->setWarnings(false)->
                      setOptions(['defaultFont' => 'sans-serif'])->setOptions(['isRemoteEnabled' => false]);
                      return $pdf->download('Single-TimeTracking.pdf');
                      
       // return view('dowloadasPdf.singletimetrackingreport', compact('newResponse'));

    }
}
