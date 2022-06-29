<?php

namespace App\Http\Controllers\admindashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Casestudy;
use Session;
use Redirect;
use DB;

class Casestudycontroller extends Controller
{
    
    
    public function show(){
        

           $Casestudy = Casestudy::orderBy('Clientname')->get();
           
           return view('Adminview.Casestudy.templateIndex',compact('Casestudy'));
    }
    
    public function index(){
      
        return view('Adminview.Casestudy.create');
    }
    
    public  function saveCasestudyData(Request $req){
        //validation code comes here in 
        
        $req->validate([
            
            'Client_logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'ClientName' => 'required',
            'Casestudyname' => 'required',
            'description' => 'required',
         ],
         
         [ 
             'Client_logo.required' => '  field can not be blank value.',
             'ClientName.required' => '  field can not be blank value.',
             'Casestudyname.required' => 'field can not be blank value.',
         ]
         
         );
         
         $imageName = time().'.'.$req->Client_logo->extension();  
         $req->Client_logo->move(public_path('ClientLogo'), $imageName);
         $ClientName = $req->ClientName;
         $Casestudyname = $req->Casestudyname;
         $description = $req->description;
          $moreinfo= $req->moreinfo;
         
         $Dataadded_in_bitrix_response  = $this->AddDatato_Bitrix_quote_list($Casestudyname);
         
         if($Dataadded_in_bitrix_response){
             
             $Casestudy = new Casestudy;
             $Casestudy->Clientname =    $ClientName;
             $Casestudy->caseStudyName =    $Casestudyname;
             $Casestudy->description =    $description;
             $Casestudy->moreinfo =    $moreinfo;
             
             $Casestudy->Client_company_logo =      $imageName;
             $Casestudy->save();
             Session::flash('message', "Record saved successfully");
             // calling database update function
             $this->UpdateDatabaseWithCasestudiesID();
             
             return Redirect::back();
             
         }
                 
                 
    }
    
    public  function EditCasestudyData($id){
        $editdata=Casestudy::find($id);
        return view('Adminview.Casestudy.edit',compact('editdata'));
    }
    
    public  function UpdateCasestudyData(Request $req,$id){
             //dd($req);
        
             //validation code here
             $ClientName = $req->ClientName;
             $Casestudyname = $req->Casestudyname;
             $description = $req->description;
             $moreinfo= $req->moreinfo;
             $updatedata=Casestudy::find($id);
             $updatedatacopy = json_decode($updatedata,true);
             $BitrixMapId=$updatedatacopy['BitrixMapId']; 
             $updatedata->Clientname =    $ClientName;
             $updatedata->caseStudyName =    $Casestudyname;
             $updatedata->description =    $description;
             $updatedata->moreinfo =    $moreinfo;
             if(isset($req->Client_logo)){
             $imageName = time().'.'.$req->Client_logo->extension();  
             $req->Client_logo->move(public_path('ClientLogo'), $imageName);
             $updatedata->Client_company_logo =      $imageName;
             }
            // $Dataadded_in_bitrix_response  = $this->AddDatato_Bitrix_quote_list($Casestudyname);
    
             $updatedata->save();
             $this->Update_custom_field_in_quote_list_CasestudiesID($BitrixMapId,$Casestudyname);
             Session::flash('message', "Record Updated successfully");
             return Redirect::back();
             
    }
    
    public  function DeleteCasestudyData($id){
       
     
           $user=Casestudy::find($id);
           $userdata = json_decode($user,true);
           
           $bitrixmapID = $userdata['BitrixMapId'];
           $this->Delete_custom_field_in_quote_list_CasestudiesID($bitrixmapID);
           
           $user->delete();
           Session::flash('message', "Successfully deleted record");
           return Redirect::back();
    }
    
    
   
        private function AddDatato_Bitrix_quote_list($newglobalvariable){
                //dd($newglobalvariable);
               /* Update case study in deal first*/
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.deal.userfield.update',
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS =>'{
                    "id": 1003,
                    "fields": {
                        "LIST": [
                            {
                                "VALUE": "'.$newglobalvariable.'",
                                "DEF": "N"
                            }
                        ]
                    }
                }',
                  CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Cookie: BITRIX_SM_SALE_UID=11; qmb=.'
                  ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                $response = json_decode($response,true);
               /* Update case study in quote now*/
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.quote.userfield.update',
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS =>'{
                    "id": 969,
                    "fields": {
                        "LIST": [
                            {
                                "VALUE": "'.$newglobalvariable.'",
                                "DEF": "N"
                            }
                        ]
                    }
                }',
                  CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Cookie: BITRIX_SM_SALE_UID=11; qmb=.'
                  ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                $response = json_decode($response,true);
               // dd($response);
                return $response['result'];
        }
        
        
        private function UpdateDatabaseWithCasestudiesID(){
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.deal.userfield.get?id=1003',
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
                $data = $response;
                $newdata = json_decode($data);
                //all data comes here
                $newarray = [];
                for ($count = 0; $count < count($newdata->result->LIST); $count++) {
                    $newarray[] = $newdata->result->LIST[$count]->ID;
                    $templateidhere = $newdata->result->LIST[$count]->ID;  //  id ko le rha hai
                    $templatevaluehere_name = $newdata->result->LIST[$count]->VALUE; //value ko le rha hai 
                    $template_update_check_condition = Casestudy::Where([['caseStudyName', '=', "$templatevaluehere_name"], ['BitrixMapId', '=', "$templateidhere"]])->first();
                    if (!$template_update_check_condition) {
                        // update kr rha hai 
                        DB::table('casestudies')->where('caseStudyName', $templatevaluehere_name)->update(array('BitrixMapId' => $templateidhere,));
                    }
                }
        }
        
        private function Delete_custom_field_in_quote_list_CasestudiesID($id){
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.deal.userfield.update',
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS =>'{
                    "id": 1003,
                    "fields": {
                        "LIST": [
                            {
                                "ID": "'.$id.'",
                                "VALUE": "",
                                "DEF": "N"
                            }
                        ]
                    }
                }',
                  CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Cookie: BITRIX_SM_SALE_UID=11; qmb=.'
                  ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                $response = json_decode($response,true);
                
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.quote.userfield.update',
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS =>'{
                    "id": 969,
                    "fields": {
                        "LIST": [
                            {
                                "ID": "'.$id.'",
                                "VALUE": "",
                                "DEF": "N"
                            }
                        ]
                    }
                }',
                  CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Cookie: BITRIX_SM_SALE_UID=11; qmb=.'
                  ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                $response = json_decode($response,true);
        }
        
        
        private function Update_custom_field_in_quote_list_CasestudiesID($id,$newname){
              $curl = curl_init();
              curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.quote.userfield.update',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>'{
                "id": 969,
                "fields": {
                    "LIST": [
                        {
                            "ID": "'.$id.'",
                            "VALUE": "'.$newname.'",
                            "DEF": "N"
                        }
                    ]
                }
            }',
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: BITRIX_SM_SALE_UID=11; qmb=.'
              ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            // echo $response;
        }
    
}
