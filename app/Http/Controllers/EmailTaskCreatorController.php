<?php

namespace App\Http\Controllers;

use DB;
use Mail;
use DateTime;

class EmailTaskCreatorController extends Controller
{

    public function index()
    {
        if (!function_exists('imap_open')) {
            echo "IMAP is not configured.";
            exit();
        } else {
            /* Connecting with IMAP f*/
            $connection = imap_open('{secure.emailsrvr.com:993/imap/ssl}INBOX', 'support-bitrix@codeconspirators.com', '27/AP\rmENcNqcw$') or die('Cannot connect to imap: ' . imap_last_error());
            /* Search Emails having status set to UNSEEN */
            $emailData = imap_search($connection, 'UNSEEN');

            $i = 0;
            $resultdata = $this->checkproject();
            if (!empty($emailData)) {
                //   $count = 1;
                foreach ($emailData as $emailIdent) {
                    $i++;
                    $email_flag = 1;
                    // Get email address
                    $header = imap_header($connection, $i); // get first mails header
                    //pre($header);
                    $mailbox = $header->to[0]->mailbox;
                    $namefetch = explode("-", $mailbox);
                    $supportproj = $namefetch[1];
                    //pre($mailbox);

                    if (str_contains($mailbox, 'support')) {

                        foreach ($resultdata as $resp) {
                            $project = $resp["NAME"];
                            $projectID = $resp["ID"];
                            $project = explode(": ", $project);

                            if (count($project) > 1) {
                                $project = $project[0];
                                $project = strtolower(trim($project));


                                if ($project == $supportproj) {
                                    $createtaskonly = 1;
                                    goto terminateLoop;


                                } else
                                    $createtaskonly = 2;
                            }


                        }
                        terminateLoop:
                        if ($createtaskonly == 1) {

                            $overview = imap_fetch_overview($connection, $emailIdent, 0);

                            $extension = imap_fetchstructure($connection, $emailIdent);
                            //pre($extension);
                            $att = 2;
                            if (isset($extension->parts[0]->parts)) {
                                $att = 1;

                            }
                            $message = imap_fetchbody($connection, $emailIdent, $att);
                            //pre($message);

                            $newmsg = quoted_printable_decode($message);

                            //pre($newmsg);

                            $newmsg_copy = quoted_printable_decode($message);
                            $messageExcerpt = substr($message, 0, 150);
                            $partialMessage = trim(quoted_printable_decode($messageExcerpt));
                            /*$tzone = new \DateTimeZone('America/New_York');
                            $date = new DateTime(null,$tzone);
                            $datenow = $date->format('m/d/Y H:i');*/
                            $timezone = date_default_timezone_get();
                            $datenow = date('m/d/Y h:i:s a', time());
                            $subject = $overview[0]->subject. "-" . $datenow;
                            $from = $overview[0]->from;
                            $split = explode(' <', $from);
                            $name = $split[0]; //for extracting name from contact

                            $email = rtrim($split[1], '>'); //for extracting email from contact

                            $document = new \DOMDocument();
                            // set error level
                            $internalErrors = libxml_use_internal_errors(true);

                            $document->loadHTML($newmsg_copy, LIBXML_HTML_NOIMPLIED);
                            $test = $document->getElementsByTagName('div');
                            if ($document->getElementById('Signature'))
                                $document->getElementById('Signature')->nodeValue = '';
                            $xpath = new \DOMXpath($document);
                            if ($xpath->query('//div[contains(@class, "gmail_signature")]')) { //instance of DOMNodeList
                                $elementsByClass = $xpath->query('//div[contains(@class, "gmail_signature")]');
                                foreach ($elementsByClass as $class) {
                                    $class->nodeValue = '';
                                }
                            }
                            $arr = $test[0]->textContent;
                            $newmsg_copy = $arr;
                            $content = "";
                            $content = lastNode($test[0]);
                            $newmsg_copy = $content;
                            $pattern = '/Â/i';
                            $newmsg_copy = preg_replace($pattern, "", $newmsg_copy);
                            $newmsg_copy = str_replace(array("\r", "\n"), '', $newmsg_copy);
                            $structure = imap_fetchstructure($connection, $emailIdent);
                            // pre($structure);
                            $imgattach = array();
                            $attachments = array();
                            $filename = array();


                            //$filename = $structure->filename["value"];


                            /* if any attachments found... */
                            if (isset($structure->parts) && count($structure->parts)) {

                                for ($i = 0; $i < count($structure->parts); $i++) {
                                    $attachments[$i] = array(
                                        'is_attachment' => false,
                                        'filename' => '',
                                        'name' => '',
                                        'attachment' => ''
                                    );

                                    //echo "on line 130";
                                    if ($structure->parts[$i]->ifdparameters) //echo "on line 130 inside if";
                                    {
                                        foreach ($structure->parts[$i]->dparameters as $object) {
                                            if (strtolower($object->attribute) == 'filename') {
                                                $attachments[$i]['is_attachment'] = true;
                                                $attachments[$i]['filename'] = $object->value;
                                                //print_r($attachments[$i]['filename']);
                                            }
                                        }
                                    }

                                    if ($structure->parts[$i]->ifparameters) {
                                        // echo "on line 144 inside if";
                                        foreach ($structure->parts[$i]->parameters as $object) {
                                            //  echo "on line 147 inside if";
                                            if (strtolower($object->attribute) == 'name') {
                                                // echo "on line 148 inside if";
                                                $attachments[$i]['is_attachment'] = true;
                                                $attachments[$i]['name'] = $object->value;
                                                $filename[] = $attachments[$i]['name'];
                                                // print_r($filename);
                                                // exit;
                                            }
                                        }
                                    }

                                    if ($attachments[$i]['is_attachment']) {
                                        $attachments[$i]['attachment'] = imap_fetchbody($connection, $emailIdent, $i + 1);

                                    }
                                }
                            }

                            // exit;

                            foreach ($attachments as $attach) {
                                if ($attach['attachment'] != null) {
                                    $imgattach[] = $attach['attachment'];

                                }
                            }
                        }
                        // pre($newmsg_copy);die();
                        task($name, $email, $newmsg_copy, $subject, $imgattach, $filename, $projectID);


                    }


                    imap_close($connection);
                }


            }


        }
    }


