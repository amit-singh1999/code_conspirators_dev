<?php


namespace App\Http\Controllers\admindashboard;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use PDF;

class performanceBonusController extends Controller
{
    public function index()
    {
        return view('Adminview.PerformanceBonus.index');
    }

    public function InsertBonusData()
    {
        ini_set('max_execution_time', '3000');
        ini_set('memory_limit', '-1');


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
      //  print_r($response);
        
        $total = $response['total'];
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

            $response = $response['result'];

            // dd($response);
            

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
                        else
                            $company_name = $value['COMPANY'];

                        $company_email = $value['EMAIL'];
                        $company_phone =  $value['PHONE'];
                    }
                   
                    $userID = $result->RESPONSIBLE_ID;
                    $account_number = $result->ACCOUNT_NUMBER;
                    $date_bill = date('Y-m-d H:i:s', strtotime(strstr(str_replace('T', ' ', $result->DATE_BILL), '+', true)));
                    if($result->DATE_PAYED!="")
                    $date_payed = date('Y-m-d H:i:s', strtotime(strstr(str_replace('T', ' ', $result->DATE_PAYED), '+', true)));
                    else
                    $date_payed="";
                   
                    if($result->PAY_VOUCHER_DATE!="")  
                    $pay_voucher_date = date('Y-m-d H:i:s', strtotime(strstr(str_replace('T', ' ', $result->PAY_VOUCHER_DATE), '+', true)));
                    else
                    $pay_voucher_date="";
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
                    $bonus= 2.00;
                    // $id=  DB::table('invoice_details')->select('id')->where('invoice_id', $invoice_id)->get();
                    //  die("$id");
                    $data = array('responsible_name' => $name, 'responsible_email' => $email, 'price' => $price,
                        'account_number' => $account_number, 'bonus' => $bonus, 'date_bill' => $date_bill, 'date_payed' => $date_payed,
                        'responsible_work' => $responsible_work, 'responsible_id' => $responsible_id,
                        'tax_value' => $tax_value, 'is_recurring' => $recurring,
                        'pay_voucher_date' => $pay_voucher_date, 'invoice_id' => $invoice_id,
                        'payed' => $payed,'company_id' => $company_id,'company_name' => $company_name,'company_email' => $company_email,'company_phone' => $company_phone, 'created_by' => $created_by);
                      
                      
                   
                         $invoicenum = DB::table('bonus_details')->where('invoice_id', $invoice_id)->pluck('account_number');
                        
                        if(count($invoicenum)==0)    
                        DB::table('bonus_details')->insertOrIgnore($data);
                     
                    //  DB::table('invoice_details')->insert($data);
                }
                   
                             if ($j % 3 == 0)
                    sleep(3);
                //die("12");

            }
            $start = $start + 50;
        }
        return "HELLO INSERT";
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
        $company =$response['result']['INVOICE_PROPERTIES'];
        
        //$mainresult['firstname'] = $response['result'][0]['NAME'];
        // $mainresult['lastname'] = $response['result'][0]['LAST_NAME'];
        return $company;
    }
    public function GetSingleBonus(Request $request, $id)
    {
        ini_set('max_execution_time', '3000');
        ini_set('memory_limit', '-1');


        $idnew =  explode("+", $id);
        $resp_id = $idnew[0];
        $fromDate = $idnew[1];
        $toDate = $idnew[2];

        if(isset($idnew[3]))
        $download=$idnew[3];
       
   
        $allrecord = DB::table('bonus_details')
        ->whereBetween('date_bill', array($fromDate, $toDate))->get();
 
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
                    $name =  $mainarr[$key]["name"] ;
                    $mainarr[$key]["invoicedate"] =  $value->date_bill;
                    $mainarr[$key]["paymentdate"] =  $value->date_payed;
                    $mainarr[$key]["company_name"] =  $value->company_name;
                    $mainarr[$key]["bonus"] = $value->bonus;
                    $mainarr[$key]["invoiceid"] = $value->invoice_id;
              }
            }
                $mainarr1 = $mainarr;
        }
        if(isset($download))
        {
            $html = view('dowloadasPdf.singleuserbonusreport', compact('mainarr'))->render();
            $pdf = PDF::loadHTML($html)->setPaper('a4', 'landscape')->setWarnings(false)->
            setOptions(['defaultFont' => 'sans-serif'])->setOptions(['isRemoteEnabled' => false]);
            return $pdf->download($name.'Performance-Bonus-Report.pdf');
        }
        else
        return view('Adminview.PerformanceBonus.viewSingleuserbonus', compact('mainarr1'));
    }
    public function GetBonusReport(Request $req)
    {   
        ini_set('max_execution_time', '3000');
        $fromDate = $req->get('VarA');
        $toDate = $req->get('VarB');
        $download = $req->get('download');
        $resp_id= $req->get('resp_id');
        $mainarr =array();

        $allrecord = DB::table('bonus_details')
            ->whereBetween('date_bill', array($fromDate, $toDate))->get();


        $response = $allrecord;
        if(count($response)>0 && $download=="")
        {
        $comm_arr = array();
      
            $userID = 0;
            $j = 0;
            foreach($response as $k => $v) {
                $id = $v->responsible_id;
                $result[$id][] = $v->price;
                $comm[$id][] = $v->bonus*$v->price/100;
                $name1 = $v->responsible_name;
    
                $name[$id]=$name1;                     
                
               // $result[$id]['price'][] = $v->PRICE;
            }
            foreach($result as $key => $value) {
                //  print_r($value);
                                  
                  $mainarr[] = array('id' => $key, 'price' =>  number_format(array_sum($value),2,'.',','),
                   'bonus'=>number_format(array_sum($comm[$key]),2,'.',','), 'name'=> $name[$key]);
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
                    $mainarr[$key]["price"] =  $value->price;
                    $mainarr[$key]["email"] =  $value->responsible_email;
                    $mainarr[$key]["invoicedate"] =  $value->date_bill;
                    $mainarr[$key]["paymentdate"] =  $value->date_payed;
                    $mainarr[$key]["name"] =  $value->responsible_name;
                    $mainarr[$key]["bonus"] = $value->bonus;
                    $mainarr[$key]["invoiceid"] = $value->invoice_id;
            }
            $html = view('downloadasPdf.performancebonusreport', compact('mainarr'))->render();
            $pdf = PDF::loadHTML($html)->setPaper('a4', 'landscape')->setWarnings(false)->
            setOptions(['defaultFont' => 'sans-serif'])->setOptions(['isRemoteEnabled' => false]);
            return $pdf->download('Performance-Bonus-Report.pdf');
        } else
            echo json_encode($mainarr);
        // echo $response;
    }

    
}