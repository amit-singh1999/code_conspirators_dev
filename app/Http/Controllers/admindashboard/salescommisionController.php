<?php

namespace App\Http\Controllers\admindashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
class salescommisionController extends Controller
{
    public function index()
    {
        return view('Adminview.SalesCommission.index');
    }
    
      public function InsertSalesData()
    {
        ini_set('max_execution_time', '3000');
        $curl = curl_init();
        $start = 0;
        $accesspath = "https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.invoice.list?start=" . $start;
        //echo $start; die();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $accesspath,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '  {          "filter": {
                "PAYED": "Y"
            }
        }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: BITRIX_SM_SALE_UID=11; qmb=.'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $mainarr = array();
        $response = json_decode(json_encode($response), true);
        $response = (array)json_decode($response);
        $total = (isset($response['total']) ? $response['total'] : 0);
        $comm_arr = array();

        $userID = 0;
        $start = 0;
        $j = 0;
        // echo $total; die();
        for ($j = 0; $j <= $total / 50; $j++) {
            $curl = curl_init();
            $accesspath = "https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.invoice.list?start=" . $start;

            curl_setopt_array($curl, array(
                CURLOPT_URL => $accesspath,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{ "filter": {
            "PAYED": "Y"
        }
    }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Cookie: BITRIX_SM_SALE_UID=11; qmb=.'
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $mainarr = array();
            $response = json_decode(json_encode($response), true);
            $response = (array)json_decode($response);
             if(isset($response))
             {
            $response = $response['result'];

            // dd($response);
            if ($total > $start) {


                foreach ($response as $key => $result) {


                    $invoice_id = $result->ID;
                    $val= DB::table('invoice_details')->select('responsible_name')->where('invoice_id', $invoice_id)->get();
                    if(count($val)==0)
                    {
                    $companyarr[] = $this->getCompanyFromBitrixApi($invoice_id );
                    //$companyarr=$companyarr[0];
                   foreach($companyarr as $value)
                    {
                         if(isset($value['FIO']))
                            $company_name = $value['FIO'];
                         if(isset($value['COMPANY']))  
                             $company_name = $value['COMPANY'];
                        else
                            $company_name = "";
                        if(isset($value['EMAIL'])) 
                             $company_email = $value['EMAIL'];
                        else
                             $company_email = "";
                         if(isset($value['PHONE'])) 
                             $company_phone =  $value['PHONE'];
                        else
                            $company_phone = "";
                    }
                   
                    $userID = $result->RESPONSIBLE_ID;
                    $account_number = $result->ACCOUNT_NUMBER;
                    $date_bill = date('Y-m-d H:i:s', strtotime(strstr(str_replace('T', ' ', $result->DATE_BILL), '+', true)));

                    $date_payed = date('Y-m-d H:i:s', strtotime(strstr(str_replace('T', ' ', $result->DATE_PAYED), '+', true)));
                    $pay_voucher_date = date('Y-m-d H:i:s', strtotime(strstr(str_replace('T', ' ', $result->PAY_VOUCHER_DATE), '+', true)));
                    $name = $result->RESPONSIBLE_NAME . " " . $result->RESPONSIBLE_LAST_NAME;

                    $email = $result->RESPONSIBLE_EMAIL;
                    $responsible_id = $result->RESPONSIBLE_ID;
                    $responsible_work = $result->RESPONSIBLE_WORK_POSITION;
                    $price = $result->PRICE;
                    $tax_value = $result->TAX_VALUE;
                    $payed = $result->PAYED;
                    $created_by = $result->CREATED_BY;
                    $recurring = $result->IS_RECURRING;
                    $company_id = $result->UF_COMPANY_ID;
                    if (!array_key_exists($userID, $comm_arr)) {
                        $commission = $this->getCommissionRateFromBitrixApi($userID);
                        $comm_arr[$userID] = $commission;
                        // echo "RUN-".$userID;
                    } else {
                        $commission = $comm_arr[$userID];
                    }
                    // $id=  DB::table('invoice_details')->select('id')->where('invoice_id', $invoice_id)->get();
                    //  die("$id");
                    $data = array('responsible_name' => $name, 'responsible_email' => $email, 'price' => $price,
                        'account_number' => $account_number, 'commission' => $commission, 'date_bill' => $date_bill, 'date_payed' => $date_payed,
                        'responsible_work' => $responsible_work, 'responsible_id' => $responsible_id,
                        'tax_value' => $tax_value, 'is_recurring' => $recurring,
                        'pay_voucher_date' => $pay_voucher_date, 'invoice_id' => $invoice_id,
                        'payed' => $payed,'company_id' => $company_id,'company_name' => $company_name,'company_email' => $company_email,'company_phone' => $company_phone, 'created_by' => $created_by);
                      
                      
                      DB::table('invoice_details')->insertOrIgnore($data);
                     
                    //  DB::table('invoice_details')->insert($data);
                }
                   
                }
                if ($j % 3 == 0)
                    sleep(3);
                //die("12");

            }
            $start = $start + 50;
        }
        }
        return 1;
    }
    
      public function getCompanyFromBitrixApi($invoiceID)
    {
        // echo $userID;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.invoice.get?id=' . $invoiceID,
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
        if(isset($response['result']['INVOICE_PROPERTIES']))      
        $company =$response['result']['INVOICE_PROPERTIES'];
        else
        $company="";
        //$mainresult['firstname'] = $response['result'][0]['NAME'];
        // $mainresult['lastname'] = $response['result'][0]['LAST_NAME'];
        return $company;
    }
    
      public function GetSingleSales(Request $request, $id)
    {    ini_set('memory_limit', '-1');

        ini_set('max_execution_time', '300');
        $idnew =  explode("+", $id);
        $resp_id = $idnew[0];
        $fromDate = $idnew[1];
        $toDate = $idnew[2];

        if(isset($idnew[3]))
        $download=$idnew[3];
       
   
        $allrecord = DB::table('invoice_details')
        ->whereBetween('date_payed', array($fromDate, $toDate))->get();
 
        $mainarr=array();
         $response = $allrecord;
          $userID = 0;
            $j = 0;
            // echo $total; die();
            if(count($response)>0)
            {
            foreach($response as $key => $value) {
              //  print_r($value);
              if( $resp_id==$value->responsible_id)
              {
                    $mainarr[$key]["userid"] =  $value->responsible_id;
                    $mainarr[$key]["price"] =  $value->price;
                    $mainarr[$key]["email"] =  $value->responsible_email;
                    $mainarr[$key]["name"] =  $value->responsible_name;
                    $mainarr[$key]["invoicedate"] =  $value->date_bill;
                    $mainarr[$key]["paymentdate"] =  $value->date_payed;
                    $mainarr[$key]["company_name"] =  $value->company_name;
                    $mainarr[$key]["commission"] = $value->commission;
                    $mainarr[$key]["invoiceid"] = $value->invoice_id;
              }
            }
                $mainarr1 = $mainarr;
        }
        if(isset($download))
        {
            $html = view('dowloadasPdf.singleusercommisionreport', compact('mainarr'))->render();
            $pdf = PDF::loadHTML($html)->setPaper('a4', 'landscape')->setWarnings(false)->
            setOptions(['defaultFont' => 'sans-serif'])->setOptions(['isRemoteEnabled' => false]);
            return $pdf->download('Sales-Commission-Report.pdf');
        }
        else
        return view('Adminview.SalesCommission.viewSingleuser', compact('mainarr'));
    }

    public function GetSalesReport(Request $req)
    {   
        ini_set('max_execution_time', '300');
        $fromDate = $req->get('VarA');
        $toDate = $req->get('VarB');
        $download = $req->get('download');
        $resp_id= $req->get('resp_id');
        $mainarr =array();

        $allrecord = DB::table('invoice_details')
            ->whereBetween('date_payed', array($fromDate, $toDate))->get();


        $response = $allrecord;
        if(count($response)>0 && $download=="")
        {
        $comm_arr = array();
      
            $userID = 0;
            $j = 0;
            foreach($response as $k => $v) {
                $id = $v->responsible_id;
                $result[$id][] = $v->price;
                $comm[$id][] = $v->commission*$v->price/100;
                $name1 = $v->responsible_name;
    
                $name[$id]=$name1;                     
                
               // $result[$id]['price'][] = $v->PRICE;
            }
            foreach($result as $key => $value) {
                //  print_r($value);
                                  
                  $mainarr[] = array('id' => $key, 'price' => number_format(array_sum($value),2,'.',','),
                   'commission'=> number_format(array_sum($comm[$key]),2,'.',','), 'name'=> $name[$key]);
              }
        }
        if (count($response)>0 && $download == "true") 
        {


            $userID = 0;
            $j = 0;
            // echo $total; die();
           
            foreach($response as $key => $value) {
              //  print_r($value);
                    $mainarr[$key]["userid"] =  $value->responsible_id;
                    $mainarr[$key]["price"] =  number_format($value->price,2,'.',',');
                    $mainarr[$key]["email"] =  $value->responsible_email;
                     $mainarr[$key]["invoicedate"] =  $value->date_bill;
                    $mainarr[$key]["paymentdate"] =  $value->date_payed;
                    $mainarr[$key]["company_name"] =  $value->company_name;
                    $mainarr[$key]["name"] =  $value->responsible_name;
                    $mainarr[$key]["commission"] = number_format($value->commission,2,'.',',');
                    $mainarr[$key]["invoiceid"] = $value->invoice_id;
            }
            $mainarr1=$mainarr;
        $html = view('dowloadasPdf.salescommisionreport',compact('mainarr1'))->render();
        $pdf=  PDF::loadHTML($html)->setPaper('a4', 'landscape')->setWarnings(false)->
        setOptions(['defaultFont' => 'sans-serif'])->setOptions(['isRemoteEnabled' => false]);
        return $pdf->download('Sales-Commission-Report.pdf');
        } else
            echo json_encode($mainarr);
        
    }
    
    private function GetInvoiceInformationFromQuickbook($accesstoken,$InvoiceID){
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://quickbooks.api.intuit.com/v3/company/380042956/invoice/'.$InvoiceID,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'User-Agent: QBOV3-OAuth2-Postman-Collection',
            'Accept: application/json',
            'Authorization: Bearer '.$accesstoken
          ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response,true);
        return $response;
    }

    private function Payment_Inspection($id,$accesstoken){
        $id = (int)$id;
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://quickbooks.api.intuit.com/v3/company/380042956/query?minorversion=14',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>"select * from Payment Where Id = '".$id."'",
          CURLOPT_HTTPHEADER => array(
            'User-Agent: QBOV3-OAuth2-Postman-Collection',
            'Accept: application/json',
            'Content-Type: application/text',
            'Authorization: Bearer '.$accesstoken
          ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response,true);
        if(isset($response['QueryResponse']['Payment'])){
        $paymentResult = $response['QueryResponse']['Payment'][0];
        $date_and_ammount = [];
        $payment_Done_date_result  =  $paymentResult['TxnDate'];
        $payment_Ammounnt_total_result  =  $paymentResult['TotalAmt'];
        
        array_push($date_and_ammount,$payment_Done_date_result);
        array_push($date_and_ammount,$payment_Ammounnt_total_result);
        
        return $date_and_ammount;
        }
        return 0;
    }
    
    private function getResponsilePersonIDFromdeal($id){
          $curl = curl_init();
          curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.deal.get?id='.$id,
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
        if(isset($response['result']['ASSIGNED_BY_ID'])){
            return $response['result']['ASSIGNED_BY_ID'];
        }
        return 0;
    }
    
    private function getCommissionRateFromBitrixApi($userID){
       $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/user.get?id=' . $userID,
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
      //  print_r($response);
        if ($response['result'][0]['UF_USR_1639140048988'] == null) {
            $commission= 0;
        } else {
            $commission = $response['result'][0]['UF_USR_1639140048988'];
        }
        //$mainresult['firstname'] = $response['result'][0]['NAME'];
       // $mainresult['lastname'] = $response['result'][0]['LAST_NAME'];
        return $commission;
    }
    
    
    public function commissionCalculation($amount,$percentage){
        
        if($percentage==0){
            return 0;
        }
        
        $new_width = ($percentage / 100) * $amount;
        return $new_width; 
    }
}
