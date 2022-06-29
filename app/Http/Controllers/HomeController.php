<?php
namespace App\Http\Controllers;
//use Illuminate\Http\Request;
//use App\Http\Controllers\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;	
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;
use DB;
use Mail;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    
    
    public function index()
    {
      $apicontroller = new ApiController;
      $invoice_result = $apicontroller->quickbook_invoice_api();  
        
     $log= Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
    
    //ticked raised by  users	
    $user = Auth::user();	
    $curl = curl_init();	
    curl_setopt_array($curl, array(	
      CURLOPT_URL => 'https://cc.codeconspirators.com/rest/22/n0iixre6ucmshyad/tasks.task.list',	
      CURLOPT_RETURNTRANSFER => true,	
      CURLOPT_ENCODING => '',	
      CURLOPT_MAXREDIRS => 10,	
      CURLOPT_TIMEOUT => 0,	
      CURLOPT_FOLLOWLOCATION => true,	
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,	
      CURLOPT_CUSTOMREQUEST => 'POST',	
      CURLOPT_POSTFIELDS => '{
	"filter":	
    {	
        "GROUP_ID":34,	
        "UF_AUTO_226162412347":"'.$user->email.'"	
        },
    "order":{
        "REAL_STATUS":"asc"
    }
}',	
      CURLOPT_HTTPHEADER => array(	
        'Content-Type: application/json',	
        'Cookie: BITRIX_SM_SALE_UID=0; qmb=.'	
      ),	
    ));	
    $response = curl_exec($curl);	
    curl_close($curl);	
    	
   $response = json_decode($response,true);	
   $response = $response['result']['tasks'];	
   $ticketstatus =$response;

    
    
        $res= DB::select('select name from users where id =' .$log);
        $name=json_encode($res,true);
        $name1=json_decode($name,true);
        $project_id = DB::select('select project_id from projects where user_id =' .$log);
     //  print_r($project_id);
      $project = array();
       for($i=0;$i<count($project_id);$i++){
    
          $curl = curl_init();

          curl_setopt_array($curl, array(
            CURLOPT_URL => "https://cc.codeconspirators.com/rest/22/n0iixre6ucmshyad/tasks.task.list",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => '{"filter":{
              "GROUP_ID":"'.$project_id[$i]->project_id.'",
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
          
          if($onboard->result->tasks != null){
            $start =$onboard->result->tasks[0]->startDatePlan;
            $end =$onboard->result->tasks[0]->endDatePlan;
            $time1 = new \DateTime($start);
            $start = $time1->format('M d, Y');
            $time1 = new \DateTime($end);
            $end = $time1->format('M d, Y');
  
          }
          else{
            $start = "Not Defined";
            $end = "Not Defined";
          }
          
          $new = array("project_id"=>$project_id[$i]->project_id,"start_date"=>$start,"end_date"=>$end);
          array_push($project,$new);

    }
    //  print_r($project);
      
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://cc.codeconspirators.com/rest/22/n0iixre6ucmshyad/tasks.task.list",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "",
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
        $task = json_decode($response); 
     
      $curl = curl_init();	
        curl_setopt_array($curl, array(	
        CURLOPT_URL => "https://cc.codeconspirators.com/rest/22/n0iixre6ucmshyad/tasks.task.list",	
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

     
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://cc.codeconspirators.com/rest/22/n0iixre6ucmshyad/sonet_group.get",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "",
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: application/json",
            "postman-token: a2cf7e60-e379-22fa-9533-8f0f679d33ee"
          ),
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          //echo $response;
        }
        $projects = json_decode($response);

        $meet_det = array();
        $arr =array();
        $act = array();
    for($i=0;$i<count($project_id);$i++){
     $id = $project_id[$i]->project_id;
     $meet_det = get_meetings($id);
     $activity = get_activity($id);
     array_push($arr,$meet_det);
     array_push($act,$activity);
    }  

     $invoice_result = json_decode($invoice_result,true);
      return view('dashboard',['task_list'=>$task],['projects'=>$projects])->with('meeting_details',$arr)->with('msg',$act)->with('project',$project)->with('things',$things)->with('ticketstatus', $ticketstatus)->with('invoice_result',$invoice_result);
    }
    
    public function message(){
      $log= Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
      $project = DB::select('select project_id from projects where user_id =' .$log);

      $act = array();
      for($i=0;$i<count($project);$i++){
        $id = $project[$i]->project_id;
        $activity = get_activity($id);
        array_push($act,$activity);
       }
       
       return view('message')->with('msg',$act);
    }
    
       public function HomeLinks()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.uptimerobot.com/v2/getMonitors",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "api_key=ur1425284-42038af0da7727944a2b0ec9&format=json&logs=1&all_time_uptime_durations=1&custom_uptime_ratios=1-7-30&monitors=789082657",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            // echo "cURL Error #:" . $err;
            exit;
        } else {
            $response =  json_decode($response,true);
            $data=$response['monitors'][0];
            // $newarray=
            // print_r($data['all_time_uptime_durations']);
            // print_r($data['custom_uptime_ratio']);

            return response()->json([
                "result" => $data
            ]);


        }
    }
    
    public function support()
  {
    $log = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');

    //ticked raised by  users	
    $user = Auth::user();
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://cc.codeconspirators.com/rest/22/n0iixre6ucmshyad/tasks.task.list',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => '{
	"filter":	
    {	
        "GROUP_ID":34,	
        "UF_AUTO_226162412347":"'.$user->email.'"	
        },
    "order":{
        "REAL_STATUS":"asc"
    }
}',
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'Cookie: BITRIX_SM_SALE_UID=0; qmb=.'
      ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);

    $response = json_decode($response, true);
    $response = $response['result']['tasks'];
    $ticketstatus = $response;



    $res = DB::select('select name from users where id =' . $log);
    $name = json_encode($res, true);
    $name1 = json_decode($name, true);
    $project = DB::select('select project_id from projects where user_id =' . $log);
    //  print_r($project);

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://cc.codeconspirators.com/rest/22/n0iixre6ucmshyad/tasks.task.list",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "",
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
    $task = json_decode($response);

    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://cc.codeconspirators.com/rest/22/n0iixre6ucmshyad/tasks.task.list",
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


    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://cc.codeconspirators.com/rest/22/n0iixre6ucmshyad/sonet_group.get",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "",
      CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: application/json",
        "postman-token: a2cf7e60-e379-22fa-9533-8f0f679d33ee"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      //echo $response;
    }
    $projects = json_decode($response);

    $meet_det = array();
    $arr = array();
    $act = array();
    for ($i = 0; $i < count($project); $i++) {
      $id = $project[$i]->project_id;
      $meet_det = get_meetings($id);
      $activity = get_activity($id);
      array_push($arr, $meet_det);
      array_push($act, $activity);
    }

    
    return view('support', ['task_list' => $task], ['projects' => $projects])->with('meeting_details', $arr)->with('msg', $act)->with('project', $project)->with('things', $things)->with('ticketstatus', $ticketstatus);
  }

   

  public function project()
  {
    $log = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
    $project_id = DB::select('select project_id from projects where user_id =' .$log);
     //  print_r($project_id);
      $project = array();
       for($i=0;$i<count($project_id);$i++){
    
          $curl = curl_init();

          curl_setopt_array($curl, array(
            CURLOPT_URL => "https://cc.codeconspirators.com/rest/22/n0iixre6ucmshyad/tasks.task.list",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => '{"filter":{
              "GROUP_ID":"'.$project_id[$i]->project_id.'",
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
          
          if($onboard->result->tasks != null){
            $start =$onboard->result->tasks[0]->startDatePlan;
            $end =$onboard->result->tasks[0]->endDatePlan;
            $time1 = new \DateTime($start);
            $start = $time1->format('M d, Y');
            $time1 = new \DateTime($end);
            $end = $time1->format('M d, Y');
  
          }
          else{
            $start = "Not Defined";
            $end = "Not Defined";
          }
          
          $new = array("project_id"=>$project_id[$i]->project_id,"start_date"=>$start,"end_date"=>$end);
          array_push($project,$new);

    }
    
     $curl = curl_init();
 
     curl_setopt_array($curl, array(
       CURLOPT_URL => "https://cc.codeconspirators.com/rest/22/n0iixre6ucmshyad/tasks.task.list",
       CURLOPT_RETURNTRANSFER => true,
       CURLOPT_ENCODING => "",
       CURLOPT_MAXREDIRS => 10,
       CURLOPT_TIMEOUT => 30,
       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
       CURLOPT_CUSTOMREQUEST => "POST",
       CURLOPT_POSTFIELDS => '',
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
     $task_list= json_decode($response);
     //projects
     
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://cc.codeconspirators.com/rest/22/n0iixre6ucmshyad/sonet_group.get",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "",
      CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: application/json",
        "postman-token: a2cf7e60-e379-22fa-9533-8f0f679d33ee"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      //echo $response;
    }

    $projects = json_decode($response);
     return   view('project')->with('task_list', $task_list)->with('projects', $projects)->with('project', $project);
     
}
}



function get_meetings($id){

    $arr = array();
    
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => "https://cc.codeconspirators.com/rest/22/n0iixre6ucmshyad/calendar.event.get",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
     CURLOPT_POSTFIELDS => '{"type": "group","ownerId": "'.$id.'","from":"'.date("Y-m-d").'"}',
    CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: application/json",
        "postman-token: 60109662-6a49-2a21-61f2-27c02342a949"
    ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
    echo "cURL Error #:" . $err;
    } else {
//       echo $response;
    }

    $meeting = json_decode($response);
    
    if(count($meeting->result)!=0){
        for($i=0;$i<count($meeting->result);$i++){
            $meet_id = $meeting->result[$i]->ID;    

          $curl = curl_init();

          curl_setopt_array($curl, array(
            CURLOPT_URL => "https://cc.codeconspirators.com/rest/22/n0iixre6ucmshyad/calendar.event.getbyid",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => '{"id":"'.$meet_id.'"}',
            CURLOPT_HTTPHEADER => array(
              "cache-control: no-cache",
              "content-type: application/json",
              "postman-token: 67a5d7e1-9891-8a4c-2cd8-43c5b42cab03"
            ),
          ));

          $response = curl_exec($curl);
          $err = curl_error($curl);

          curl_close($curl);

          if ($err) {
            echo "cURL Error #:" . $err;
          } else {
//            echo $response;
          }
          $meet = json_decode($response);
          $link = $meet->result->DESCRIPTION;
          $newDate = date("m/d/Y h:ia", strtotime($meeting->result[$i]->DATE_FROM));
          $new = array("name" => $meeting->result[$i]->NAME, "date_from" => $newDate, "link" => $link);
          array_push($arr,$new);
        }
    }

    return $arr;
}

function get_activity($id){
  $arr = array();
  $curl = curl_init();
  curl_setopt_array($curl, array(
  CURLOPT_URL => "https://cc.codeconspirators.com/rest/22/n0iixre6ucmshyad/log.blogpost.get",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => '{"LOG_RIGHTS":["SG'.$id.'"]}',
  CURLOPT_HTTPHEADER => array(
      "cache-control: no-cache",
      "content-type: application/json",
      "postman-token: 070705f1-0268-04f3-8ebc-7f7596c672f5"
  ),
  ));
  $response = curl_exec($curl);
  $err = curl_error($curl);
  curl_close($curl);
  if ($err) {
//    echo "cURL Error #:" . $err;
  } else {
 //         echo $response;
  }
  $activity = json_decode($response);
  
  for($i=0;$i<count($activity->result);$i++){
      if($activity->result[$i]->UF_BLOG_POST_FILE->VALUE){
      $title = $activity->result[$i]->TITLE;
      $detailtext = $activity->result[$i]->DETAIL_TEXT;
      $date = $activity->result[$i]->DATE_PUBLISH;

      $str = $detailtext ;
      $stt = explode("//", $str);
      $links = array();

      for ($firstloop = 0; $firstloop < count($stt); $firstloop++) {
        if (strpos($stt[$firstloop], "[/VIDEO]") !== false) {
          $word = $stt[$firstloop];

          $link = "";
          for ($secondlooop = 0; $secondlooop < strlen($word); $secondlooop++) {
            $link = $link . $word[$secondlooop];
            if ($word[$secondlooop] == "[") {
              array_push($links, $link);
              break;
            }
          }
        }
      }

      if (!empty($links)) {
        $link = $links[0];
      } else {
        $link = "";
      }

      //for link above for text below;
      $re = '/\[VIDEO.*\[\/VIDEO\]/m';
      $str = $detailtext;
      $subst = '';
      $result = preg_replace($re, $subst, $str);
      // echo $result;
      $re = '/\[/m';
      $str = $result;
      $subst = '<';
      $result = preg_replace($re, $subst, $str);
      $re = '/\]/m';
      $str = $result;
      $subst = '>';
      $result = preg_replace($re, $subst, $str);
          
          
          $fileId = $activity->result[$i]->UF_BLOG_POST_FILE->VALUE[0];
          $curl = curl_init();
          curl_setopt_array($curl, array(
          CURLOPT_URL => "https://cc.codeconspirators.com/rest/22/n0iixre6ucmshyad/disk.attachedObject.get?id=".$fileId,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_POSTFIELDS => "",
          CURLOPT_HTTPHEADER => array(
              "cache-control: no-cache",
              "content-type: application/json",
              "postman-token: f7b8beb6-8f0b-94c6-c479-f8f561fdd395"
          ),
          ));
          $response = curl_exec($curl);
          $err = curl_error($curl);
          curl_close($curl);
          if ($err) {
          echo "cURL Error #:" . $err;
          } else {
      //    echo $response;
          }
          $attachobj = json_decode($response);
          $down = $attachobj->result->DOWNLOAD_URL;
          $name = $attachobj->result->NAME;
          $file_obj = file_get_contents($down);
          
          
          $dir = public_path()."/files//".$name;
          if(!file_exists($dir)){
            file_put_contents($dir,$file_obj);
          }
          
          $new = array("title"=>$title,"file"=>$name,"date"=>$date);
          array_push($arr,$new);
      }
      else{
        
      $title = $activity->result[$i]->TITLE;
      $date = $activity->result[$i]->DATE_PUBLISH;
      $detailtext= $activity->result[$i]->DETAIL_TEXT;

      
      $str = $detailtext;
      $stt = explode("//", $str);
      $links = array();

      for ($firstloop = 0; $firstloop < count($stt); $firstloop++) {
        if (strpos($stt[$firstloop], "[/VIDEO]") !== false) {
          $word = $stt[$firstloop];

          $link = "";
          for ($secondlooop = 0; $secondlooop < strlen($word); $secondlooop++) {
            $link = $link . $word[$secondlooop];
            if ($word[$secondlooop] == "[") {
              array_push($links, $link);
              break;
            }
          }
        }
      }

      if (!empty($links)) {
        $link = $links[0];
      } else {
        $link = "";
      }
      $re = '/\[VIDEO.*\[\/VIDEO\]/m';
      $str = $detailtext;
      $subst = '';
      $result = preg_replace($re, $subst, $str);
      $re = '/\[/m';
      $str = $result;
      $subst = '<';
      $result = preg_replace($re, $subst, $str);
      $re = '/\]/m';
      $str = $result;
      $subst = '>';
      $result = preg_replace($re, $subst, $str);

      $new = array("title" => $title, "file" => "", "date" => $date, "youtubelink" =>$link,"detailtext"=> $result);
      array_push($arr, $new);
      }
    }
//      print_r($arr);
    return $arr;   
}
