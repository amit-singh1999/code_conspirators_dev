<?php
namespace App\Http\Controllers;
use DB;
use Hash;
use Mail;
use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


//use Notification;

class EmailNotificationController extends Controller
{
    
    public function sendnotification()
    {   
           
        $res = $_REQUEST;
       // file_put_contents("art.txt",json_encode($res),FILE_APPEND);
        
        
     //   pre($_REQUEST);
        die();
        echo $log = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
        $res = DB::select('select name from users where id =' . $log);
        $name = json_encode($res, true);
        $name1 = json_decode($name, true);
        $project = DB::select('select project_id from projects where user_id =' . $log);
        $act = array();
        for ($i = 0; $i < count($project); $i++) {
            $id = $project[$i]->project_id;
             //   $meet_det = get_meetings($id);
            $activity = get_activity($id);
            //array_push($arr, $meet_det);
            array_push($act, $activity);
        }
       // pre($project_id);


    }
}
function pre($arr)
{
    echo "<pre>";
 print_r($arr);
    echo "</pre>";
}
function get_activity($id)
{
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
        CURLOPT_POSTFIELDS => '{"LOG_RIGHTS":["SG' . $id . '"]}',
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
pre($activity);
    for ($i = 0; $i < count($activity->result); $i++) {
        if ($activity->result[$i]->UF_BLOG_POST_FILE->VALUE) {
            $title = $activity->result[$i]->TITLE;
            $detailtext = $activity->result[$i]->DETAIL_TEXT;
            $date = $activity->result[$i]->DATE_PUBLISH;

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
                CURLOPT_URL => "https://cc.codeconspirators.com/rest/22/n0iixre6ucmshyad/disk.attachedObject.get?id=" . $fileId,
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


            $dir = public_path() . "/files//" . $name;
            if (!file_exists($dir)) {
                file_put_contents($dir, $file_obj);
            }

            $new = array("title" => $title, "file" => $name, "date" => $date);
            array_push($arr, $new);
        } else {

            $title = $activity->result[$i]->TITLE;
            $date = $activity->result[$i]->DATE_PUBLISH;
            $detailtext = $activity->result[$i]->DETAIL_TEXT;


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

            $new = array("title" => $title, "file" => "", "date" => $date, "youtubelink" => $link, "detailtext" => $result);
            array_push($arr, $new);
        }
    }
//      print_r($arr);
    return $arr;
}

function gettasks($id)
{
    $curl=curl_init();
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

}

function support()
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
        "UF_AUTO_226162412347":"' . $user->email . '"
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
    for ($i = 0; $i < count($projects); $i++) {
        $id = $projects[$i]->project_id;
        $meet_det = get_meetings($id);
        $activity = get_activity($id);
        array_push($arr, $meet_det);
        array_push($act, $activity);
    }


    return view('support', ['task_list' => $task], ['projects' => $projects])->with('meeting_details', $arr)->with('msg', $act)->with('project', $project)->with('things', $things)->with('ticketstatus', $ticketstatus);
}