    public function checkproject()
    {
        /* fetch list of sonet group to see if sonet group available*/

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://cc.codeconspirators.com/rest/22/sqvy6dfkagsp6z8n/sonet_group.get',
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
        $response = $response['result'];
        return $response;

    }

    public function getsupportcomments()
    {
        // die(123);
        //echo 123;
        /* $date = new DateTime();
         $timeZone = $date->getTimezone();
         $timezone= $timeZone->getName();
         */
        $dir = storage_path() . '/logs/test12.txt';

        file_put_contents($dir, json_encode($_REQUEST), FILE_APPEND | LOCK_EX);
        $data = json_decode(json_encode($_REQUEST), true);
        $taskid = $data['data']['FIELDS_AFTER']['TASK_ID'];

        //$taskid=2581;
        //$req='{"event":"ONTASKCOMMENTADD","data":{"FIELDS_BEFORE":"undefined","FIELDS_AFTER":{"ID":"6453","TASK_ID":"2581"},"IS_ACCESSIBLE_BEFORE":"N","IS_ACCESSIBLE_AFTER":"undefined"},"ts":"1652439800","auth":{"domain":"cc.codeconspirators.com","client_endpoint":"https:\/\/cc.codeconspirators.com\/rest\/","server_endpoint":"https:\/\/oauth.bitrix.info\/rest\/","member_id":"567ecb31de3c003c8869ff7f2c4270ed","application_token":"al6wyk3f75c96e6r09it75zvqznljf1v"}}';
        //$req=json_decode($req,true); 
        //dd($req);

        //$taskId = $_REQUEST['data']['FIELDS_AFTER']['TASK_ID'];
        file_put_contents($dir, $taskid, FILE_APPEND | LOCK_EX);
        $results = DB::select('select task_id from support_email_details where status = 1');
        $tasklist = array();
        foreach ($results as $result)
            $tasklist[] = $result->task_id;
        if (in_array($taskid, $tasklist)) {
            $curl = curl_init();
            echo $taskid;
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://cc.codeconspirators.com/rest/22/sqvy6dfkagsp6z8n/task.commentitem.getlist?id=' . $taskid,
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

            $resp = json_decode($response, true);
            $comments = $resp["result"];
            $node = count($comments) - 1;
            $commentdata = $comments[$node];

            $result = DB::select('select * from support_email_details where task_id =' . $taskid);
            //dd($result);
            $result = $result[0];
            $email = $result->email;
            $task_title = $result->task_title;
            $clientname = $result->client_name;
            $commentid = $commentdata["ID"];
            echo $commenter = $commentdata["AUTHOR_NAME"];
            echo $comment = $commentdata["POST_MESSAGE"];

            send_email_on_comment($email, $task_title, $clientname, $comment, $commenter);


        }
    }

