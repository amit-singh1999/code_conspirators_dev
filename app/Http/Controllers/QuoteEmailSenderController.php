<?php

namespace App\Http\Controllers;
use DB;
use Hash;
use Mail;
use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class QuoteEmailSenderController extends Controller
{
    public function quotebitrix()
    {
        //first encrypted method function 
        
      
         
         function encrypt_decrypt($string, $action = 'encrypt')
            {
                $encrypt_method = "AES-256-CBC";
                $secret_key = 'AA74CDCC2BBRT935136HH7B63C27'; // user define private key
                $secret_iv = '5fgf5HJ5g27'; // user define secret key
                $key = hash('sha256', $secret_key);
                $iv = substr(hash('sha256', $secret_iv), 0, 16); // sha256 is hash_hmac_algo
                if ($action == 'encrypt') {
                    $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
                    $output = base64_encode($output);
                } else if ($action == 'decrypt') {
                    $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
                }
                return $output;
            }
                 
        
        $dir = storage_path().'/logs/data.txt';
        $dealid = $_REQUEST['ID'];
        // $dealid = 1271;
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.quote.list',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
            "filter":
        {
                   "DEAL_ID":"'.$dealid.'",
                   ">OPPORTUNITY":"0"

                }
        }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Cookie: BITRIX_SM_SALE_UID=11; qmb=.'
          ),
        ));

        $response = curl_exec($curl);
        $data=json_decode($response);
        //dd($data);
        //$data= $data;
        foreach($data->result as $resp)
        {
            $arr[] = $resp;
        }
        foreach($arr as $newdata)
        {
            
        
        $quoteid = $newdata->ID;
        
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.deal.get?id='.$dealid,
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
        $dealiddata=json_decode($response);
        $GetcontactIDFromhere=  $dealiddata->result->CONTACT_ID;
        $stage= $dealiddata->result->STAGE_ID;
        
        
        // file_put_contents($dir, $GetcontactIDFromhere,FILE_APPEND|LOCK_EX);
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.contact.get?id='.$GetcontactIDFromhere,
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
        $contactdata=json_decode($response);
        $contactdatahere =  $contactdata->result->EMAIL;
        $contactdata_Name =  $contactdata->result->NAME;
       // dd($contactdata);
        
        $emailid= '';
        if(!empty($contactdatahere)){
             $emailidid =  $contactdatahere[0]->VALUE;
             $emailid=$emailid.$emailidid;
        }
        // curl_close($curl);
        
        //$quoteid=json_decode($quoteid);
        $searchquoteid= $quoteid;
       $quoteid=encrypt_decrypt($quoteid,'encrypt');
        $link='https://portal.codeconspirators.com/yourtemplate/'.$quoteid;
        
      /*  $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.quote.update',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{ 
          "id":'.$searchquoteid.',
          "fields": {
          "UF_CRM_1648804554": "'.$link.'"
        }	
        }',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Cookie: BITRIX_SM_SALE_UID=11; qmb=.'
        ),
        ));
          $response = curl_exec($curl);
         curl_close($curl);*/
        
        // $quoteid=encryptPass($quoteid);
        // file_put_contents($dir,$quoteid,FILE_APPEND|LOCK_EX);
        // file_put_contents($dir,$emailid,FILE_APPEND|LOCK_EX);
        
         $existence_check=$this->ExistingContactCheck($GetcontactIDFromhere);
         file_put_contents($dir, $existence_check,FILE_APPEND|LOCK_EX);
        // $maildata = array('email' => $emailid, 'quoteid' =>$quoteid,'$existence_check' => $existence_check, 'quoteid' =>$quoteid);
         $maildata = array('email' => $emailid, 'quoteid' =>$quoteid,'existence_check' => $existence_check, 'contactdata_Name' =>  $contactdata_Name);
         //sleep(10);
       // if(isset($stage) && $stage==1){
          //  die($stage);
            try {
                Mail::send('email.custom', $maildata, function ($emailMessage) use ($maildata, $emailid, $contactdata_Name) {
                    $emailMessage->subject($contactdata_Name .", here's your Action Plan!");
                    $emailMessage->to($emailid);
                });
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }
        
        
        
        
             
        
    }
    
    public function ExistingContactCheck($userid){
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
          CURLOPT_POSTFIELDS =>'{
            "filter":{
                "UF_CRM_TASK": ["C_'.$userid.'"]
            }
        }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Cookie: BITRIX_SM_SALE_UID=11; qmb=.'
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
       $response = json_decode($response);
       $result_total_check_from_response=$response->total;
       return $result_total_check_from_response;
       

        
    }
 }
