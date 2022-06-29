<?php

namespace App\Http\Controllers;


class QuoteLinkUpdateController extends Controller
{
    public function index()
    {

         $edata =$_REQUEST;
              $quoteid = $edata['data']['FIELDS']['ID'];
        

        file_put_contents("art.txt", $quoteid, FILE_APPEND);

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

        $linktext = encrypt_decrypt($quoteid, 'encrypt');
        file_put_contents("art.txt", $quoteid, FILE_APPEND);

        $link = 'https://portal.codeconspirators.com/actionplan/' . $linktext;

        file_put_contents("art.txt", "  " . $link . "--- " . $quoteid, FILE_APPEND);
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
          "id":' . $quoteid . ',
          "fields": {
          "UF_CRM_1648804554": "' . $link . '"
        }
        }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: BITRIX_SM_SALE_UID=11; qmb=.'
            ),
        ));
        $response = curl_exec($curl);
        file_put_contents("art.txt", "  " . json_encode($response), FILE_APPEND);

        curl_close($curl);
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.quote.get?id='.$quoteid,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Cookie: BITRIX_SM_SALE_UID=11'
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        $quotedproduct = json_decode($response, true);
//dd($quotedproduct);



        $missedfields = array();

        if (isset($quotedproduct['result']['UF_CRM_1648804554']) && $quotedproduct['result']['UF_CRM_1648804554'] != "")
            $actionlink = $quotedproduct['result']['UF_CRM_1648804554'];
        else
            array_push($missedfields, "Action template link");

        if (isset($quotedproduct['result']['UF_CRM_QUOTE_1637294174507']) && $quotedproduct['result']['UF_CRM_QUOTE_1637294174507'] != "")
            $quoteinstallment = $quotedproduct['result']['UF_CRM_QUOTE_1637294174507'];
        else
            array_push($missedfields, "Quote Installments");

        if (isset($quotedproduct['result']['DEAL_ID']))
            $dealid = $quotedproduct['result']['DEAL_ID'];

   //  print_r($missedfields);

        /*Capture the fields from bitrix deal*/
        if (isset($dealid)) {
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
            $response = curl_exec($curl);
            curl_close($curl);
            $company_Title = "";

            $dealproduct = json_decode($response, true);
           // dd($dealproduct);
            
            
            if (isset($dealproduct['result']['TITLE']) && $dealproduct['result']['TITLE'] != "")
                $dealtitle=$dealproduct['result']['TITLE'];
            
            if (isset($dealproduct['result']['COMPANY_ID']) && $dealproduct['result']['COMPANY_ID'] != 0)
            {
                $company = $dealproduct['result']['COMPANY_ID'];
                
            
            }else
                array_push($missedfields, "Company details");
                
                
            if (isset($dealproduct['result']['CONTACT_ID']) && $dealproduct['result']['CONTACT_ID'] != 0)
                $company = $dealproduct['result']['CONTACT_ID'];
            else
                array_push($missedfields, "Contact details");
                
            if (isset($dealproduct['result']['UF_CRM_6214B4F8D6BDD']) and !empty($dealproduct['result']['UF_CRM_6214B4F8D6BDD']))
                echo $servicearea = $dealproduct['result']['UF_CRM_6214B4F8D6BDD'];
            else
                array_push($missedfields, "Service Area");

            if (isset($dealproduct['result']['UF_CRM_6214B4F93CC7B']) and !empty($dealproduct['result']['UF_CRM_6214B4F93CC7B']))
                $casestudy = $dealproduct['result']['UF_CRM_6214B4F93CC7B'];
            else
                array_push($missedfields, "Case Study");

            //dd($responsenew);

        }
     //   print_r($missedfields);


        $curl = curl_init();
        $responsibleid = 1;
        $msg = "For Client [client company name]’s deal[". $dealtitle."] required fields are missing or incomplete <br>";
        if(count($missedfields)>0)
        {
             foreach ($missedfields as $fields)
             {
                $msg .= $fields . ", ";
             }
             $msg.="https://cc.codeconspirators.com/crm/deal/details/".$dealid."/";
        echo 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/im.notify?to=' . $responsibleid . '&message=' . $msg;
        //dd($msg);
       $curl = curl_init();
       $msg= rawurlencode($msg);

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/im.notify?to='.$responsibleid.'&message='.$msg,
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
        echo $response;


    }
    }


}