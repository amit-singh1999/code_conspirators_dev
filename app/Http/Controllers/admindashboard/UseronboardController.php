<?php

namespace App\Http\Controllers\admindashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DateTime;
class UseronboardController extends Controller
{
    public function index(){
        return view('Adminview.usermanagement');
    }
    
    public function createUser(Request $req){
        //validation 
                $this->validate($req, [
                    'usersearchid' => 'required',
                    'projectname' => 'required',
                    
                ]);
                $projectid=$req->projectname;
                $userid=$req->usersearchid;
       //  dd($req->userID,$req->projectname);
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
          CURLOPT_POSTFIELDS =>'{
         "ORDER": {
         "NAME": "ASC"
         },
         "FILTER": {
         "%NAME": "'.$projectid.'"
         }
         }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Cookie: BITRIX_SM_SALE_UID=11; qmb=.'
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        // dd($response);
        $response=json_decode($response);
        $groupid=$response->result[0]->ID;
        
        
        // dd($groupid, $userid);
        //create a task finaly here
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/tasks.task.add',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>' {
             "fields":{
                 "TITLE":"Onboarding Client", 
                  "GROUP_ID":"'.$groupid.'",
                 "RESPONSIBLE_ID":1,
                  "UF_CRM_TASK": [ "C_'.$userid.'" ]
                 }
        }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Cookie: BITRIX_SM_SALE_UID=11; qmb=.'
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        //dd($response);
        return redirect('/dashboard');

         
        
        
    }
    
    public function searchContactbyemail(Request $req){
        
        $useremailID =  $req->Useremail;
       
      
        if (isset($useremailID)) {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.contact.list',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => ' { 
                                    
                                        "filter": { "EMAIL": "'.$useremailID.'" }               
                        }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Cookie: BITRIX_SM_SALE_UID=11; qmb=.'
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $response=json_decode($response);
            
            if($response->total==0){
                $createTable = '<p>No contact availbale with these email</p>';
                echo $createTable;
                exit;
            }
            
            $id=  '  id="gettabledata"';
            $function ='  onClick="myFunction(this)"';

            $newfunction=" ".$id." ".$function;
            $tablestyle = '   style="margin-left: 10px;"';
            
            
            $createTable = '<table id="emailtable" '.  $tablestyle.'>';
        	$createTable .= '<tr>';
        	$createTable .= '<th>ID</th>';
        	$createTable .= '<th>Name</th>';
        	$createTable .= '<th>Date Create</th>';
        	$createTable .= '</tr>';
        	foreach( $response->result as $customerData)
        	{
        	    $datetime=strtotime($customerData->DATE_CREATE);
                $datetime = date('y/m/d',$datetime);
               // $datetime = date('Y/m/d', $datetime);
        		$createTable .= '<tr >';
        		$createTable .= '<td  '. $newfunction.' style="cursor:pointer; padding-right:10px;">'.$customerData->ID.'</td>';
        		$createTable .= '<td class="searchbyname" style="cursor:pointer; padding-right:10px;" >'.$customerData->NAME." ".$customerData->LAST_NAME.'</td>';
        		$createTable .= '<td style="cursor:pointer; padding-right:10px;" >'.$datetime.'</td>';
        		$createTable .= '</tr>';	
        	}
        	$createTable .= '</table>';
        	echo $createTable;
        }

       
        
        
    }
    
    public function Searchprojectbyprojectname(Request $req){
             $userSearch_project_name =  $req->Projectname;
          
              
                $id=  '  id="gettableprojectName"';
                $function ='  onClick="myFunProject(this)"';
                $newfunction=" ".$id." ".$function;
                $tablestyle = '   style="margin-left: 10px;"';
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
              CURLOPT_POSTFIELDS =>'{
             "ORDER": {
             "NAME": "ASC"
             },
             "FILTER": {
             "%NAME": "'.$userSearch_project_name.'"
             }
             }',
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: BITRIX_SM_SALE_UID=11; qmb=.'
              ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $response=json_decode($response);
            $createTable = '<table id= "projecttable" '.$tablestyle.'>';
            	$createTable .= '<tr>';
            	$createTable .= '<th>ID</th>';
            	$createTable .= '<th>Name</th>';
            	$createTable .= '<th>Date Created</th>';
            	$createTable .= '</tr>';
            	foreach( $response->result as $customerData)
            	{
                    $datetime=strtotime($customerData->DATE_CREATE);
                    $datetime = date('y/m/d',$datetime);
               
            		$createTable .= '<tr>';
            		$createTable .= '<td  class="searchprojectbyname" style="cursor:pointer; padding-right:10px;">'.$customerData->ID.'</td>';
            		$createTable .= '<td'. $newfunction.' style="cursor:pointer; padding-right:10px;" >'.$customerData->NAME.'</td>';
            		
            		$createTable .= '<td style="cursor:pointer; padding-right:10px;">'.$datetime.'</td>';
            		$createTable .= '</tr>';	
            	}
            	$createTable .= '</table>';
            	echo $createTable;
      
    }
     
     public function SearchcompanybyCompanyname(Request $req){
       $companyname =  $req->Companyname;
       
      
        if (isset($companyname)) {
            $curl = curl_init();
          

            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.company.list',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>'{
            
             "order": { "DATE_CREATE": "ASC" },
                            "filter": {  "%TITLE": "'.$companyname.'" },
                            "select": [ "ID", "TITLE", "DATE_CREATE" ]
                            }',
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: BITRIX_SM_SALE_UID=11; qmb=.'
              ),
            ));
            
            $response = curl_exec($curl);
            
            curl_close($curl);
            $response=json_decode($response);
            
            if($response->total==0){
                $createTable = '<p>No Company availbale with this name</p>';
                echo $createTable;
                exit;
            }
            
            $id=  '  id="gettabledata"';
            $function ='  onClick="myFunction(this)"';

            $newfunction=" ".$id." ".$function;
            $tablestyle = '   style="margin-left: 10px;"';
            
            
            $createTable = '<table id="emailtable" '.  $tablestyle.'>';
        	$createTable .= '<tr>';
        	$createTable .= '<th>ID</th>';
        	$createTable .= '<th>Name</th>';
        	$createTable .= '<th>Date Create</th>';
        	$createTable .= '</tr>';
        	foreach( $response->result as $customerData)
        	{
        	    $datetime=strtotime($customerData->DATE_CREATE);
                $datetime = date('y/m/d',$datetime);
               // $datetime = date('Y/m/d', $datetime);
        		$createTable .= '<tr >';
        		$createTable .= '<td  '. $newfunction.' style="cursor:pointer; padding-right:10px;">'.$customerData->ID.'</td>';
        		$createTable .= '<td class="searchbyname" style="cursor:pointer; padding-right:10px;" >'.$customerData->TITLE.'</td>';
        		$createTable .= '<td style="cursor:pointer; padding-right:10px;" >'.$datetime.'</td>';
        		$createTable .= '</tr>';	
        	}
        	$createTable .= '</table>';
        	echo $createTable;
    
   
     }    
}
}