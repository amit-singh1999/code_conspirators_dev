<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
use Session;
use Redirect;
use DB;

class SettingController extends Controller
{
    public function index()
    {
        $qry= 'select * from general_settings where id=1';
            $select_docs = DB::select($qry);
        foreach($select_docs as $key => $doc)
            {
             $msgoninv=$doc->message_on_invoice;
             //$msgonstat=$doc->message_on_statement;
            }
       // dd();
        return view('Adminview.generalsetting', compact("msgoninv"));
    }
    public function save_settings(Request $req)
    {   //dd($req);
        $msg_on_inv= $req->msgoninv;
        //$msg_on_stat= $req->msgonstat;
        $id=1;
        $values = array('message_on_invoice' => $msg_on_inv);
       DB::table('general_settings')
                            ->where('id',$id )
                            ->update($values);
                
    //    DB::table('general_settings')->insert($values);
         Session::flash('message', "Record Updated successfully");
        
        $qry= 'select * from general_settings where id=1';
            $select_docs = DB::select($qry);
        foreach($select_docs as $key => $doc)
            {
             $msgoninv=$doc->message_on_invoice;
           //  $msgonstat=$doc->message_on_statement;
            }
       
        return view('Adminview.generalsetting', compact("msgoninv"));        
    }
}