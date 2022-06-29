<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Mail;
use DB;
use DateTime;

class templateinvoiceControllercls extends Controller
{
    //function strat here
    function checkboxcreateInvoce(Request $req){
          $this->validate($req, [
                    'dealIdhere' => 'required'
                  
          ]);
          $pdflink="";
          
          
         
          // product ka mid check  kra yha pe thik hai
          if(isset($req->signature))
          $signature=$req->signature;
          else
          $signature="";
          
          if(isset($req->pdflink))
          $pdflink=$req->pdflink;
          else
           $pdflink="";
        //   dd($signature);
          $signatureFileName = uniqid() . '.png';
          $signature = str_replace('data:image/png;base64,', '', $signature);
          $signature = str_replace(' ', '+', $signature);
          $uploadsignaturehere=$signature; 
        //   dd($uploadsignaturehere=$signature);
        //   echo $req->dealIdhere;
        
          // dd($req);

          $productdataarraypush= array(); //main data
          $arrayofproductiDs=array();   //product id a gya yha pe
           //product ka id nikal rha price nikalne ke liye
           //first paramter
           if(isset($req->Priceconspirator)){
               //array hai
               for($loopid=0;$loopid<count($req->Priceconspirator);$loopid++){
                  array_push($arrayofproductiDs, $req->Priceconspirator[$loopid]);
               }
           }
           //second parameter
           if(isset($req->preselected)){
               //array hai
               for($loopid=0;$loopid<count($req->preselected);$loopid++){
                  array_push($arrayofproductiDs, $req->preselected[$loopid]);
               }
           }
           //third parameter
           if(isset($req->selectone)){
               array_push($arrayofproductiDs, $req->selectone);
           }
          if(isset($req->selectoneMarketing)){
               array_push($arrayofproductiDs, $req->selectoneMarketing);
           }
           
           $quoteurl="https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.quote.get?ID=".$req->dealIdhere;
          $curl = curl_init();
          curl_setopt_array($curl, array(
          CURLOPT_URL => $quoteurl,
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
             
        $dealdata=json_decode($response);
        $dealid = $dealdata->result->DEAL_ID;
        /* we will be using this deal id to create contact */
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.deal.get?id=' . $dealid,
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
        $responsedeal = curl_exec($curl);
        curl_close($curl);
        $responsedeal = json_decode($responsedeal);


        /* we are using below details for deals and email thankyou*/


        $client_email = "";
        if (isset($responsedeal->result->CONTACT_ID)) {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.contact.get?id=' . $responsedeal->result->CONTACT_ID,
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
            $contactdata = json_decode($response);
            // dd($contactdata);
            if (isset($contactdata->result->EMAIL[0]->VALUE))
                $client_email = $contactdata->result->EMAIL[0]->VALUE;
            if (isset($contactdata->result->NAME))
                $name = $contactdata->result->NAME;
            if (isset($contactdata->result->LAST_NAME))
                $lastname = $contactdata->result->LAST_NAME;
            if(isset($name))
            $client_fullname = $name." ".$lastname;    
        }


        if (isset($dealdata->result->DEAL_ID))
            $dealid = $dealdata->result->DEAL_ID;
        else
            $dealid = "";

        $useremail = $client_email;


        $completedealdata = $responsedeal;
        $dealname=$completedealdata->result->TITLE;
        $deallink= 'https://cc.codeconspirators.com/crm/deal/details/'.$completedealdata->result->ID.'/';
         //dd($completedealdata);
        if (isset($completedealdata->result->UF_CRM_1652854727) && $completedealdata->result->UF_CRM_1652854727!="") {
            
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/user.get?id='.$completedealdata->result->UF_CRM_1652854727,
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
            $response= json_decode($response, true);
            $response=$response["result"][0];
           // dd($response);
            $strategist = $response["NAME"]." ".$response["LAST_NAME"] ;
            $strategistemail=$response["EMAIL"];
            //die();
            
        }
        else
        {
            $strategist = "Robb Riggs" ;
            $strategistemail="rob.riggs@codeconspirators.com";
            
        }
        $thankyoudata = array();

        if (isset($name) && $name!="")
            $thankyoudata["name"] = $name;
      

         if (isset($req->company_name) && $req->company_name!="")
            $thankyoudata["company_name"] = $req->company_name;
            else
             $thankyoudata["company_name"]  = $name;


        $thankyoudata["email"] = $useremail;

        $thankyoudata["dealid"] = $dealid;

        $thankyoudata["strategist"] = $strategist;


        // dd($completedealdata);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.quote.productrows.get',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
               "ID":'.$req->dealIdhere.'
        }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Cookie: BITRIX_SM_SALE_UID=4; qmb=.'
          ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $productdata=json_decode($response);
        //dd($productdata);
        //die();
          
        for($responsedataloop=0;$responsedataloop<count($productdata->result);$responsedataloop++){
            $proID= $productdata->result[$responsedataloop]->PRODUCT_ID;
            for($idloop=0;$idloop<count($arrayofproductiDs);$idloop++){
                $InsideloopDATA= $arrayofproductiDs[$idloop];
                if($proID== $InsideloopDATA){
                    //agr match ho jata hai   to kya krnai  nya arrday bnana hai aut us array ko purane array me push krna hai cool
                    
                    if(str_contains($productdata->result[$responsedataloop]->PRODUCT_NAME,'Discount:'))
                    {
                    $productdata->result[$responsedataloop]->PRICE= "-".$productdata->result[$responsedataloop]->PRICE;
                    $dataarey = array("ID"=>$productdata->result[$responsedataloop]->ID, "PRODUCT_ID"=>$productdata->result[$responsedataloop]->PRODUCT_ID, "PRODUCT_NAME"=>$productdata->result[$responsedataloop]->PRODUCT_NAME,"PRODUCT_PRICE"=>$productdata->result[$responsedataloop]->PRICE,"PRODUCT_DESCRIPTION"=>$productdata->result[$responsedataloop]->PRODUCT_DESCRIPTION);
                   // echo "in IF";
                    }else 
                    $dataarey = array("ID"=>$productdata->result[$responsedataloop]->ID, "PRODUCT_ID"=>$productdata->result[$responsedataloop]->PRODUCT_ID, "PRODUCT_NAME"=>$productdata->result[$responsedataloop]->PRODUCT_NAME,"PRODUCT_PRICE"=>$productdata->result[$responsedataloop]->PRICE,"PRODUCT_DESCRIPTION"=>$productdata->result[$responsedataloop]->PRODUCT_DESCRIPTION);
                   // dd($dataarey);
                    array_push( $productdataarraypush ,$dataarey);
                    break;
                }
                else{
                    Continue;
                }
            }
          }
        $productdataarraypushcopy = $productdataarraypush;
        $another_test_array = [];
         $counti = 0;
          foreach($productdataarraypush as $productsall){
              $productIDgeting_from_array = $productsall['PRODUCT_ID'];
              $function_product_monthy_result_from_handler =  $this->GetproductDataFROmHandler($productIDgeting_from_array);
              $productdataarraypushcopy[$counti]['Monthly']=$function_product_monthy_result_from_handler['Monthly'];
              $productdataarraypushcopy[$counti]['Initial']=$function_product_monthy_result_from_handler['Initial'];
              $counti++;
          }
          $productdataarraypush = $productdataarraypushcopy;
          //dd( $productdataarraypush);
         
          $curl = curl_init();
          curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.quote.get',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
            "ID":'.$req->dealIdhere.'
          }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Cookie: BITRIX_SM_SALE_UID=4; qmb=.'
          ),
        ));
        
        $response = curl_exec($curl);
        $newresponse=$response;
        $datanew = json_decode($newresponse);
        
        //$data= array();
        $data="";
       foreach($productdataarraypush  as $key => $prod)
        {
                if(count($productdataarraypush)==0)
                 $data .='{ "PRODUCT_ID": '.$prod["PRODUCT_ID"].', "PRICE": '.$prod["PRODUCT_PRICE"].', "QUANTITY": 1 }';
                 else
                 $data .='{ "PRODUCT_ID": '.$prod["PRODUCT_ID"].', "PRICE": '.$prod["PRODUCT_PRICE"].', "QUANTITY": 1 },';
                 
        }
         //echo $req->dealIdhere;
         $data=rtrim($data,",");
         $data="[".$data."]";
        // dd($data);
       
       /* updating products in bitrix custom field */
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.quote.productrows.set',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{ 
                "id": '.$req->dealIdhere.',
                       "rows":'.$data.'
            }',
             CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Cookie: BITRIX_SM_SALE_UID=11; qmb=.'
          ),
        ));
         $response = curl_exec($curl);
         curl_close($curl);
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.deal.productrows.set',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{ 
                "id": '.$dealid.',
                       "rows":'.$data.'
            }',
             CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Cookie: BITRIX_SM_SALE_UID=11; qmb=.'
          ),
        ));
         $response = curl_exec($curl);
         curl_close($curl);
       
       //dd($response);
       
       /*update signature and signature time */
                   $date = new DateTime();
            $datenow = $date->format('m/d/Y H:i:sP');

       $curl = curl_init();
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
          "id":'.$req->dealIdhere.',
          "fields": {
          "UF_CRM_QUOTE_1630303559020": {"fileData":"'.$uploadsignaturehere.'"},
          "UF_CRM_1650868859":"'.$datenow.'",
          "UF_CRM_1628683225": "2"
        }	
        }',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Cookie: BITRIX_SM_SALE_UID=11; qmb=.'
        ),
        ));
          $response = curl_exec($curl);
          curl_close($curl);
          
       
         /*invoice commented for now and will fix later*/
       //  dd($datanew->result,$productdataarraypush);
         $invoice_create = new ApiController;
         $invoice_result = $invoice_create->quickbook_invoice_create_api($datanew->result,$productdataarraypush);
          // dd($invoice_result);
            
            /*Send email to Code Conspirators team about signed proposal */
             
             //dd($data,$strategist,$client_fullname,$req->company_name, $dealname,$deallink,$strategistemail);
             
              // dd($datanew);
             
              $adminemail='rob.riggs@codeconspirators.com';
               $maildata = array('data'=> $productdataarraypush, 'client_name' => $client_fullname, 'company_name'=> $req->company_name,'dealname'=> $dealname,'deallink'=> $deallink,'strategist'=> $strategist,'dealamt'=> $datanew->result->OPPORTUNITY);
            
              try {
               Mail::send('email.ProposalSigned', $maildata, function ($emailMessage) use ($maildata,$strategist,$client_fullname) {
                    $emailMessage->subject($client_fullname.", has signed the Action Plan");
                    $emailMessage->to("aadilhussain1988@gmail.com");
                   
                  //  $emailMessage->cc("rob.riggs@codeconspirators.com");
               });
              }
              catch (\Exception $e) {
                echo $e->getMessage();
            }
             
            /******CC portal Team Updated via mail above *******/
            
             /*Send Thanks email to Client*/
             
            
               $maildata = array('email' => $useremail,  'contactdata_Name' => $name , 'company_name' => $name,  'pdflink' =>$pdflink,  'strategist'=> $strategist);
            try {
              
               Mail::send('email.thanks', $maildata, function ($emailMessage) use ($maildata, $useremail, $name) {
                    $emailMessage->subject($name .", We’re Conspiring for Your Good!");
                    $emailMessage->to($useremail);
               }); 
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
                if(isset($thankyoudata["company_name"]))
                $companyname= $thankyoudata["company_name"] ;
        
        //$this->setprojectdetail($req,$strategist, $dealid, $projectname, $companyname,$productdataarraypush,$strategistid);
        
         
         return view('thank-you.index', compact('thankyoudata'));
    }
   
    
    private function GetproductDataFROmHandler($id){
        
            // dd("hey",$id);
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.product.get?ID='.$id,
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
              // echo $response;
              $response =  json_decode($response);
              //  0 or 1  wala system kam ni krega yha pe name dena pdega array ka like hashmap
              // i have to return 2 things either 0 or 1 from this fuction
             if(isset($response->result->PROPERTY_387->value)){
               if($response->result->PROPERTY_387->value==195){
               $newarr['Monthly'] = 1;
               }	
             }else{
             $newarr['Monthly'] = 0;
             }
             
             if(isset($response->result->PROPERTY_395->value)){
               if($response->result->PROPERTY_395->value==209){
               $newarr['Initial'] = 1;
               }
               
             }else{
             $newarr['Initial'] = 0;
             }
             return $newarr; 
             
    }
    
    public function createproject($project_name)
    {
          $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'c' . $project_name,
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
        $response = json_decode($response);
        return $response;
                 
    }
    
    /*function to create task and assign it to project */
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
        //     dd($response);
            $response = json_decode($response);
            if(isset($response->result->task->id)){
                return $response->result->task->id;
            }
            return 0;
        
    }
            /*set project details - Set project details in array and push to database */
        public function setprojectdetail($req,$strategist, $dealid, $projectname, $companyname,$productdataarraypush,$strategistid)
        {
           //echo "<pre>";
          // dd($req);
          // dd($productdataarraypush);
         //  die(); 
            $standardhourlyrate=12;
            
            
            
            $quoteid =$req->dealIdhere;
            //$project_name="";
           // $opportunity=$req->opportunity;
            $strategist_id= "";
            $client=$companyname;
            $outsourced=0;
            if(isset($req->productonetime))
            {
            $budgetonetime= $req->productonetime;
            $budgethrs= $budgetonetime/$standardhourlyrate;
            }
            if(isset($req->productmonthly))
            {
             $budgetmonthly= $req->productmonthly;  
              $budgethrs="";
            }
            $budgethrs= $budgetonetime/$standardhourlyrate;
            //$projectid=$this->createproject($projectname);
            // $projectid=$projectid->result;
            $projectid=149;
            //dd($projectid);
            $this->createtask($projectid,$strategistid);
            
            $tasks=1;
            $opentasks=1;
            
             $data = array('project_name' =>$projectname,
    	                'bitrix_dealID'=> $dealid,
    	                'bitrix_quoteID'=> $req->dealIdhere,
    	                'project_id'=> $projectid,
		'strategist'=>$strategist,	
        'budget-$'=>$budgetonetime, 
        'budget-hrs'=>$budgethrs,
        'client'=>$companyname,
        
        'outsourced-$'=>$outsourced,
        'tasks'=> $tasks,
        
        'open_tasks'=> $opentasks,
            );
             DB::table('support_email_details')->insertOrIgnore($data);

            
        }
        
        
        
        /*set project details end */ 

    
}