    public function getcompletedtask()
    {
        // die(123);
        //echo 123;
        /* $date = new DateTime();
         $timeZone = $date->getTimezone();
         $timezone= $timeZone->getName();
         */
        $dir = storage_path() . '/logs/test13.txt';

        file_put_contents($dir, json_encode($_REQUEST), FILE_APPEND | LOCK_EX);
        $data = json_decode(json_encode($_REQUEST), true);
        // $taskid = $data['data']['FIELDS_AFTER']['TASK_ID'];

        $taskid = 2581;
        //$req='{"event":"ONTASKCOMMENTADD","data":{"FIELDS_BEFORE":"undefined","FIELDS_AFTER":{"ID":"6453","TASK_ID":"2581"},"IS_ACCESSIBLE_BEFORE":"N","IS_ACCESSIBLE_AFTER":"undefined"},"ts":"1652439800","auth":{"domain":"cc.codeconspirators.com","client_endpoint":"https:\/\/cc.codeconspirators.com\/rest\/","server_endpoint":"https:\/\/oauth.bitrix.info\/rest\/","member_id":"567ecb31de3c003c8869ff7f2c4270ed","application_token":"al6wyk3f75c96e6r09it75zvqznljf1v"}}';
        //$req=json_decode($req,true); 
        //dd($req);

        //$taskId = $_REQUEST['data']['FIELDS_AFTER']['TASK_ID'];
        file_put_contents($dir, $taskid, FILE_APPEND | LOCK_EX);
        $results = DB::select('select task_id from support_email_details where status = 1');
        $tasklist = array();
        foreach ($results as $result)
            $tasklist[] = $result->task_id;
        if (in_array($taskid, $tasklist)) {
            $curl = curl_init();
            echo $taskid;
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://cc.codeconspirators.com/rest/22/sqvy6dfkagsp6z8n/tasks.task.get?id=' . $taskid,
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

            $response = json_decode($response, true);

            $taskdata = $response["result"]["task"];
         //   dd($taskdata);


            if ($taskdata["status"] == 5) {

                try {
                    $update = DB::table('support_email_details')->where("task_id", '=', $taskid)->where("status", '!=', 2)
                        ->update(['status' => 2]);
                        //dd($update);

                    if ($update==1) {
                        echo 123;
                        $result = DB::select('select * from support_email_details where status = 2 and task_id='.$taskid);
                       
                        send_email_on_complete($result[0]->email, $result[0]->task_title, $result[0]->client_name);
                    }
                } catch (\Illuminate\Database\QueryException $e) {
                    // something went wrong with the query
                } catch (\Exception $e) {
                    // something else happened
                }


                //send_email_on_comment($email, $task_title, $clientname, $comment, $commenter);


            }
        }
    }
}

function pre($arr)
{
    echo "<pre>";
    print_r($arr);
    echo "</pre>";

}

