<?php


namespace App\Http\Controllers;
require '../vendor/quickbook/vendor/autoload.php';

use DB;
use Hash;
use Mail;
use DateTime;
use DatePeriod;
use DateInterval;
use App\Models\User;
use App\Models\Project;
//use	App\Http\Controllers\Auth\MagicLoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

$user = Auth::user();
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2AccessToken;


class ApiController extends Controller
{
    /* quick book method */

    /* for refreshing new token */
    public function quickbook_invoice_api()
    {

        /*  $response = array();
          $response['is_valid']=false;
          $response['message']=false;
          $response['data']=false;
          
          $tokens = file_get_contents(storage_path()."/token.json");
          $tokens = json_decode($tokens,true);
          if(!isset($tokens['access_token'])){
              $response['message']="access token not found";
              //return json_encode($response);
          }
          $refresh_token = $tokens['refresh_token'];
          $result = $this->getnewtoken($refresh_token);
  
  
          if($result['is_valid']==false)
          {
              $response['message']=$result['error_message'];
              $response['data']=false;
             // return json_encode($response);   
          }
          
          $access_token = $result['access_token'];
          
          $user = Auth::user();
          $email = $user->email; */

        try {
            $dataService = DataService::Configure(array(
                'auth_mode' => 'oauth2',
                'ClientID' => "ABdyzzpX3J2wjXkZfngzBgnMbGzHNSlvLuTYWfYhKEzPDlJzND",
                'ClientSecret' => "sL5TwzmQ4YtG8Twb8u1u2R3kWP6bPRbpXKFUixcX",
                'RedirectURI' => "https://portal-dev.codeconspirators.com/codeconspirators/public/quickbook_callback",
                'scope' => "com.intuit.quickbooks.accounting",
                'baseUrl' => "development"
            ));
            //  dd($dataService);
            $error = $dataService->getLastError();

            $oathaccesstokenobj = new OAuth2AccessToken('ABdyzzpX3J2wjXkZfngzBgnMbGzHNSlvLuTYWfYhKEzPDlJzND', 'sL5TwzmQ4YtG8Twb8u1u2R3kWP6bPRbpXKFUixcX', $access_token);
            // $oathaccesstokenobj->setRealmID("9130350810338796");

            $oathaccesstokenobj->setRealmID("4620816365217669310");

            $dataService->updateOAuth2Token($oathaccesstokenobj);

            $customer = $dataService->Query("Select * from Customer where PrimaryEmailAddr = '$email'");
            $customer_id = isset($customer[0]->Id) ? $customer[0]->Id : 0;
            if ($customer_id) {
                $param = "select * from invoice  where CustomerRef ='$customer_id'";
                $allInvoices = json_decode($this->fetch_invoice($access_token, $param), true);
                $response['is_valid'] = true;
                $response['message'] = true;
                $response['data'] = $allInvoices;
                //  dd($response);
                return json_encode($response);
            } else {
                $response['message'] = "No Customer Found";
                return json_encode($response);
            }
        } catch (\Exception $e) {
            $response['message'] = "Quicbook Error";
          //  $response['data'] = $e->message();
            return json_encode($response);
        }
    }

    /* invoice ko fetch krta hai */

    public function fetch_invoice($access_token, $param)
    {

        $param = urlencode($param);
        $curl = curl_init();


        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://sandbox-quickbooks.api.intuit.com/v3/company/4620816365217669310/query?query=$param&include=InvoiceLink&minorversion=59",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Authorization: Bearer ' . $access_token
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }

    public function quickbook_invoice_create_api($data, $req)
    {

        $response = array();
        $response['is_valid'] = false;
        $response['message'] = false;
        $response['data'] = false;

        $tokens = file_get_contents(storage_path() . "/token.json");
        $tokens = json_decode($tokens, true);
        if (!isset($tokens['access_token'])) {
            $response['message'] = "access token not found";
            //return json_encode($response);
        }

        $refresh_token = $tokens['refresh_token'];
        $result = $this->getnewtoken($refresh_token);
        if ($result['is_valid'] == false) {
            $response['message'] = $result['error_message'];
            $response['data'] = false;
            return json_encode($response);
        }

        $access_token = $result['access_token'];
        $this->create_invoice_from_portal($access_token, $data, $req);
        return "hey";

    }


    //this method i will call from templateinvoicecls controller to create invoice in quickbook

    public function getnewtoken($theRefreshTokenValue)
    {
        $response = array();
        $response['is_valid'] = true;
        $response['access_token'] = true;

        $ClientID = "ABdyzzpX3J2wjXkZfngzBgnMbGzHNSlvLuTYWfYhKEzPDlJzND";
        $ClientSecret = "sL5TwzmQ4YtG8Twb8u1u2R3kWP6bPRbpXKFUixcX";

        try {
            $oauth2LoginHelper = new OAuth2LoginHelper($ClientID, $ClientSecret);
            $accessTokenObj = $oauth2LoginHelper->refreshAccessTokenWithRefreshToken($theRefreshTokenValue);
            $accessTokenValue = $accessTokenObj->getAccessToken();
            $refreshTokenValue = $accessTokenObj->getRefreshToken();
            $token = array();
            $token['access_token'] = $accessTokenValue;
            $token['refresh_token'] = $refreshTokenValue;
            $token['updated_at'] = date('Y-m-d');
            file_put_contents(storage_path() . "/token.json", json_encode($token));
            $response['access_token'] = $accessTokenValue;
        } catch (\Exception $e) {
            $response['is_valid'] = false;
            $response['access_token'] = false;
            $response['error_message'] = $e->getMessage();
        }

        return $response;
    }

