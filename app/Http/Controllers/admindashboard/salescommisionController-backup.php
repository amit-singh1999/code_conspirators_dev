<?php

namespace App\Http\Controllers\admindashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class salescommisionController extends Controller
{
    public function index()
    {
        return view('Adminview.SalesCommission.index');
    }
    
    public function GetSalesReport(Request $req){
      $Filterdate = $req->FilterDate ;
      $AccessTOken = app('App\Http\Controllers\ApiController')->quickbook_invoice_sales_commision_generation();
      //   dd($AccessTOken);
      $Invoice_Ids = DB::table('InvoiceResponsibleID')->get();
      $Invoice_Ids = json_decode( $Invoice_Ids,true);
      $mainarrayData = [];
      foreach($Invoice_Ids as $Value){
        $invoiceID = $Value['InvoiceID'];
        $DealIdforResponsiblePerson  = $Value['DealID'];
        $response  = $this->GetInvoiceInformationFromQuickbook($AccessTOken,$invoiceID);
        if(isset($response['Invoice'])){
        $invoiceArray = $response['Invoice'];
        if(isset($invoiceArray['EInvoiceStatus']) and $invoiceArray['EInvoiceStatus']=='Paid' ){
            if(isset($invoiceArray['LinkedTxn'][0])){
            $filtering_Payment_id = $invoiceArray['LinkedTxn'][0]['TxnId'];
            $payment_made_by_user_date = $this->Payment_Inspection($filtering_Payment_id,$AccessTOken);
            if($payment_made_by_user_date != 0){
            $comparedateBackend = $payment_made_by_user_date[0];  
            $Ammount = $payment_made_by_user_date[1];
            if($Filterdate <= $comparedateBackend){
                
                $responsible_person_id_from_deal = $this->getResponsilePersonIDFromdeal($DealIdforResponsiblePerson);
                $commision_Rate_info  =  $this->getCommissionRateFromBitrixApi($responsible_person_id_from_deal);
               
                $Userinfo['firstname'] =     $commision_Rate_info['firstname'];
                $Userinfo['lastname'] = $commision_Rate_info['lastname'];
                $Userinfo['Commission'] = $this->commissionCalculation($Ammount,$commision_Rate_info['commission']);
                $Userinfo['InvoiceId'] =  $invoiceID;
                $Userinfo['Amount'] =     $Ammount; 
                $Userinfo['DealId'] = $DealIdforResponsiblePerson;
                array_push($mainarrayData,$Userinfo);
            }
            
            }
            }
          }
        }
      }
    //   dd($mainarrayData);
      return view('Adminview.SalesCommission.index',compact('mainarrayData'));
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
          CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/user.get?id='.$userID,
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
        if($response['result'][0]['UF_USR_1639140048988'] == null){
        $mainresult['commission'] = 0;
        }else{
        $mainresult['commission'] = $response['result'][0]['UF_USR_1639140048988'];    
        }
        $mainresult['firstname'] = $response['result'][0]['NAME']; 
        $mainresult['lastname'] = $response['result'][0]['LAST_NAME']; 
        return $mainresult; 
    }
    
    
    private function commissionCalculation($amount,$percentage){
        
        if($percentage==0){
            return 0;
        }
        
        $new_width = ($percentage / 100) * $amount;
        return $new_width; 
    }
}
