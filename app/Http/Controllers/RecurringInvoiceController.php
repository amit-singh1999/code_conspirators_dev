<?php

namespace App\Http\Controllers;

require '../vendor/quickbook/vendor/autoload.php';

use DB;
use Hash;
use Mail;
use Session;
use Redirect;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

$user = Auth::user();

use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2AccessToken;


class RecurringInvoiceController extends Controller
{

  public function index()
  {
    $result2 = DB::select(DB::raw("SELECT * FROM `recurring_invoices` where status!='archive' ORDER BY `id` DESC"));
    // $collect = collect($data);

    //   $data =  json_decode($result2,true);
    $data = $result2;
    $newarray = [];
    foreach ($data as $key => $newdata) {
      $newarray[$key]["id"] = $newdata->id;
      $deal =  json_decode($newdata->dealdata, true);
      //dd($deal);
      if (isset($deal["result"]["ID"]))
        $dealID = $deal["result"]["ID"];
      if ($deal["result"]["COMPANY_ID"] != 0)
        $companyID = $deal["result"]["COMPANY_ID"];
      else
        $companyID = "";

      //  $projectname =  $dealID= $deal["result"][""];
      if (isset($companyID)) {
        if ($companyID == 0) {
          $company_Title = 0;
        } else {



          $curl = curl_init();
          curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.company.get?id=' . $companyID,
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
          if (isset($responsehere->result))
            $company_Title = $responsehere->result->TITLE;
        }
      }
      //dd($deal);
      $prdct = json_decode($newdata->product, true);
      if (isset($company_Title) && $company_Title != "")
        $newarray[$key]["company_title"] = $company_Title;
      else
        $newarray[$key]["company_title"] = $deal["result"]["TITLE"];
      //$newarray["Rate"]=
      $newarray[$key]["credit"] = $newdata->credit;
      $newarray[$key]["active"] =  $newdata->status;
      if (isset($newdata->invoice_date))
        $newarray[$key]["lastbilled"] = $newdata->invoice_date;
      else
        $newarray[$key]["lastbilled"] = "Not billed";
      $newarray[$key]["prdct"] = $prdct;
      $newarray[$key]["type"] = $newdata->type;
      //echo $prdname= $prdct[0]["PRODUCT_NAME"];

    }
    $page = 1;
    $size = 3;
    $collect = collect($newarray);


    /*     $newarray = new LengthAwarePaginator(
                         $collect->forPage($page, $size),
                         $collect->count(), 
                         $size, 
                         $page
                       );
       
   */
    return view('Adminview.paymentmanagement', compact('newarray'));
  }
  public function UpdateMonthlyItem(Request $req, $id)
  { //dd($req);

    $editdata = DB::select(DB::raw("SELECT * FROM `recurring_invoices` where  id=" . $id));
    // dd($editdata);
    $prdct = json_decode($editdata[0]->product, true);
    $prdct[0]["PRODUCT_NAME"] = $req->Product_name;
    $prdct[0]["PRODUCT_price"] = $req->Product_price;

    $prdct = json_encode($prdct);
    if ($req->Credit == "on")
      $credit = "active";
    else
      $credit = "inactive";

    if ($req->Active == "on")
      $active = "active";
    else
      $active = "inactive";


    if ($req->Archive == "archive") {
      DB::table('recurring_invoices')->where('id', $id)->update(array(
        'status' => "archive", 'credit' => $credit, 'product' => $prdct
      ));
    } else {
      DB::table('recurring_invoices')->where('id', $id)->update(array(
        'status' => $active, 'credit' => $credit, 'product' => $prdct
      ));
    }
    Session::flash('message', "Record Updated successfully");
    return Redirect::back();
  }
  public function EditMonthlyItem($id)
  {

    $editdata = DB::select(DB::raw("SELECT * FROM `recurring_invoices` where  id=" . $id));
    //$editdata=$editdata[0];
    $newarray = [];
    foreach ($editdata as $key => $newdata) {
      $newarray[$key]["id"] = $newdata->id;
      $deal =  json_decode($newdata->dealdata, true);
      $dealID = $deal["result"]["ID"];
      $companyID = $deal["result"]["COMPANY_ID"];
      //  $projectname =  $dealID= $deal["result"][""];
      if (isset($companyID)) {
        if ($companyID == 0) {
          $company_Title = 0;
        } else {



          $curl = curl_init();
          curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.company.get?id=' . $companyID,
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
          if (isset($responsehere->result))
            $company_Title = $responsehere->result->TITLE;
        }
      }
      //dd($deal);
      $prdct = json_decode($newdata->product, true);
      if (isset($company_Title) && $company_Title != "")
        $newarray[$key]["company_title"] = $company_Title;
      else
        $newarray[$key]["company_title"] = $deal["result"]["TITLE"];
      //$newarray["Rate"]=
      $newarray[$key]["credit"] = $newdata->credit;
      $newarray[$key]["active"] =  $newdata->status;
      if (isset($newdata->invoice_date))
        $newarray[$key]["lastbilled"] = $newdata->invoice_date;
      else
        $newarray[$key]["lastbilled"] = "Not billed";
      $newarray[$key]["prdct"] = $prdct;
      //echo $prdname= $prdct[0]["PRODUCT_NAME"];

    }
    $editdata = $newarray[0];

    //  dd($editdata);
    return view('Adminview.Payments.edit', compact('editdata'));

    //    return view('Adminview.paymentmanagement');
  }
  public function addretaineritem()
  {
    // dd("HI");
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.productsection.list',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => '{
        "order": {"NAME": "ASC"},
        "filter": {"CATALOG_ID": 25   },
        }',
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'Cookie: BITRIX_SM_SALE_UID=11; qmb=.'
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    $data = json_decode($response, true);
    $data = $data['result'];
    $productarr = array();
    
    foreach ($data as $key => $section) {
      dd($section);
      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.product.list',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{
                    "order": {"NAME": "ASC"},
                    "filter": {"CATALOG_ID": 25,"SECTION_ID":' . $section["ID"] . '   },
                    "select": ["ID", "NAME", "ACTIVE","DESCRIPTION", "PRICE", "SECTION_ID"]
                }',
        CURLOPT_HTTPHEADER => array(
          'Content-Type: application/json',
          'Cookie: BITRIX_SM_SALE_UID=11; qmb=.'
        ),
      ));

      $response = curl_exec($curl);
      curl_close($curl);
      $item = json_decode($response, true);
      
      $items[] = $item['result'];

      foreach ($items as $item) {
        
        $productarr[$section["NAME"]] = $item;
      }
    }
    //dd($productarr);

    return view('Adminview.Payments.addretainer', compact('productarr'));
  }
  public function sendinvoicenow($id)
  {
    $invoice_create = new ApiController;
    $datanew = DB::select(DB::raw("SELECT * FROM `recurring_invoices` where  id=" . $id));
    // dd($datanew);
    foreach ($datanew as $datanew) {
      $dealarr = json_decode($datanew->dealdata);
      $dealarr = $dealarr->result;
      $dealid = $dealarr->ID;
      //dd($dealarr);
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.quote.list',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{
            "filter":
        {
                   "DEAL_ID":"' . $dealid . '",
                   ">OPPORTUNITY":"0"

                }
        }',
        CURLOPT_HTTPHEADER => array(
          'Content-Type: application/json',
          'Cookie: BITRIX_SM_SALE_UID=11; qmb=.'
        ),
      ));

      $response = curl_exec($curl);
      $dataquote = json_decode($response, true);
      $dataquote = $dataquote["result"][0];
      $quotearr = (object)$dataquote;
      //dd($quotearr);

      //dd($quotearr);
      $productdataarraypush = json_decode($datanew->product, true);
      if (isset($productdataarraypush[0]["Monthly"])) {
        $productdataarraypush[0]["Initial"] = 1;
        $productdataarraypush[0]["Existing"] = $id;
      }
      if (isset($productdataarraypush["Monthly"])) {
        $productdataarraypush["Initial"] = 1;
        $productdataarraypush["Existing"] = $id;
      }

      $invoice_result = $invoice_create->quickbook_invoice_create_api($quotearr, $productdataarraypush);
      Session::flash('message', "Invoice send successfully");
      return Redirect::back();
    }
  }
  public function SaveLineItem(Request $req)
  {
    //dd($req);
    if (isset($req->item_type) && $req->item_type == "onetime") {
      $initial = 1;
      $monthly = 0;
    } else {
      $initial = 0;
      $monthly = 1;
    }
    $prdctarr = '{"ID":"' . $req->product_id . '","PRODUCT_ID":' . $req->product_id . ',"PRODUCT_NAME":"' . $req->productname . '","PRODUCT_PRICE":' . $req->product_price . ',"PRODUCT_DESCRIPTION":"","Monthly":' . $monthly . ',"Initial":' . $initial . '}';
    $dealdata = '{"result":{"ID":"","COMPANY_ID":"' . $req->company_id . '"}}';


    $values1 = array('product' => $prdctarr, 'dealdata' => $dealdata, 'create_date' =>    Date('Y-m-d h:i:s'), 'type' => $req->item_type);
    DB::table('recurring_invoices')->insert($values1);

    if ($req->productname == "Other")
      $req->productname = $req->customproduct;
    $values = array(
      'company_name' => $req->companyname, 'project_name' => $req->projectname, 'product_name' => $req->productname, 'company_id' => $req->company_id,
      'project_id' => $req->project_id, 'product_id' => $req->product_id,  'rate' => $req->product_price, 'quantity' => $req->quantity, 'createdat' => Date('Y-m-d h:i:s')
    );
    DB::table('lineitem_invoices')->insert($values);






    //   DB::table('lineitem_invoices')->insert($values);
    Session::flash('message', "Record Added successfully");
    return Redirect::back();
  }
}