    //main function to create incoice

    public function create_invoice_from_portal($access_token, $data, $req)
    {
        $resultfrombitrix = $data;
        $dealidforcontact = $resultfrombitrix->DEAL_ID;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.deal.get?id=' . $dealidforcontact,
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
        $dealdata=$response;
        if(isset($response->result->CONTACT_ID) and $response->result->CONTACT_ID!="")
        {
        $contact_id_for_email = $response->result->CONTACT_ID;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.contact.get?id=' . $contact_id_for_email,
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
        if(isset($response->result->EMAIL[0]->VALUE) && $response->result->EMAIL[0]->VALUE!="")
        $email = $response->result->EMAIL[0]->VALUE;
        }
        //check kr rha email set hai ki yha pe

        /* agr set hua to  email pass kr rha*/
        //   dd($data,"hey");

        if (isset($email)) {
            // dd("email found");
            // $email="yekyahai@gmail.com";
            // $email=$data->CLIENT_EMAIL;
            //passing email  only and access token

            $response_from_handler = $this->quickbook_customer_handler($access_token, $email);
            //dd($response_from_handler);
            $custome_existence_checking = json_decode($response_from_handler);
            if (isset($custome_existence_checking->QueryResponse))
                $data = json_encode($custome_existence_checking->QueryResponse);

            $arr = (array)$data;
            //dd("hey",  $arr );
            if ($arr[0] == '{}') {
                //  dd("hello if");

                //agr phle se hai to dubara  create ni hoga
                $customer_ref_id = $this->createCustomerInquickbOOk($resultfrombitrix, $access_token, $email);
                //crere a invoice
                $this->createinvoice($resultfrombitrix, $customer_ref_id, $access_token, $req, $email, $dealdata);
            } else {

                $customerRef = $custome_existence_checking->QueryResponse->Customer[0]->Id;
                // dd("hi there", $customerRef);

                $this->createinvoice($resultfrombitrix, $customerRef, $access_token, $req, $email, $dealdata);

                //invoice created and we have to redirect from here
            }
            return redirect('/');
        } else {
            return redirect('/');
        }


    }

    public function DueInvoiceHandler()
    {
                /*First get token and enable Dataservice */
                $tokens = file_get_contents(storage_path() . "/token.json");
                $tokens = json_decode($tokens, true);
                if (isset($tokens['access_token'])) {
                    $access_token = $tokens['access_token'];
                    $theRefreshTokenValue = $tokens['refresh_token'];
                    $result = $this->getnewtoken($theRefreshTokenValue);
        
                    if ($result['is_valid']) {
                        /* custom api */
        
                    } else {
                        echo $result['error_message'];
                    }
                } 
                         
                 $dataService = DataService::Configure(array(
                'auth_mode' => 'oauth2',
                'ClientID' => "ABdyzzpX3J2wjXkZfngzBgnMbGzHNSlvLuTYWfYhKEzPDlJzND",
                'ClientSecret' => "sL5TwzmQ4YtG8Twb8u1u2R3kWP6bPRbpXKFUixcX",
                'RedirectURI' => "https://portal-dev.codeconspirators.com/codeconspirators/public/quickbook_callback",
                'scope' => "com.intuit.quickbooks.accounting",
                'baseUrl' => "development"
            ));
            //  dd($dataService);
            $error = $dataService->getLastError();
            
    
            $oathaccesstokenobj = new OAuth2AccessToken('ABdyzzpX3J2wjXkZfngzBgnMbGzHNSlvLuTYWfYhKEzPDlJzND', 'sL5TwzmQ4YtG8Twb8u1u2R3kWP6bPRbpXKFUixcX', $access_token);
            // $oathaccesstokenobj->setRealmID("9130350810338796");
    
            $oathaccesstokenobj->setRealmID("4620816365217669310");
    
            $dataService->updateOAuth2Token($oathaccesstokenobj);
            $dataService->throwExceptionOnError(true);
            
            /*Get Today's date for compraision, if this is last invoice and  not send, else invoice send to client but when mail status is 0 */
            
            $todaydate=date("Y-m-d");
            $qry= 'select * from InvoiceResponsibleID where DueDate="'.$todaydate.'" and Mailed=0';
            $select_docs = DB::select($qry);
           // dd($select_docs);
            foreach($select_docs as $key => $doc)
            {
                $dealid = $doc->DealID;
                $qry= 'select * from InvoiceResponsibleID where Dealid='.$dealid.' and InvoiceID!='.$doc->InvoiceID.' and DueDate>="'.$todaydate.'"';
                $select = DB::select($qry);
                //dd($select);
                if(count($select)>1)
                {
                $this_invoice = $dataService->Query("select * from Invoice where DocNumber='$doc->DocNumber'");
                $this_invoice_trimmed = $this_invoice[0];
                $resultingMailObj = $dataService->sendEmail($this_invoice_trimmed);
               
                $affected = DB::table('InvoiceResponsibleID')
                            ->where('DocNumber',$doc->DocNumber )
                            ->update(['Mailed' => 1]);
                
                }
                else
                {
                 $email = $doc->ClientEmail;
                 $amt=$doc->Balance;
                 $invid=$doc->DocNumber;
                $maildata = array('ClientEmail' =>$email , 'InvoiceAmt' => $amt, 'InvoiceID' =>$invid );
                $email="aadil.mevafarosh@kadellabs.com";
                Mail::send('email.LastInvoice', $maildata, function ($emailMessage) use ($maildata, $email) {
                    $emailMessage->subject('Alert last invoice Reached !');
                    $emailMessage->to($email,'aadilhussain1988@gmail.com');
                });
                
            }
        
            }
        
    }

