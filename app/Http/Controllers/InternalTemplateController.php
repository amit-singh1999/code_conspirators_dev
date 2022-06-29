<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\usertemp;

class InternalTemplateController extends Controller
{
    
    public function intemplate(){
        $storage = array();
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
          CURLOPT_HTTPHEADER => array(
            'Cookie: BITRIX_SM_SALE_UID=4; qmb=.'
          ),
        ));
        
        $response = curl_exec($curl);
        
        
        $data = json_decode($response);
        $count =  $data->total;
        $api_count = 0;
        if ($count > 50) {
        $api_count = floor($count / 50);
        }
        for ($i = 0; $i <= $api_count; $i++) {
          $start = $i * 50;
          $newdata = apicalling($start);
          array_push($storage, $newdata);
          
        }
        curl_close($curl);
        
 
        
        for ($outerloop = 0; $outerloop < count($storage); $outerloop++) {
          for ($innerloop = 0; $innerloop < count($storage[$outerloop]->result); $innerloop++) {
            $quoteid = $storage[$outerloop]->result[$innerloop]->ID;
            $emailid = $storage[$outerloop]->result[$innerloop]->CLIENT_EMAIL;
            $datafound = usertemp::where('quoteid',$quoteid)->first();
            if(!$datafound && $emailid){
                $usertemp = new Usertemp;
                $usertemp->quoteid=$quoteid;
                $usertemp->email=$emailid;
                $usertemp->save();
            }
            
           
            
            
            
           
          }
        }
    }
      
    
}

    function apicalling($start)
        {
        
            $curl = curl_init();
        
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.quote.list?start=' . $start,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array(
              'Cookie: BITRIX_SM_SALE_UID=4; qmb=.'
            ),
          ));
        
          $response = curl_exec($curl);
        
                
          $data = json_decode($response);
          return $data;
        //   $data = json_decode($response);
        //   return $data->result;
    }

