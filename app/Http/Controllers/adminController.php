<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\templatemodel;
use DB;

class adminController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $alltemplatedata = templatemodel::orderBy("name")->get();
        // dd($alltemplatedata[0]['attributes']);
        return view('template.templateupload', compact('alltemplatedata'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('template.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
                
               //validation 
                $this->validate($request, [
                    'name' => 'required|mimes:html',
                    'detail' => 'required|max:200',
                    
                ]);
                   
            //  dd("subal");
              $data = new templatemodel;
            //for checking if  template is alread
              if ($files = $request->file('name')) {
              $name = $files->getClientOriginalName();
              $name = str_replace(' ', '_', $name);
              $name = preg_replace('/\\.[^.\\s]{3,4}$/', '', $name);
              if ($data::where('name', '=', $name)->exists()) {
                return redirect('/admin');
             }
             }
            $newglobalvariable = "";
            if ($files = $request->file('name')) {
            $name = $files->getClientOriginalName();
            $name = str_replace(' ', '_', $name);
            $name= preg_replace('/\\.[^.\\s]{3,4}$/', '', $name);
            fopen(resource_path( 'views/usertemplate/' . $name.'.blade.php' ), 'w' );  // writing file name in view cool
            $files->move('images', $name);  //file ko public/images me move kr rha hai
            $dir = dirname(__DIR__,3);
            $dir= $dir.'/resources/views/usertemplate/';
            $get_file = file_get_contents('images/'.$name);
            file_put_contents($dir.$name.'.blade.php', $get_file);
            $status=unlink('images/'.$name); 
            $data->name = $name;
            $newglobalvariable = $name;
        }
        $data->detail = $request->detail;
        $data->save();
        
        
        
        //api  code goes here 
        
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
            CURLOPT_POSTFIELDS => '{	
                "id":787,
                "fields":{
                "LIST":[{
                  	
                    "VALUE":  "' . $newglobalvariable . '",
                    "DEF": "N"
                }]
            }
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: BITRIX_SM_SALE_UID=11; qmb=.'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        // get method we have to take id from here so that it will be helpfull at time  of mapping 
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://cc.codeconspirators.com/rest/13/kpylyymjqouoe0v2/crm.quote.userfield.get?id=787',
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
        $newarray = [];
        for ($count = 0; $count < count($newdata->result->LIST); $count++) {
            $newarray[] = $newdata->result->LIST[$count]->ID;
            $templateidhere = $newdata->result->LIST[$count]->ID;  //  id ko le rha hai
            $templatevaluehere_name = $newdata->result->LIST[$count]->VALUE; //value ko le rha hai 
            $template_update_check_condition = templatemodel::Where([['name', '=', "$templatevaluehere_name"], ['template_id', '=', "$templateidhere"]])->first();
            if (!$template_update_check_condition) {
                //update kr rha hai 
                DB::table('template')->where('name', $templatevaluehere_name)->update(array('template_id' => $templateidhere,));
            }
        }
        
        
        
        
        
        
        
        
        
        
        
        
        return redirect('/admin');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $edit=templatemodel::find($id);  
        return view('template.edit', compact('edit'));  
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   
      
      //validation 
        $this->validate($request, [
         'name' => 'required|mimes:html|',
         'detail' => 'required|max:200',
        ]);
                   
        
       $data = templatemodel::findOrFail($id);
       $dir = dirname(__DIR__,3);
       $dir= $dir.'/resources/views/usertemplate/'. $data->name.'.blade.php';
       $status=unlink($dir);
       Session::flash('flash_message', 'Task successfully deleted!');
       $newname= " ";
       if ($files = $request->file('name')) {
           $name = $files->getClientOriginalName();
           $name = str_replace(' ', '_', $name);
           $name= preg_replace('/\\.[^.\\s]{3,4}$/', '', $name);
           fopen(resource_path( 'views/usertemplate/' . $name.'.blade.php' ), 'w' );  // writing file name in view cool
           $files->move('images', $name);  //file ko public/images me move kr rha hai
           $dir = dirname(__DIR__,3);
           $dir= $dir.'/resources/views/usertemplate/';
           $get_file = file_get_contents('images/'.$name);
           file_put_contents($dir.$name.'.blade.php', $get_file);
           $status=unlink('images/'.$name); 
           $data->name = $name;
           $newname=$name;
       }
       $data->detail = $request->detail;
       $data->save();
       
       //curl ko update krne ke liye kya chhaiye 
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
            CURLOPT_POSTFIELDS =>'
            {	
                "id":787,
                "fields":{
                "LIST":[{
                    
                    "ID": "'.$data->template_id.'",
                    "VALUE": "'.$newname .'",
                    "DEF": "N"
                }]
            }
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: BITRIX_SM_SALE_UID=11; qmb=.'
            ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
       
       
       return redirect('/admin');
        
        
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
     
        
        $task = templatemodel::findOrFail($id);
    
        $dir = dirname(__DIR__,3);
        $dir= $dir.'/resources/views/usertemplate/'.$task->name.'.blade.php';
        $status=unlink($dir);
        $task->delete();
        Session::flash('flash_message', 'Task successfully deleted!');
        return redirect('/admin');
        
    }
}
