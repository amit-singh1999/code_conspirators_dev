<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\templatemodel;
use App\Models\Casestudy;
use DateTime;
class ExternalTemplateController extends Controller
{
    public function extemplate($id)
        {     
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
        $signed=0;
        $signeddate= "";
        $quotedproduct=json_decode($newresponse,true);
      // dd($quotedproduct);
       if(isset($quotedproduct['result']['UF_CRM_QUOTE_1630303559020']) && count($quotedproduct['result']['UF_CRM_QUOTE_1630303559020'])>0 )
                
            {
                $signed =1; 
                $signeddate=$quotedproduct['result']['UF_CRM_1650868859'];
                $date = new DateTime($signeddate);
                date_default_timezone_set("America/New_York");
                $signeddate = date('m-d-Y H:i:s',strtotime($signeddate)); 
            }
        
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
            if(!isset($response['result']))
        return view('404.index');
        else
        {
        for($i=0;$i<count($response['result']);$i++){
            $Keyvalue[$response['result'][$i]['ID']] = $response['result'][$i]['NAME'];
        }
        return $Keyvalue; 
        }
        }
        //dd($response);
        
        $productKeyvalue = Sectionkeys();
       // dd($productKeyvalue);
      
       // dd($productKeyvalue);
        function getSeparaterValue($productid){
           // dd($productid);
            if($productid!=0)
            {
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
                     array_push($newProductbucket,73);
                     
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
            else
            {   $newProductbucket= [];
                array_push($newProductbucket,187);
                array_push($newProductbucket,0);
                array_push($newProductbucket,0);
                array_push($newProductbucket,73);
                return $newProductbucket;
        
            }
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
              // print_r($diffrentiator);
               
               
                if(count($diffrentiator)>2){
                    //print_r($diffrentiator);
                  $newarrayhere['Monthly']=$diffrentiator[1];
                  $newarrayhere['prechecked']=$diffrentiator[2];
                  $newarrayhere['ProductSection']=$diffrentiator[0];
                  
                  if(!isset($diffrentiator[3]))
                  $diffrentiator[3]=73;
                  
                  $FinalProductArray[$countarraykey][$productKeyvalue[$diffrentiator[3]]]=$newarrayhere;
                  $FinalProductArraycopy[$productKeyvalue[$diffrentiator[3]]][]=$newarrayhere;
                  
                  $countarraykey++;
                   
           
                 }
           //        print_r($newarrayhere);              
              
        }
        
//($newarrayhere);
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
   // print_r($FinalProductArraycopy);
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
            if(isset($response1['result']['UF_CRM_1622055091573']) && $response1['result']['UF_CRM_1622055091573']!=""){

                $webassesmentlink = $response1['result']['UF_CRM_1622055091573'];
                $assesmentlink=$webassesmentlink;
             //die("12");
            }
            
            /*Seo Assessment link*/
             if(isset($response1['result']['UF_CRM_1623613754787']) && $response1['result']['UF_CRM_1623613754787']!=""){

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
                        /*Estimator link */

            $objectivedata ="";
            if(isset($response1['result']['UF_CRM_1649912379']) &&$response1['result']['UF_CRM_1649912379']!=false ){

                $objectivedata = $response1['result']['UF_CRM_1649912379'];
              // dd($assumptiondata) ;
            }


    $UsernameofQuote = json_decode($response);
    //dd($UsernameofQuote);
        if(!empty($datanew->result->UF_CRM_1628229760)){
              $retrivedata=  templatemodel::where('template_id', $datanew->result->UF_CRM_1628229760)->value('name');
              if(!empty($retrivedata)){
                //   dd($datanew)
                  return view('usertemplate.'.$retrivedata,compact('response', 'datanew','FinalProductArraycopy','UsernameofQuote','company_Title','Listofcasestudy','assumptiondata','assesmentlink', 'estimatorlink','objectivedata', 'signed', 'signeddate'));
              }
              return 0;
        }
        //if template is not selected by mistake
        return 1;
    }
}
function fix_url($url) {
if (substr($url, 0, 7) == 'http://') { return $url; }
if (substr($url, 0, 8) == 'https://') { return $url; }
return 'http://'. $url;
}