    //main function to create a customer

    public function quickbook_customer_handler($accesstoken, $email)
    {
        //   $email="subalbro@gmail.com";
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://sandbox-quickbooks.api.intuit.com/v3/company/4620816365217669310/query?minorversion=14',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => "Select * from Customer  where PrimaryEmailAddr = '" . $email . "'",
            CURLOPT_HTTPHEADER => array(
                'User-Agent: QBOV3-OAuth2-Postman-Collection',
                'Accept: application/json',
                'Content-Type: application/text',
                'Authorization: Bearer ' . $accesstoken
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    //create invoice in quickbook

    public function createCustomerInquickbOOk($cutstomer_data, $access_token, $email)
    {
        // these two comming from quote
        //phone bhi pass kr rhe andr
        // dd("book",$cutstomer_data);
        $dealidforcontact = $cutstomer_data->DEAL_ID;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.deal.get?id=' . $dealidforcontact,
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
        $contact_id_for_email = $response->result->CONTACT_ID;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.contact.get?id=' . $contact_id_for_email,
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
        if ($response->result->COMPANY_ID != "") {
            $cmpid = $response->result->COMPANY_ID;

            //dd($response->result->NAME,$response);
            /* CURLOPT_URL => 'https://quickbooks.api.intuit.com/v3/company/4620816365217669310/customer?minorversion=14*/
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.company.get?id=' . $cmpid,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_HTTPHEADER => array(
                    'Cookie: BITRIX_SM_SALE_UID=11; qmb=.'
                ),
            ));

            $cmpresponse = curl_exec($curl);
            $cmpresponse = json_decode($cmpresponse);
            curl_close($curl);
            $company_name = $cmpresponse->result->TITLE;
        } else
            $company_name = $response->result->NAME . " " . $response->result->LAST_NAME;