function send_email_on_complete($emailid, $tasktitle, $contactdata_Name)
{
   /* $tzone = new \DateTimeZone('America/New_York');
    $date = new DateTime(null,$tzone);
    $datenow = $date->format('m/d/Y H:i');*/
   /* $timezone = date_default_timezone_get();
    $datenow = date('m/d/Y h:i:s a', time());*/
    $subject = $tasktitle;
    //echo $emailid.$tasktitle.$contactdata_Name."123";
    //$emailid="aadilhussain1988@gmail.com"; 

    $maildata = array('email' => $emailid, 'task_title' => $subject, 'contactdata_Name' => $contactdata_Name);
    //pre()
    try {
        echo "tried";
        Mail::send('email.SupportEmail', $maildata, function ($emailMessage) use ($subject, $emailid, $contactdata_Name) {
            $emailMessage->subject($subject . ", has been completed");
            $emailMessage->to($emailid);
        });
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
}

function send_email_on_comment($emailid, $tasktitle, $contactdata_Name, $comment, $commenter)
{

    $subject = $tasktitle;

    $maildata = array('email' => $emailid, 'task_title' => $subject, 'contactdata_Name' => $contactdata_Name, 'comment' => $comment, 'commenter' => $commenter,);
    //pre()
    try {
        echo "tried";
        Mail::send('email.SupportComment', $maildata, function ($emailMessage) use ($subject, $emailid, $contactdata_Name) {
            $emailMessage->subject($subject . ", has update");
            $emailMessage->to($emailid);
        });
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
}

function task($sender, $email, $description, $subject, $imgattach, $filename, $projectID)
{ // pre($imgattach); die();
    $subject = str_replace("Fwd: ", "", $subject);
    $date = date('Y-m-d');

    $deadline = date('Y-m-d', strtotime($date . ' + 2 days'));
    $day = date('D', strtotime($deadline));
    if ($day == 'Sat') {
        $deadline = date('Y-m-d', strtotime($deadline . ' + 2 days'));
    } elseif ($day == 'Sun') {
        $deadline = date('Y-m-d', strtotime($deadline . ' + 1 days'));
    }
    $timezone = date_default_timezone_get();
    $dateNOW = date('m/d/Y h:i:s a', time());
    $subject = trim($subject);
    $description = trim(preg_replace('/\s\s+/', ' \n ', $description));
    $sub = $subject . "<br>" . $description;
    $decode = $sub;
    $decode = trim(preg_replace('<br>', '\n', $decode));
    $decode = str_replace(array('<'), ' ', $decode);
    $decode = str_replace(array('>'), ' ', $decode);
    $decode = str_replace('\n', "\n", $decode);


    $field_post = array(
        "fields" => array(
            "TITLE" => $subject,
            "RESPONSIBLE_ID" => 73,
            "DESCRIPTION" => $decode,
            "DEADLINE" => $deadline,
            "ALLOW_TIME_TRACKING" => "Y",
            "TASK_CONTROL" => "Y",
            "GROUP_ID" => $projectID
        )
    );
    //file_put_contents("everyimg.txt",$field_post,FILE_APPEND);

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://cc.codeconspirators.com/rest/22/sqvy6dfkagsp6z8n/tasks.task.add",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($field_post),
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: application/json",
            "postman-token: f7e70331-7a53-3f83-b0a9-2eca2625c2e5"
        ),
    ));
    $response = curl_exec($curl);
    $response = json_decode($response, true);
    // pre($response);
    $task_id = $response['result']['task']['id'];
    $s = 0;
    $status = 0;
    if (isset($task_id) && $task_id != "")
        $status = 1;

    $err = curl_error($curl);
    curl_close($curl);
    $imgattach = str_replace(array("\r", "\n"), '', $imgattach);
    /*added logic for adding file to task */
    if (count($imgattach) > 0) {
        foreach ($imgattach as $img) {
            $postfields = array(
                'TASK_ID' => $task_id,
                'FILE[NAME]' => $filename[$s],
                'FILE[CONTENT]' => "'.$img.'"
            );
            $postfields = $postfields;
            //pre($postfields);

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'https://cc.codeconspirators.com/rest/22/sqvy6dfkagsp6z8n/task.item.addfile');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postfields);

            $out = curl_exec($curl);
            //  pre($out);
            curl_close($curl);
        }

    }
    if ($status == 1) {

        //$datenow = $date->format('m/d/Y H:i:sP');
        $data = array('task_title' => $subject,
            'task_id' => $task_id,
            'group_id' => $projectID,
            'status' => $status,
            'client_name' => $sender,
            'lastupdated_on' => now(),
            'email' => $email

        );
        DB::table('support_email_details')->insertOrIgnore($data);
    }

}


function lastNode($divtag)
{
    if ($divtag->hasChildNodes()) {
        $content = '';
        foreach ($divtag->childNodes as $child) {
            // $content.=lastNode($child);
            $content .= lastNode($child);
        }
        return $content;
    }
    if ($divtag->textContent)
        return "<br>" . $divtag->textContent;

    return $divtag->textContent;
}

function build_post_fields($data, $existingKeys = '', $returnArray = [])
{
    if (($data instanceof CURLFile) or !(is_array($data) or is_object($data))) {
        $returnArray[$existingKeys] = $data;
        return $returnArray;
    } else {
        foreach ($data as $key => $item) {
            build_post_fields($item, $existingKeys ? $existingKeys . "[$key]" : $key, $returnArray);
        }
        return $returnArray;
    }
}

function get_completed_project($groupid)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/tasks.task.list',
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
        "GROUP_ID":' . $groupid . ',
        "REAL_STATUS":5
                },
         "select": ["ID","STATUS"], 
         "order":
         {
             "ID":"asc"}
             }',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Cookie: BITRIX_SM_SALE_UID=11; qmb=.'
        ),
    ));

    $response = curl_exec($curl);
    $response = json_decode($response, true);
    $resultdata = $response['result']['tasks'];

    return $resultdata;
}

function clean($string)
{
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}
    