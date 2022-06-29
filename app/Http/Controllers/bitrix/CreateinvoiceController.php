<?php

namespace App\Http\Controllers\bitrix;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CreateinvoiceController extends Controller
{

    public function GetBitrixTaskDetails(){
         $dir =storage_path()."/subal.txt";
         
         
         $data = json_decode(json_encode($_REQUEST),true);
         $data_Task_ID  = $data['data']['FIELDS_AFTER']['TASK_ID'];
         
         function quoteDetail($id)
        
            {
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.quote.get?id=' . $id,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => '{
                "fields": {
                    "TITLE": "task for test222221",
                    "GROUP_ID": 103,
                    "RESPONSIBLE_ID": 13
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
                // echo "<pre>";
                // print_r($response);
                // echo "</pre>";
                return $response->result;
              
            }
                 
                
        function GetproductsRows($id)
        {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.quote.productrows.get?id=' . $id,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Cookie: BITRIX_SM_SALE_UID=11; qmb=.'
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $response = json_decode($response, true);
            return $response;
        }
        
        function removebRcaket($stringhere)
        {
            $re = '/\[.+?\]/m';
            $str = $stringhere;
            preg_match_all($re, $str, $matches, PREG_SET_ORDER, 0);
            $firstMatch =  $matches[0][0];
            $productID =  substr($firstMatch, 1, -1);
            return $productID;
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/tasks.task.get?id='.(int)$data_Task_ID,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS => '{
            "fields": {
                "TITLE": "task for test222221",
                "GROUP_ID": 103,
                "RESPONSIBLE_ID": 13
            }
        }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: BITRIX_SM_SALE_UID=11; qmb=.'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response, true);
        $checklistitems = $response['result']['task']['checklist'];
        $quote_Number = $response['result']['task']['title'];
        $re = '/\[.+?\]/m';
        $str = $quote_Number;
        preg_match_all($re, $str, $matches, PREG_SET_ORDER, 0);
        $firstMatch =  $matches[0][0];
        $quoteNumber =  substr($firstMatch, 1, -1);
        
        $quotedetailsresults = quoteDetail($quoteNumber);


        $mainproductarray = [];
        foreach ($checklistitems as $newitmes) {
            $title = $newitmes['title'];
            if (strpos($title, "ProductID") === 0) {
                $titleresultID = removebRcaket($title);
                $titleresultID = (int)$titleresultID;
                $resultOfproducts  = GetproductsRows($quoteNumber);
                $allproducts = $resultOfproducts['result'];
                foreach ($allproducts as $products) {
                    if ($titleresultID == $products['PRODUCT_ID']) {
                      $pid =  $products['ID'];
                        $products_id =  $products['PRODUCT_ID'];
                        $products_name = $products['PRODUCT_NAME'];
                        $products_description = $products['PRODUCT_DESCRIPTION'];
                        $products_price = $products['PRICE'];
                        $newarray = array( 'ID' => $pid, 'PRODUCT_ID' => $products_id, 'PRODUCT_NAME' => $products_name, 'PRODUCT_DESCRIPTION' => $products_description, 'PRODUCT_PRICE' => $products_price);
                        array_push($mainproductarray, $newarray);
                        $newarray = [];
                    }
                }
            }
        }
        $newarrayset['set'] = $mainproductarray;
        app('App\Http\Controllers\ApiController')->quickbook_invoice_create_api_from_task($quotedetailsresults,$newarrayset);
    }
    
    public function  Callapicontroller(){
        
        // $req= '{"set":[{"ID":"675","PRODUCT_ID":279,"PRODUCT_NAME":"Connected Company: Maintenance & Support","PRODUCT_DESCRIPTION":"- Licensing for Unlimited Users<br>\n - Platform Maintenance<br>\n- Platform Support","PRODUCT_PRICE":800}]}';
        // $req = json_decode($req);
        
        // $data = '{"ID":"429","TITLE":null,"STATUS_ID":"DRAFT","CURRENCY_ID":"USD","OPPORTUNITY":"44077.00","TAX_VALUE":"0.00","COMPANY_ID":null,"CONTACT_ID":null,"MYCOMPANY_ID":"1","BEGINDATE":"2021-11-12T03:00:00+03:00","CLOSEDATE":"2021-11-19T03:00:00+03:00","ASSIGNED_BY_ID":"1","CREATED_BY_ID":"1","MODIFY_BY_ID":"13","DATE_CREATE":"2021-11-12T21:51:27+03:00","DATE_MODIFY":"2021-12-07T14:42:26+03:00","OPENED":"Y","CLOSED":"N","COMMENTS":null,"LEAD_ID":null,"DEAL_ID":"1051","QUOTE_NUMBER":"QU--2021-11-127","CONTENT":null,"TERMS":null,"PERSON_TYPE_ID":"3","LOCATION_ID":null,"CLIENT_TITLE":null,"CLIENT_ADDR":null,"CLIENT_CONTACT":null,"CLIENT_EMAIL":null,"CLIENT_PHONE":null,"CLIENT_TP_ID":null,"CLIENT_TPA_ID":null,"UTM_SOURCE":null,"UTM_MEDIUM":null,"UTM_CAMPAIGN":null,"UTM_CONTENT":null,"UTM_TERM":null,"UF_CRM_6068F84775DA6":"","UF_CRM_6068F8478A1AF":"","UF_CRM_60B004D303310":"","UF_CRM_60B004D32284B":"","UF_CRM_60B004D32A7E0":[],"UF_CRM_60B004D331489":"","UF_CRM_60B004D33DA1C":"","UF_CRM_60B004D347890":"","UF_CRM_60B004D352C28":"","UF_CRM_60B004D360333":"","UF_CRM_60B004D36CC2F":"","UF_CRM_60B004D37C14F":"","UF_CRM_60B004D38693D":"","UF_CRM_60C0CB1DD5BE3":"","UF_CRM_60C0CB1DF1F20":"","UF_CRM_60C0CB1E08DA6":false,"UF_CRM_60C0CB1E27984":"","UF_CRM_60C0CB1E33FEB":"","UF_CRM_60D4786F1DFE1":"","UF_CRM_60D4786F56338":"","UF_CRM_60D4786F69014":"","UF_CRM_60D4786F79EC9":"","UF_CRM_60D4786F899A0":"","UF_CRM_1624968016":"test","UF_CRM_1624973151":"","UF_CRM_1625034183":"","UF_CRM_60F17408CEEEB":"","UF_CRM_60F174093D945":"","UF_CRM_610BAE491CA67":[],"UF_CRM_610BAE4985867":"","UF_CRM_610BAE49926CB":"","UF_CRM_610BAE49A3E47":"","UF_CRM_610BAE49B42F9":"","UF_CRM_610BAE49C18DD":"","UF_CRM_610BAE49CDCD0":"","UF_CRM_610BAE49DA2EF":"","UF_CRM_610BAE49ED129":"","UF_CRM_QUOTE_1628155981821":[],"UF_CRM_1628229760":"253","UF_CRM_QUOTE_1630303559020":{"id":26433,"showUrl":"\/bitrix\/components\/bitrix\/crm.quote.show\/show_file.php?ownerId=429&fieldName=UF_CRM_QUOTE_1630303559020&dynamic=Y&fileId=26433","downloadUrl":"\/bitrix\/components\/bitrix\/crm.quote.show\/show_file.php?auth=&ownerId=429&fieldName=UF_CRM_QUOTE_1630303559020&dynamic=Y&fileId=26433"},"UF_CRM_QUOTE_1634735716240":"","UF_CRM_QUOTE_1634735755989":"","UF_CRM_QUOTE_1634735777996":"","UF_CRM_QUOTE_1637294174507":"40,40,20"}';
        // $data = json_decode($data,true);
        // app('App\Http\Controllers\ApiController')->quickbook_invoice_create_api_from_task($data,$req);
    }
    
}
