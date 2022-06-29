<?php

namespace App\Http\Controllers\admindashboard;

use App\Http\Controllers\Controller;
use PDF;
use DB;

class ProjectStatusController extends Controller
{
    public function index()
    {
        return view('Adminview.Projectstatus.index');
    }

    public function addorupdateprojects()
    {

    }

    public function getallprojects()
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://cc.codeconspirators.com/rest/22/sqvy6dfkagsp6z8n/crm.deal.list',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                        "filter":{
                              "STAGE_ID": 1
                                 }
                            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: BITRIX_SM_SALE_UID=11; qmb=.'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = (array)json_decode($response);
        $dealresponse = $response['result'];
         //dd($dealresponse);
        $start = 0;
       $total = count($dealresponse);
       $dealids=array();
        for ($i = 0; $i <= $total; $i++) {
            foreach ($dealresponse as $result) {

                $dealids[]=$result->ID;
            }
        }
            $dealidcollection=implode(", ",$dealids);
        //dd($dealidcollection);

                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://cc.codeconspirators.com/rest/22/sqvy6dfkagsp6z8n/crm.quote.list',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => '{
                                    "filter":{

                                         "!UF_CRM_QUOTE_1630303559020": "",
                                         ">OPPORTUNITY":0,
                                         "!TITLE":"",
                                         "DEAL_ID":[' . $dealidcollection. ']


                                    }

                                }',
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        'Cookie: BITRIX_SM_SALE_UID=11; qmb=.'
                    ),
                ));

                $response = curl_exec($curl);

                curl_close($curl);
        $response = (array)json_decode($response);
        $quoteresponse = $response['result'];
        $totalnew=count($quoteresponse);
        //dd($quoteresponse);
            foreach ($quoteresponse as $result) {
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://cc.codeconspirators.com/rest/22/sqvy6dfkagsp6z8n/crm.deal.get?ID='.$result->DEAL_ID,
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

                $response = (array)json_decode($response);
                $finalresponse[] = $response['result'];
                    


            }
           $this->createproject($finalresponse);
            
        
           

    }
    public function createtask($projectid,$strategistid){
        $titleName="Test for task";
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://cc.codeconspirators.com/rest/22/sqvy6dfkagsp6z8n/tasks.task.add',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
                        "fields": {
                            "TITLE": "'.$titleName.'",
                            "GROUP_ID":"'.$projectid.'",
                            "RESPONSIBLE_ID": '.$strategistid.'
                            
                        }
                    }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Cookie: BITRIX_SM_SALE_UID=11; qmb=.'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
             dd($response);
            $response = json_decode($response);
            if(isset($response->result->task->id)){
                return $response->result->task->id;
            }
            return 0;
        
    }

    public function getcompanyname($id)
    {

    }
    public function createproject($finalresponse)
    {

         
        foreach($finalresponse as $resp)
        {
            $dealid= $resp->ID;
           // $quoteid = $resp->quoteID;
                if(isset($resp->UF_CRM_1649149280))
            $project_name = $resp->UF_CRM_1649149280;
            else
            $project_name = getcompanyname($resp->CONTACT_ID);

            $bitrix_dealID = $dealid;
        	//$bitrix_quoteID = "";
            //$client="";
            if($resp->UF_CRM_1649155953)
            $strategist=$resp->UF_CRM_1649155953;
	        if(isset($resp->UF_CRM_6214B4F8D6BDD))
            {
             $serviceid=$resp->UF_CRM_6214B4F8D6BDD;
                if($serviceid==399)
                {
                $strategistid=36;
                $strategist="Gianni Rand";
                    
                }
                if($serviceid==381)
                {
                $strategistid=34;
                $strategist="Evan Koteles";
                
                }
            }
            else
            {
                $strategistid=34;
                $strategist="Evan Koteles";
            }

            $budget = $resp->OPPORTUNITY;	
           // $budget-hrs="";	
           // $time-logged=""	
            $outsourced=$resp->UF_CRM_1650348858;	
            //$margin=	
            //$Tasks	
            //$open_tasks
        
        /* $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://cc.codeconspirators.com/rest/22/sqvy6dfkagsp6z8n/sonet_group.create?NAME=' . $project_name,
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
        $response = json_decode($response, true); */
         
        
        $taskid = $this->createtask(141,$strategistid);
        $data = array('project_name' =>$project_name,
    	'bitrix_dealID'=> $bitrix_dealID,
		'strategist'=>$strategist,	
        'budget-$'=>$budget, 
        'budget-hrs'=>'',
        'outsourced-$'=>$outsourced
        

    );
      
      
      DB::table('project_details')->insertOrIgnore($data);
        die();
        }

    }


}