        $name = $response->result->NAME;
        if(isset($response->result->PHONE[0]->VALUE) && $response->result->PHONE[0]->VALUE!="")
        $phone = $response->result->PHONE[0]->VALUE;
        else
        $phone ="";
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://sandbox-quickbooks.api.intuit.com/v3/company/4620816365217669310/customer?minorversion=14',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                    "GivenName":"' . $response->result->NAME . '",
                    "FamilyName":"' . $response->result->LAST_NAME . '",
                    "CompanyName": "' . $company_name . '",
                    "DisplayName": "' . $company_name . '",
                    "PrimaryPhone": {
                        "FreeFormNumber": "' . $phone . '"
                    },
                    "PrimaryEmailAddr": {
                        "Address": "' . $email . '"
                    }
                }
                ',
            CURLOPT_HTTPHEADER => array(
                'User-Agent: QBOV3-OAuth2-Postman-Collection',
                'Accept: application/json',
                'Content-Type: application/json',
                'Authorization: Bearer ' . $access_token,
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response);
        //dd($data);
        if (isset($data->Customer->Id))
            return $data->Customer->Id;
        else
            return 0;
    }

    public function createinvoice($data, $customerRef, $access_token, $req, $email,$dealdata)
    {

        //dd($req,"hey");

        $dir = storage_path() . "/subal.txt";
        file_put_contents($dir, "hey");


        $extractedQuoteID = $data->ID;
        // dd($extractedQuoteID);
        // dd($req);

        if (isset($req['set'])) {

            $productarrays = $req['set'];
            $dt = new DateTime();
            $newdatee = $dt->format('Y-m-d');

            file_put_contents($dir, "hellly");
            $this->createInvoiceFromLoop($data, $customerRef, $access_token, $productarrays, $email, $newdatee, $dealdata);


        } else {

            //else part 


            $monthly_Project_product_sarray = [];
            $SingleTime_Project_product_sarray = [];
           if(!array_key_exists("0",$req))
            {
            $req = array_fill(0, 1, $req);
            }
            //  dd($req);
            //if(!isset($req[0]))
            // $req = array_fill(0, 1, $req);

            foreach ($req as $productsarr) {
              //  dd($productsarr);
                if ($productsarr['Monthly'] == 1) {
                    array_push($monthly_Project_product_sarray, $productsarr);
                    //dd($monthly_Project_product_sarray);
                } else {
                    array_push($SingleTime_Project_product_sarray, $productsarr);
                }
            }
            //dd($monthly_Project_product_sarray);

            $NewObjectOfProducctCalc = new ProductCalc();
            $productsection = $NewObjectOfProducctCalc->Sectionkeys();

            $havetostore_using_serialize_not_initial = [];
            $havetostore_using_serialize_initial = [];
            $mainaarray_of_product_section = [];
            //dd($monthly_Project_product_sarray);
            if (count($monthly_Project_product_sarray) > 0) {
                foreach ($monthly_Project_product_sarray as $monthlyProductList) {
                    if ($monthlyProductList['Initial'] == 0) {
                        //initial ni hai to 0
                        array_push($havetostore_using_serialize_not_initial, $monthlyProductList);
                        
                    } else {
                        array_push($havetostore_using_serialize_initial, $monthlyProductList);
                    }
                    
                }
            }

            //dd($productsection);
            //dd($havetostore_using_serialize_not_initial,$havetostore_using_serialize_initial);
            
            if(isset($monthlyProductList['Existing']))
            {
                  DB::table('recurring_invoices')->where('id',$monthlyProductList['Existing'])->update(array(
                                 'invoice_date' =>    Date('Y-m-d h:i:s')));
            }
            else
            {
            foreach($havetostore_using_serialize_not_initial as $serialize_not_initial)
            {
                    $prd_monthly_not_intial = json_encode($havetostore_using_serialize_not_initial); 
                    $values1 = array('product'=>$prd_monthly_not_intial,'dealdata' =>json_encode($dealdata),'create_date' =>    Date('Y-m-d h:i:s') , 'type' =>"installments" );
                    DB::table('recurring_invoices')->insert($values1);
                    
                   
            }
             foreach($havetostore_using_serialize_initial as $serialize_initial)
            {
                    /*$prd_monthly_intial = json_encode($serialize_initial);
                   $values = array( 'product'=>$prd_monthly_intial,'initial' => 1,'dealdata' =>json_encode($dealdata),'create_date' =>    Date('Y-m-d h:i:s'),'invoice_date' =>    Date('Y-m-d h:i:s') );
                    DB::table('recurring_invoices')->insert($values);*/
                
            }  
            }
            
                    
                    
            //dd($monthlyProductList);
            foreach ($havetostore_using_serialize_not_initial as $noninitialData) {

                $noninitialDataProductID = $noninitialData['PRODUCT_ID'];
                $productsectionFromLoophere = $NewObjectOfProducctCalc->getSeparaterValue($noninitialDataProductID);
                $mainaarray_of_product_section[$productsection[$productsectionFromLoophere]][] = $noninitialData;

            }

            
             $dt = new DateTime();
                    $newdatee = $dt->format('Y-m-d');

            //dd($mainaarray_of_product_section,"hiii");
            /*UF_CRM_QUOTE_1637294174507 checking installments of Quote*/
            if (isset($data->UF_CRM_QUOTE_1637294174507)) {
                $installement_numbers = $data->UF_CRM_QUOTE_1637294174507;
                $installement_numbers_array = explode(",", $installement_numbers);
                $i = 0;
                if (array_sum($installement_numbers_array) == 100) {
                  
                    //die();
                    if (count($havetostore_using_serialize_initial) > 0) {
                        $this->createInvoiceFromLoop($data, $customerRef, $access_token, $havetostore_using_serialize_initial, $email, $newdatee, $dealdata);
                    }

                    foreach ($installement_numbers_array as $arr_value) {
                        
                        $product_Get_price_percent_result = $this->DivideInstallmentInPercent($arr_value, $SingleTime_Project_product_sarray);
                    

                        $this->createInvoiceFromLoop($data, $customerRef, $access_token, $product_Get_price_percent_result, $email, $newdatee, $dealdata);

                        $newdatee = $dt->add(new DateInterval('P1D'))->format('Y-m-d');


                    }


                    //monthly product ke  liye aik invoice create hoga hundress percent ammount ke sath
                    //thik hai

                }
            }
            else
            {
                 //$newdatee=$dt->add()->format('Y-m-d');
                 $this->createInvoiceFromLoop($data, $customerRef, $access_token, $havetostore_using_serialize_initial, $email, $newdatee, $dealdata);
            }


        }


    }

    public function createInvoiceFromLoop($data, $customerRef, $access_token, $req, $email, $newdatee, $dealdata)
    {
      //dd(123);
        $initial=0;
      
        if (isset($data->DEAL_ID)) {
            $DealIdforResponsible_Persoon = $data->DEAL_ID;
        } else {
            $DealIdforResponsible_Persoon = $data['DEAL_ID'];
        }
      //  dd($dealdata,$req);
        //dd($req);
        
        for ($i = 0; $i < count($req); $i++) {
            
            $product_name_for_search = $req[$i]['PRODUCT_NAME'];
            $product_name_for_search = $this->RegextoRemove_COLON_from_Productname($product_name_for_search);
            $Single_product = $req[$i];
            $product_search_data = $this->search_product_In_quickbook($product_name_for_search, $access_token);
            //  dd($product_search_data,$access_token);
            if (isset($product_search_data->QueryResponse))
                $result_QueryResponse = json_encode($product_search_data->QueryResponse);
            //  dd($result_QueryResponse);
            if ($result_QueryResponse == '{}') {

                $product_id_result = $this->Create_product_in_quickbook($Single_product, $access_token);
                $req[$i]['Quickbookproduct_ID'] = $product_id_result;
            } else {
                $productid_from_bitrix = $product_search_data->QueryResponse->Item[0]->Id;
                $req[$i]['Quickbookproduct_ID'] = $productid_from_bitrix;
            }
        }

        $n = 10;
        $cntid=$dealdata->result->CONTACT_ID;
        $invoicenumberhere =  $this->getName($cntid,$dealdata->result->TITLE);
        
        
        for ($loopquick = 0; $loopquick < count($req); $loopquick++) {
        $data=    $req[$loopquick];
     
            $field_array['Line'][$loopquick] = array(
                "Amount" => $req[$loopquick]['PRODUCT_PRICE'],
                "Description" => htmlspecialchars_decode($req[$loopquick]['PRODUCT_DESCRIPTION']),
                "DetailType" => "SalesItemLineDetail",
                "SalesItemLineDetail" => array(
                    "ItemRef" => array(
                        "value" => $req[$loopquick]['Quickbookproduct_ID'],
                        "name" => "Services"
                    ),
                    "Qty" => 1
                )
            );
            
            /*if($req[$loopquick]['Initial']==1)
            {
                $initial=1;
            $field_array['Date']=$newdatee;
            }*/
        }
        //dd($dealdata);
        //print_r
        $qry= 'select * from general_settings where id=1';
            $select_docs = DB::select($qry);
        foreach($select_docs as $key => $doc)
            {
             $msgoninv=$doc->message_on_invoice;
             $msgonstat=$doc->message_on_statement;
            }
              
        $customermemo = $msgoninv;
        $statementmemo = $msgonstat;
       
        $field_array['BillEmail'] = array(
            "Address" => $email
        );
        $field_array['CustomerRef'] = array(
            "value" => $customerRef
        );
    /*    $field_array['PrivateNote'] = array(
            "value" => $customermemo
        );*/
        /*$field_array['PaymentDetailsMessage'] = array(                   
             "value"=>"testing by AADIL QBO1"
        );*/

        $field_array['DocNumber'] = $invoicenumberhere;
        $field_array['AllowOnlinePayment'] = true;
        $field_array['AllowOnlineCreditCardPayment'] = true;
        $field_array['AllowOnlineACHPayment'] = true;

        $field_array['AllowIPNPayment'] = false;


        $field_array['TxnDate'] = $newdatee;
        $field_array['DueDate']=$newdatee;
        //  $field_array['InvStartDate']=$newdatee;
        // dd($field_array);
        $setarr = json_encode($field_array);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://sandbox-quickbooks.api.intuit.com/v3/company/4620816365217669310/invoice?minorversion=14',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $setarr,
            CURLOPT_HTTPHEADER => array(
                'User-Agent: QBOV3-OAuth2-Postman-Collection',
                'Accept: application/json',
                'Content-Type: application/json',
                'Authorization: Bearer ' . $access_token
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $dir = storage_path() . "/subal.txt";
        file_put_contents($dir, "hello");
        $invencoded=$response;
        $resultingInvoiceObj = json_decode($response);
        
        $response = json_decode($response, true);
        
              file_put_contents("invoice.txt",$invencoded);
//dd($setarr);           
      //dd($response);
        $invoiceID = $response['Invoice']['Id'];
         
        
        $Docnumber = $response['Invoice']['DocNumber'];
        //echo $email=$response['Invoice']['DocNumber'];
        $Balance = $response['Invoice']['Balance'];
        $duedate=  $response['Invoice']['DueDate'];
         $invoicedate =  $response['Invoice']['TxnDate'];
        
        $ResponsibledealID = $DealIdforResponsible_Persoon;

        /*
            * 3. Email Invoice to customer
         */
         if($initial)
         {
            $dataService = DataService::Configure(array(
                'auth_mode' => 'oauth2',
                'ClientID' => "ABdyzzpX3J2wjXkZfngzBgnMbGzHNSlvLuTYWfYhKEzPDlJzND",
                'ClientSecret' => "sL5TwzmQ4YtG8Twb8u1u2R3kWP6bPRbpXKFUixcX",
                'RedirectURI' => "https://portal-dev.codeconspirators.com/codeconspirators/public/quickbook_callback",
                'scope' => "com.intuit.quickbooks.accounting",
                'baseUrl' => "development"
            ));
            //  dd($dataService);
            $error = $dataService->getLastError();
    
            $oathaccesstokenobj = new OAuth2AccessToken('ABdyzzpX3J2wjXkZfngzBgnMbGzHNSlvLuTYWfYhKEzPDlJzND', 'sL5TwzmQ4YtG8Twb8u1u2R3kWP6bPRbpXKFUixcX', $access_token);
            // $oathaccesstokenobj->setRealmID("9130350810338796");
    
            $oathaccesstokenobj->setRealmID("4620816365217669310");
    
            $dataService->updateOAuth2Token($oathaccesstokenobj);
            $dataService->throwExceptionOnError(true);
            $this_invoice = $dataService->Query("select * from Invoice where DocNumber='$Docnumber'");
            $this_invoice_trimmed = $this_invoice[0];
            //dd($this_invoice_trimmed);
            $current = strtotime(date("Y-m-d"));
            $date    = strtotime($newdatee);
            $datediff = $date - $current;
            $difference = floor($datediff/(60*60*24));
             if($difference==0)
            {
            $resultingMailObj = $dataService->sendEmail($this_invoice_trimmed);
            // echo "Sent mail. Reconstructed response body below:\n";
            $result = json_encode($resultingMailObj, JSON_PRETTY_PRINT);
            }
         }


        $values = array('InvoiceID' => $invoiceID, 'DocNumber' => $Docnumber, 'Balance' => $Balance,
        'DealID' => $ResponsibledealID,'DueDate' => $duedate,'ClientEmail' => $email,'InvoiceDate' => $invoicedate, 'Mailed'=>1, 'Loggedtime'=> Date('Y-m-d h:i:s') );
        DB::table('InvoiceResponsibleID')->insert($values);
        
        /*$prd_monthly_intial = json_encode($req);
                   $values = array( 'product'=>$prd_monthly_intial,,'dealdata' =>json_encode($dealdata),'create_date' =>    Date('Y-m-d h:i:s'),'invoice_date' =>    Date('Y-m-d h:i:s') );
                    DB::table('recurring_invoices')->insert($values);*/
        //dd();

    }

    public function RegextoRemove_COLON_from_Productname($productName)
    {

        //second regex to remove colon

        $re = '/:/m';
        $str = $productName;
        $subst = ' ';

        $result = preg_replace($re, $subst, $str);
        $removedColon_result = $result;

        //second regex to remove double quotes

        $re = '/"/m';
        $str = $removedColon_result;
        $subst = '';
        $result = preg_replace($re, $subst, $str);
        // echo $result;
        return $result;
    }

    public function search_product_In_quickbook($product_name, $access_token)
    {

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://sandbox-quickbooks.api.intuit.com/v3/company/4620816365217669310/query?minorversion=62',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => "select * from item where Name ='" . $product_name . "'",
            CURLOPT_HTTPHEADER => array(
                'User-Agent: QBOV3-OAuth2-Postman-Collection',
                'Accept: application/json',
                'Content-Type: application/text',
                'Authorization: Bearer ' . $access_token
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response_result = json_decode($response);
        return $response_result;
    }

    public function Create_product_in_quickbook($products, $access_token)
    {
        //  dd($products,$access_token);
        $product_name = htmlspecialchars_decode($products['PRODUCT_NAME']);
        $product_description = htmlspecialchars_decode(trim($products['PRODUCT_DESCRIPTION']));

        // dd($product_name,$product_description);

        $product_name = $this->RegextoRemove_COLON_from_Productname($product_name);
        $product_description = $this->RegextoRemove_Quote_from_Productname($product_description);
        //  $product_description_new = trim($product_description,'"');
        $product_description = ltrim($product_description, '"');
        $newproductDescriptionfinal = '';
        for ($loop = 0; $loop < strlen($product_description) - 1; $loop++) {
            $newproductDescriptionfinal .= $product_description[$loop];
        }

        // dd("hey",$newproductDescriptionfinal);

        //  dd(strlen($product_description));

        //  dd("hey",$product_description);
        //  dd("hey",gettype($product_description_new));
        //  dd($product_name,$product_description);
        // echo  $product_name;
        // echo "<br>";
        // echo "<br>";
        // echo  trim($product_description);
        // echo "<br>";
        // echo "<br>";


        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://sandbox-quickbooks.api.intuit.com/v3/company/4620816365217669310/item?minorversion=14',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                  "Name": "' . $product_name . '", 
                  "IncomeAccountRef": {
                    "name": "Services", 
                    "value": "49"
                  }, 
                  "Type": "Service"
                }
                ',
            CURLOPT_HTTPHEADER => array(
                'User-Agent: QBOV3-OAuth2-Postman-Collection',
                'Accept: application/json',
                'Content-Type: application/json',
                'Authorization: Bearer ' . $access_token
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        // echo $response;
        $newdata = json_decode($response, true);
        // dd($newdata);
        $product_id = $newdata['Item']['Id'];
        return $product_id;
    }

    public function  RegextoRemove_Quote_from_Productname($product_description)
    {
        $re = '/"/m';
        $str = $product_description;
        $subst = '';
        $result = preg_replace($re, $subst, $str);
        return $result;
    }

    public function getName($cntid,$title)
    {
       /* $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }*/
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.contact.get?id=' . $cntid,
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

        $cntdetail = curl_exec($curl);
        $cntdetail = json_decode($cntdetail);
        curl_close($curl);
        
       /*we are doing it as per client reuest for Invoice ID */
    
        $ccc =  substr($cntdetail->result->NAME, 0, 3);
        $ppp=substr($title, 0, 3);
        $yymm=date('ym');    
        $FourDigitRandomNumber = mt_rand(1111,9999);
        $xxxx=$FourDigitRandomNumber;
        $invoiceID = $ccc."-".$ppp."-".$yymm."-".$xxxx;
        return $invoiceID;
    }

    public function DivideInstallmentInPercent($arr_value, $req)
    {
        $datacopy = $req;
        //dd($datacopy);
        if(!isset($_SESSION))
            session_start();
        
        for ($i = 0; $i < count($req); $i++) {
            $data = $req[$i];
         // pre($data);
          
           // die();
           if(!isset($_SESSION[$i]["PNAME"]))
           $_SESSION[$i]["PNAME"]="";
           //echo $_SESSION[$i]["PNAME"]."outside";
            if((str_contains($data['PRODUCT_NAME'],'Discount:') or str_contains($data['PRODUCT_NAME'],'Credit') and strcmp($_SESSION[$i]["PNAME"],$data['PRODUCT_NAME'])!=0 ))
            {

           //die();
            $datacopy[$i]['PRODUCT_PRICE'] = "-".$data['PRODUCT_PRICE'];
            
                
            }
            if(strcmp($_SESSION[$i]["PNAME"],$data['PRODUCT_NAME'])==0)
            {
           //  unset($datacopy[$i]);
        //dd($datacopy);
            //continue;
            
                
            }
            $_SESSION[$i]["PNAME"]=$data['PRODUCT_NAME']; 
             if(!(str_contains($data['PRODUCT_NAME'],'Discount:') or str_contains($data['PRODUCT_NAME'],'Credit')) )
            $datacopy[$i]['PRODUCT_PRICE'] = $data['PRODUCT_PRICE'] * $arr_value / 100;
        }
        //pre($datacopy);
        return $datacopy;

    }

    public function testquickbook()
    {
        $tokens = file_get_contents(storage_path() . "/token.json");
        $tokens = json_decode($tokens, true);
        if (isset($tokens['access_token'])) {
            $access_token = $tokens['access_token'];
            $theRefreshTokenValue = $tokens['refresh_token'];
            $result = $this->getnewtoken($theRefreshTokenValue);

            if ($result['is_valid']) {
                /* custom api */

            } else {
                echo $result['error_message'];
            }
        } else {
            /* execute only when admin is login */
            $is_valid = false;
            if ($is_valid) {
                $dataService = DataService::Configure(array(
                    'auth_mode' => 'oauth2',
                    'ClientID' => "ABdyzzpX3J2wjXkZfngzBgnMbGzHNSlvLuTYWfYhKEzPDlJzND",
                    'ClientSecret' => "sL5TwzmQ4YtG8Twb8u1u2R3kWP6bPRbpXKFUixcX",
                    'RedirectURI' => "https://portal-dev.codeconspirators.com/codeconspirators/public/quickbook_callback",
                    'scope' => "com.intuit.quickbooks.accounting",
                    'baseUrl' => "development"
                ));
                $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
                $authorizationCodeUrl = $OAuth2LoginHelper->getAuthorizationCodeURL();
                return redirect($authorizationCodeUrl);
            }
            echo "Access Denied";
        }
    }

    public function quickbook_callback()
    {
        if (!isset($_REQUEST["code"]) || !isset($_REQUEST["realmId"])) {
            echo "Invalid Request.!";
            exit;
        }

        $code = $_REQUEST["code"];
        $id = $_REQUEST["realmId"];

        $dataService = DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => "ABIN35BGno09rFQygZdqQ0SGK9PyKuqto4TgTHsX00hzFfYHmk",
            'ClientSecret' => "4WpN0wYggPfIMgkJpjRdtkFqQJnqzvtWt4IIfCUM",
            'RedirectURI' => "https://portal-dev.codeconspirators.com/codeconspirators/public/quickbook_callback",
            'scope' => "com.intuit.quickbooks.accounting",
            'baseUrl' => "production"
        ));
        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();

        $accessTokenObj = $OAuth2LoginHelper->exchangeAuthorizationCodeForToken($code, $id);
        $accessTokenValue = $accessTokenObj->getAccessToken();
        $refreshTokenValue = $accessTokenObj->getRefreshToken();

        $token = array();
        $token['access_token'] = $accessTokenValue;
        $token['refresh_token'] = $refreshTokenValue;
        $token['updated_at'] = date('Y-m-d');
        file_put_contents(storage_path() . "/token.json", json_encode($token));
        echo "Access Token Saved.!";
    }

    public function quickbook_invoice_create_api_from_task($data, $req)
    {

        $response = array();
        $response['is_valid'] = false;
        $response['message'] = false;
        $response['data'] = false;

        $tokens = file_get_contents(storage_path() . "/token.json");
        $tokens = json_decode($tokens, true);
        if (!isset($tokens['access_token'])) {
            $response['message'] = "access token not found";
            return json_encode($response);
        }

        $refresh_token = $tokens['refresh_token'];
        $result = $this->getnewtoken($refresh_token);
        if ($result['is_valid'] == false) {
            $response['message'] = $result['error_message'];
            $response['data'] = false;
            return json_encode($response);
        }
        $access_token = $result['access_token'];
        //task
        //  $dir =storage_path()."/subalbro.txt";

        //  file_put_contents($dir,json_encode($req));

        //  $dir1 =storage_path()."/subalbro1.txt";

        //  file_put_contents($dir1,json_encode($data));

        //  exit;


        $this->create_invoice_from_portal($access_token, $data, $req);
    }


    public function quickbook_invoice_sales_commision_generation()
    {

        $response = array();
        $response['is_valid'] = false;
        $response['message'] = false;
        $response['data'] = false;
        $tokens = file_get_contents(storage_path() . "/token.json");
        $tokens = json_decode($tokens, true);
        if (!isset($tokens['access_token'])) {
            $response['message'] = "access token not found";
            return json_encode($response);
        }

        $refresh_token = $tokens['refresh_token'];
        $result = $this->getnewtoken($refresh_token);
        if ($result['is_valid'] == false) {
            $response['message'] = $result['error_message'];
            $response['data'] = false;
            return json_encode($response);
        }
        $access_token = $result['access_token'];
        return $access_token;

    }


    public function api_dev()
    {
        \Artisan::call('route:cache');
        \Artisan::call('route:clear');
        \Artisan::call('config:cache');
        \Artisan::call('config:clear');
        \Artisan::call('key:generate');
        echo "true";
    }

    public function api()
    {
        $dir = storage_path() . '/logs/test1.txt';
        file_put_contents($dir, json_encode($_REQUEST), FILE_APPEND | LOCK_EX);
        $taskId = json_encode($_REQUEST['data']['FIELDS_AFTER']['ID']);

        //$taskId = 2573;
        //file_put_contents($dir,$taskId,FILE_APPEND|LOCK_EX);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/task.item.getdata",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => '{"id":' . $taskId . '}',
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/json",
                "postman-token: 115d2b95-729e-7604-005e-09628e9fd8e1"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            //  echo $response;
        }

        $task = json_decode($response);
        //  print_r($task);
        $title = $task->result->TITLE;
        if (strtolower($title) != "onboarding client") {
            echo 1;
            exit;
        }
        $group_id = $task->result->GROUP_ID;
        $cont_id = $task->result->UF_CRM_TASK[0];

        $cont_id = explode("_", $cont_id);
        $cont = $cont_id[1];
        echo $cont;
        //file_put_contents($dir,$cont,FILE_APPEND|LOCK_EX);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.contact.get",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => '{"id":"' . $cont . '"}',
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/json",
                "postman-token: 99e38a00-0b0f-e39e-c8e4-db0991979076"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            //  echo $response;
        }

        $cont_detail = json_decode($response);

        $name = $cont_detail->result->NAME;
        $email = $cont_detail->result->EMAIL[0]->VALUE;
        echo $name . "" . $email;
        $password = randomPassword();
        echo $password;

        //  file_put_contents($dir,$name,FILE_APPEND|LOCK_EX);
        //file_put_contents($dir,$email,FILE_APPEND|LOCK_EX);
        // file_put_contents($dir,$password,FILE_APPEND|LOCK_EX);
        $user = User::where('email', $email)->first();
        // dd($user);

        /*    $MagicLoginControllerhere = new MagicLoginController;
            $magiclinktoken =  $MagicLoginControllerhere->sendToken($email);
        */
        /*     file_put_contents(storage_path()."/new.json",$magiclinktoken );
             $dir = storage_path().'/logs/test1.txt';
             file_put_contents($dir,"hell" );    
        */

        //    dd($magiclinktoken);
        $magiclinktoken = "https://portal-dev.codeconspirators.com/codeconspirators/public/login";
        if ($user == null) {
            $user = new User();
            $user->name = "aadil";
            $user->email = $email;
            $user->password = Hash::make($password);
            $user->save();


            $maildata = array('email' => $email, 'name' => $name, 'password' => $password, 'logintoken' => $magiclinktoken);


            try {
                echo "hello";

                Mail::send('email.SendEmail', $maildata, function ($emailMessage) use ($maildata, $email) {
                    $emailMessage->subject('Welcome To CommandCenter!');
                    $emailMessage->to($email);
                });
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        } else {
            $magiclinktoken = "https://portal-dev.codeconspirators.com/codeconspirators/public/login";

            $maildata = array('email' => $email, 'name' => $name, 'password' => $password, 'logintoken' => $magiclinktoken);


            try {
                Mail::send('email.UpdateEmail', $maildata, function ($emailMessage) use ($maildata, $email) {
                    $emailMessage->subject('Welcome To CommandCenter!');
                    $emailMessage->to($email);
                });
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }

        // crete user project only once if already exist then dont create 
        $project = Project::Where([['user_id', '=', "$user->id"], ['project_id', '=', "$group_id"]])->first();
        if (!$project) {
            $project = new Project();
            $project->user_id = $user->id;
            $project->project_id = $group_id;
            $project->save();
        }
    }
}


function randomPassword()
{
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}


class ProductCalc
{

    public function Sectionkeys()
    {
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
        $response = json_decode($response, true);
       // dd($response);
        for ($i = 0; $i < count($response['result']); $i++) {
            $Keyvalue[$response['result'][$i]['ID']] = $response['result'][$i]['NAME'];
        }
        return $Keyvalue;
    }


    public function getSeparaterValue($productid)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.product.get?id=' . $productid,
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
        if (isset($response->result)) {
            if (isset($response->result->SECTION_ID)) {
                return $response->result->SECTION_ID;

            }
        }
        return 0;
    }


    public function CreateTask($titleName)
    {
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
            CURLOPT_POSTFIELDS => '{
                "fields": {
                    "TITLE": "' . $titleName . '",
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
        if (isset($response->result->task->id)) {
            return $response->result->task->id;
        }
        return 0;
    }


    public function addCheckList($id, $title)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/task.checklistitem.add',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '[
                    ' . $id . ',
                    {
                        "TITLE": "' . $title . '",
                        "IS_COMPLETE": "N"
                    }
                ]
                ',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: BITRIX_SM_SALE_UID=11; qmb=.'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response);


    }


}

function pre($arr)
{
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
}
