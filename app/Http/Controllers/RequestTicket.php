<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\templatemodel;
use DB;
use Image;
use PDF;
use App\Models\Casestudy;
class RequestTicket extends Controller
{
    function ticketData(Request $req)
     
  {
    
    //form validation
    $this->validate($req, [
      'issuetitle' => 'required|max:50',
      'describeissue' => 'required|max:200',
      'img' => 'required',
  ]);
     // Get the currently authenticated user...
  $user = Auth::user();

    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://cc.codeconspirators.com/rest/22/n0iixre6ucmshyad/tasks.task.add',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',

      CURLOPT_POSTFIELDS => '{"fields":{
        "TITLE":"' . $req->issuetitle . '",
        "DESCRIPTION":"'.$req->describeissue .'",
        "UF_AUTO_457307501852":"'.$req->projectname.'",
        "UF_AUTO_226162412347":"'.$user->email.'",
         "UF_AUTO_467618799502":"'.$req->flexRadioDefault.'",
        "UF_AUTO_855501359250":"'.$req->issueURL.'",
        "RESPONSIBLE_ID":13,
        "GROUP_ID":34

    }}',
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'Cookie: BITRIX_SM_SALE_UID=0; qmb=.'
      ),
    ));

    $response = curl_exec($curl);
    
    $task_resp = json_decode($response);
    //dd($task_resp->result->task->id);
    $id =$task_resp->result->task->id;

    $name = $req->img->getClientOriginalName();
    curl_close($curl);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://cc.codeconspirators.com/rest/22/n0iixre6ucmshyad/task.item.addfile.xml');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, array( 
        'TASK_ID' => $id, 
        'FILE[NAME]' => "'.$name.'", 
        'FILE[CONTENT]' => base64_encode(file_get_contents($req->img)) 
    ));
    
    $out = curl_exec($curl);
  //  dd($out);
    curl_close($curl);    

        return back()->withStatus(__('Thank you! Your ticket submitted successfully.')); 
  }
  
  /********** AADIL dec-17-2021- *********it will generate the client service agreement as PDF */
  
  
  public function generatePDF($id)
  {
     ini_set('memory_limit', '3000M'); //This might be too large, but depends on the data set

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
          $id=   encrypt_decrypt($id, 'decrypt');
            $datanew=[];
            //$id= 1535;    
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
            "ID":'.$id.'
        }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Cookie: BITRIX_SM_SALE_UID=4; qmb=.'
          ),
        ));
        $response = curl_exec($curl);
        $newresponse=$response;
        
        
        $datanew = json_decode($newresponse);
        //($datanew);
         curl_close($curl);
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
               "ID":'.$id.'
        }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Cookie: BITRIX_SM_SALE_UID=4; qmb=.'
          ),
        ));
        $response = curl_exec($curl);
        $response = json_decode($response);
        
        //dd($response);
        function Sectionkeys(){
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.productsection.list',
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
        $response = json_decode($response,true);
        for($i=0;$i<count($response['result']);$i++){
            $Keyvalue[$response['result'][$i]['ID']] = $response['result'][$i]['NAME'];
        }
        return $Keyvalue; 
        }
        //dd($response);
        
        $productKeyvalue = Sectionkeys();
        //dd($productKeyvalue);
      
       // dd($productKeyvalue);
            function getSeparaterValue($productid){
           // dd($productid);
                if($productid==317)
                $productid=315;
                if($productid==143)
                $productid=141;
                
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.product.get?id='.$productid,
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
                $response= json_decode($response);
                $newProductbucket= [];
                // dd($response);
                /* aadil comment to solve subal logic
                PROPERTY_387 = recurring
                PROPERTY_385 = radio checkbox default
                PROPERTY_393 = prechecked
                
                */
                
                /*seprator id*/
                if(isset($response->result)){
                    if(isset($response->result->PROPERTY_385->value)){
                     $separaterID = $response->result->PROPERTY_385->value;
                     array_push($newProductbucket,$separaterID);
                    }
                    else
                     array_push($newProductbucket,187);
                     
                     //Recurring
                     if(isset($response->result->PROPERTY_387->value)){
                        //  second 1 ka mtlb montthly
                        // dd("PROPERTY_387->value");
                        // dd($response->result->PROPERTY_387->value);
                        if($response->result->PROPERTY_387->value==195){
                            array_push($newProductbucket,1);
                        }
                     }else{
                      array_push($newProductbucket,0);
                     }
                     //PRECHECKED
                     if(isset($response->result->PROPERTY_393->value)) {
                          // third 1 ka mtlb preselect
                         if($response->result->PROPERTY_393->value==203) {
                            array_push($newProductbucket,1);
                        }
                        
                     }
                     else 
                        array_push($newProductbucket,0);
                     
                     array_push($newProductbucket,$response->result->SECTION_ID);
                }
                //dd($newProductbucket);
                return $newProductbucket;
        }
    
        $Getdatafrom_SeparaterValueFunction=[];
        
        $testarray = [];
        $mainarraypassingtoview = [];
        $FinalProductArraycopy = [];
        $casestudyArray=[];
        $Listofcasestudy=[];
        $countarraykey=0;
        if(!isset($response->result))
        return view('404.index');
      //  dd($response);
        for($i=0;$i<count($response->result);$i++){
             $productid = $response->result[$i]->PRODUCT_ID;
             $productname = $response->result[$i]->PRODUCT_NAME;
             $productprice = $response->result[$i]->PRICE_ACCOUNT;  
             $productdescription = trim($response->result[$i]->PRODUCT_DESCRIPTION);
             $newarrayhere=array('productid'=>$productid,'productname'=> $productname,'productprice'=>$productprice,'productdescription'=>$productdescription);
                $diffrentiator  =   getSeparaterValue($productid);
             //   print_r($diffrentiator);
               
               
                if(count($diffrentiator)>2){
                   // print_r($diffrentiator);
                  $newarrayhere['Monthly']=$diffrentiator[1];
                  $newarrayhere['prechecked']=$diffrentiator[2];
                  $newarrayhere['ProductSection']=$diffrentiator[0];
                  $FinalProductArray[$countarraykey][$productKeyvalue[$diffrentiator[3]]]=$newarrayhere;
                  $FinalProductArraycopy[$productKeyvalue[$diffrentiator[3]]][]=$newarrayhere;
                  
                  $countarraykey++;
                   
           
                 }
           //        print_r($newarrayhere);              
              
        }
        
       // dd($newarrayhere);
             if(!isset($datanew->result->DEAL_ID))
        return view('404.index');
     $dealidfinal=$datanew->result->DEAL_ID;
     // $dealidfinal=1125;
     //echo $dealidfinal;
     curl_close($curl);
     $curl = curl_init();
     curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.deal.get?id='.$dealidfinal,
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
    $company_Title="";
    
    $responsenew=json_decode($response);
   // dd($responsenew);
       if(isset($responsenew->result->COMPANY_ID)){
       if($responsenew->result->COMPANY_ID == 0){
            $company_Title = 0 ;
        }
       else
       {
        
       
            $productid_customer_here= $responsenew->result->COMPANY_ID;
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.company.get?id='.$productid_customer_here,
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
                $responsehere = curl_exec($curl);
                curl_close($curl);
                $responsehere = json_decode($responsehere);
                 //dd($responsehere);
                if(isset($responsehere->result))
               $company_Title=$responsehere->result->TITLE;

        
       }
       
     }
     //dd($company_Title);
       
    
    
    
    
        function fix_url($url) {
if (substr($url, 0, 7) == 'http://') { return $url; }
if (substr($url, 0, 8) == 'https://') { return $url; }
return 'http://'. $url;
}
    
    
    
    
        if(!isset($responsenew->result))
        return view('404.index');
    $contactidfinal=$responsenew->result->CONTACT_ID;
    //contact id
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.contact.get?id='.$contactidfinal,
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
    // dd($company_Title);
    //  dd($FinalProductArraycopy);
   // dd($FinalProductArraycopy);
    $UsernameofQuote = json_decode($response);
        //dd($UsernameofQuote);
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.deal.get?id='.$dealidfinal,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{
            "IBLOCK_TYPE_ID":"lists",
            "IBLOCK_ID":1003
         
        }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Cookie: BITRIX_SM_SALE_UID=11; qmb=.'
                ),
            ));

            $response1 = curl_exec($curl);
            curl_close($curl);

            $response1 = json_decode($response1,true);
           //  dd($response1);
            // $response['result']['UF_CRM_1639979874'];
            if(isset($response1['result']['UF_CRM_6214B4F93CC7B']) &&$response1['result']['UF_CRM_6214B4F93CC7B']!=false ){
                $newChecklist = $response1['result']['UF_CRM_6214B4F93CC7B'];
                // dd($newChecklist);
                foreach($newChecklist as $newmapID ){
                    $data = Casestudy::Where('BitrixMapId', '=', $newmapID)->first();
                    $data = json_decode($data,true);
                    $Listofcasestudy[] = $data;
                }

//dd($Listofcasestudy);

}           /*Get Assumption data */
            $assumptiondata ="";
            if(isset($response1['result']['UF_CRM_1647496283']) &&$response1['result']['UF_CRM_1647496283']!=false ){

                $assumptiondata = $response1['result']['UF_CRM_1647496283'];
              // dd($assumptiondata) ;
            }
           
           /*Get Assessment links */
            $assesmentlink ="";
            
            /*Website Assessment link*/
            if(isset($response1['result']['UF_CRM_1622055091573']) &&$response1['result']['UF_CRM_1622055091573']!=""){

                $webassesmentlink = $response1['result']['UF_CRM_1622055091573'];
                $assesmentlink=$webassesmentlink;
             //die("12");
            }
            
            /*Seo Assessment link*/
             if(isset($response1['result']['UF_CRM_1623613754787']) &&$response1['result']['UF_CRM_1623613754787']!=""){

                $seoassesmentlink = $response1['result']['UF_CRM_1623613754787'];
                $assesmentlink=$seoassesmentlink;
            }
            
            /*landingpage Assessment link*/
             if(isset($response1['result']['UF_CRM_1623613802212']) &&$response1['result']['UF_CRM_1623613802212']!=""){

                $landingpageassesmentlink = $response1['result']['UF_CRM_1623613802212'];
                $assesmentlink=$landingpageassesmentlink;
            }
            
            /*Marketing Assessment link*/
             if(isset($response1['result']['UF_CRM_1623613789654']) &&$response1['result']['UF_CRM_1623613789654']!=""){

                $marketingassesmentlink = $response1['result']['UF_CRM_1623613789654'];
                $assesmentlink=$marketingassesmentlink;
            }
            
            /*PPC Assessment link*/
             if(isset($response1['result']['UF_CRM_1623613766956']) &&$response1['result']['UF_CRM_1623613766956']!=""){

                $ppcassesmentlink = $response1['result']['UF_CRM_1623613766956'];
                $assesmentlink=$ppcassesmentlink;
             }
             
            /*Compatitive Analysis Assessment link*/
             if(isset($response1['result']['UF_CRM_1623613812244']) &&$response1['result']['UF_CRM_1623613812244']!=""){

                $cmpanalysisassesmentlink = $response1['result']['UF_CRM_1623613812244'];
                $assesmentlink=  $cmpanalysisassesmentlink;
             
            }
                $assesmentlink =fix_url($assesmentlink);
            /*Estimator link */
            $estimatorlink ="";
            if(isset($response1['result']['UF_CRM_1622055119481']) &&$response1['result']['UF_CRM_1622055119481']!=""){

                $estimatorlink = $response1['result']['UF_CRM_1622055119481'];
                $estimatorlink =fix_url($estimatorlink);    
            }


    $UsernameofQuote = json_decode($response);
    //dd($UsernameofQuote);
        if(!empty($datanew->result->UF_CRM_1628229760)){
              $retrivedata=  templatemodel::where('template_id', $datanew->result->UF_CRM_1628229760)->value('name');
              if(!empty($retrivedata)){
                //   dd($datanew);
                      $html = view('dowloadasPdf.index-form',compact('response', 'datanew','FinalProductArraycopy','UsernameofQuote','company_Title','Listofcasestudy','assumptiondata','assesmentlink', 'estimatorlink'))->render();
                      $html .= '<link type="text/css" href="https://files.codeconspirators.com/_resources/proposal/style.css" rel="stylesheet" />';
                      $pdf=  PDF::loadHTML($html)->setPaper('a4', 'portrait')->setWarnings(false)->
                      setOptions(['defaultFont' => 'sans-serif'])->setOptions(['isRemoteEnabled' => false]);
                      // return $pdf->stream();
                      return $pdf->download('Service-contract.pdf');
              }
              return 0;
        }
        //if template is not selected by mistake
        return 1;

  }
  
  /********** AADIL dec-17-2021- *********it will generate the view of clientservice agreement which we generating as PDF abv ******/
  public function viewPDF()
  {
      return view('pdfcontract/hello');
  }
  
}
