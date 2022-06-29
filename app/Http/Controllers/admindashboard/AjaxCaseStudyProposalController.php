<?php

namespace App\Http\Controllers\admindashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Casestudy;

class AjaxCaseStudyProposalController extends Controller
{
    public function index(){
       echo $id = $_GET['id']; 
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
          $id =   encrypt_decrypt($id, 'decrypt');
         $curl = curl_init();
         curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.quote.get?id='.$id,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
            "IBLOCK_TYPE_ID":"lists",
            "IBLOCK_ID":969
         
        }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Cookie: BITRIX_SM_SALE_UID=11; qmb=.'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        
        $response = json_decode($response,true);
       $response['result']['UF_CRM_1639979874'];
       if(isset($response['result']['UF_CRM_1639979874'])){
           $newChecklist = $response['result']['UF_CRM_1639979874'];
           foreach($newChecklist as $newmapID ){
               $data = Casestudy::Where('BitrixMapId', '=', $newmapID)->first();
               $data = json_decode($data,true);
               $Listofcasestudy[] = $data;
           } 
        return response()->json(array('success' => true, 'data' =>$Listofcasestudy));
       }
       
       return 0;
      
    }
}
