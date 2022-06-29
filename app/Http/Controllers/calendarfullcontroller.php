<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;


use App\Http\Controllers\ApiController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use GrahamCampbell\ResultType\Result;
use Hamcrest\Description;
use Mail;




class calendarfullcontroller extends Controller
{
    public function __construct()
      {
        $this->middleware('auth');
     }

   public function calendar(){

   //fullcalendar starts
    $data_array = array();
       $user = Auth::user();
       $userid=$user->id;
       $log = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
       $project_id = DB::select('select project_id from projects where user_id =' . $log);
 
    for ($i = 0; $i < count($project_id); $i++) {
      $id = $project_id[$i]->project_id;
      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/calendar.event.get',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{"type": "group","ownerId":"' . $id . '","from":"2020-05-01"}',
        CURLOPT_HTTPHEADER => array(
          'Content-Type: application/json',
          'Cookie: BITRIX_SM_SALE_UID=0; qmb=.'
        ),
      ));

      $response = curl_exec($curl);
      curl_close($curl);
      $response1 =  json_decode($response);
      //2 3 5

      $countedarray = count($response1->result);


      for ($k = 0; $k < $countedarray; $k = $k + 1) {
        $idnumber = $response1->result[$k]->ID;

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/calendar.event.getbyid',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => '{"id":"' . $idnumber . '"}',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Cookie: BITRIX_SM_SALE_UID=0; qmb=.'
          ),
        ));


        $response = curl_exec($curl);
        // dd($response);
        curl_close($curl);
        $response = json_decode($response);

        // check title if milestone or not
        if (strpos($response->result->NAME, "milestone") === false) {
        $eventid = $response->result->ID;
        $name = $response->result->NAME;
        $date_from =  date('Y-m-d', strtotime($response->result->DATE_FROM));
        $date_to = date('Y-m-d', strtotime($response->result->DATE_TO));
        $eventdescription = $response->result->DESCRIPTION;
        $resourceId = 1;
        if (!empty($eventdescription)) {
          preg_match('#\[(.*?)\]#', $eventdescription, $match);
          $founddata = $match[1];
          $eventdescription = substr($founddata, 4);
          $new = array($name, $date_from, $date_to, $eventid,  $eventdescription, $resourceId);
          array_push($data_array, $new);
        } else {
          $founddata = "#";
          $new = array($name, $date_from, $date_to, $eventid,  $founddata, $resourceId);
          array_push($data_array, $new);
        }
          
        }else{

          $eventid = $response->result->ID;
          $name = $response->result->NAME;
          $date_from =  date('Y-m-d', strtotime($response->result->DATE_FROM));
          $date_to = date('Y-m-d', strtotime($response->result->DATE_TO));
          $eventdescription = $response->result->DESCRIPTION;
          $resourceId = 3;
          if (!empty($eventdescription)) {
            // preg_match('#\[(.*?)\]#', $eventdescription, $match);
            // $founddata = $match[1];
            // $eventdescription = substr($founddata, 4);
            $new = array($name, $date_from, $date_to, $eventid,  $eventdescription, $resourceId);
            array_push($data_array, $new);
          } else {
            $founddata = "not available";
            $new = array($name, $date_from, $date_to, $eventid,  $founddata, $resourceId);
            array_push($data_array, $new);
          }
        }



       
     
      }


    }
    
    //  print_r($project_id);
    $project = array();
    for ($i = 0; $i < count($project_id); $i++) {

      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/tasks.task.list",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => '{"filter":{
              "GROUP_ID":"' . $project_id[$i]->project_id . '",
              "%TITLE":"onboarding client"
            }
          }',
        CURLOPT_HTTPHEADER => array(
          "cache-control: no-cache",
          "content-type: application/json",
          "postman-token: aa484415-9e65-dd3b-29fc-3ec6b4780d9e"
        ),
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);

      if ($err) {
        echo "cURL Error #:" . $err;
      } else {
        //  echo $response;
      }
      $onboard = json_decode($response);

      if ($onboard->result->tasks != null) {
        $start = $onboard->result->tasks[0]->startDatePlan;
        $end = $onboard->result->tasks[0]->endDatePlan;
        $time1 = new \DateTime($start);
        $start = $time1->format('M d, Y');
        $time1 = new \DateTime($end);
        $end = $time1->format('M d, Y');
      } else {
        $start = "Not Defined";
        $end = "Not Defined";
      }

      $new = array("project_id" => $project_id[$i]->project_id, "start_date" => $start, "end_date" => $end);
      array_push($project, $new);
    }

    
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/tasks.task.list",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => '{	
          "filter":{		
            "UF_AUTO_697796185795":1	
          }	
          }',
      CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: application/json",
        "postman-token: 9b0ab35a-03b0-321e-82d6-f505f8c98208"
      ),
    ));
    $response = curl_exec($curl);

    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
      // echo "cURL Error #:" . $err;	
    } else {
      //  echo $response;	
    }
    $things = json_decode($response);
    

    //things we need
    for ($i = 0; $i < count($project); $i++) {
      for ($j = 0; $j < count($things->result->tasks); $j++) {
        if ($things->result->tasks[$j]->groupId == $project[$i]['project_id']) {

          $name = $things->result->tasks[$j]->title;
          $date_from =  date('Y-m-d', strtotime($things->result->tasks[$j]->createdDate));
          $date_to = date('Y-m-d', strtotime($things->result->tasks[$j]->createdDate));
          $eventid = $things->result->tasks[$j]->id;
          $eventdescription = "";
          $resourceId = 2;
          $new = array($name, $date_from, $date_to, $eventid, $eventdescription, $resourceId);
          array_push($data_array, $new);
        }
      }
    }

    for ($i = 0; $i < count($data_array); $i++) {
      $data[] = array(
        'id'   =>  $data_array[$i][3],
        'title'   => $data_array[$i][0],
        'start'   => date('Y-m-d', strtotime($data_array[$i][1])),
        'end'   => date('Y-m-d', strtotime($data_array[$i][2])),
        'description' => $data_array[$i][4],
        'resourceid' => $data_array[$i][5],
        'color'=>'#c72027',     
        'textColor'=> '#ffffff'
      );
    }

      echo json_encode($data);
   }
}

