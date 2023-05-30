<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input as Input;
use DB;
use Auth;
use Illuminate\Support\Facades\Redirect;
class CultureController extends Controller
{
    public function Cindex()
    {
        $apps=DB::table('Culturetab')->where('status','new')->paginate(5);
        return view("pages.Culturelist",['apps'=>$apps]);
    }
    public function Culture()
    {
        return view("pages.Culture",['stat'=>""]);
    }
       public function SaveCulture(Request $req)
    {
        $req->validate(['Hname'=>'required','Hlocation'=>'required','Hstatus'=>'required']); 
        $filepath='#';
        if ($req->hasFile('file'))  { 
        $filename=time().'.'.$req->file('file')->getClientOriginalExtension();
        $file=$req->file('file');
        $file->move(public_path('/documents/'),$filename);
        $filepath='/documents/'.$filename;
        }
        $givendate=$this->GetECDateInfo(date('d'),date('m'),date('y'));
        $gdates=explode('-', $givendate);
        $day=$gdates[0];
        $month=$gdates[1];
        $year=$gdates[2];
        $hname=$req->input('Hname');
        $hloc=$req->input('Hlocation');
        $hstat=$req->input('Hstatus');
        //$hnum=explode(' ', $hname);
        $data=array('hname'=>$hname,'hloc'=>$hloc,'hstat'=>$hstat);
        DB::table('Culturetab')->insert($data);
        return view("pages.Culturepg",['stat'=>"The Data Successfully Saved!!!"]); 
    }
    public function Capprove()
    {
        $apps=DB::table('Culturetab')->where('status','new')->orderBy('Culturetab.id')
    ->paginate(5);
        return view("pages.Approveculture",['apps'=>$apps,'suc'=>'']);
    }
    public function Cshow($id)
    {
        $apps=DB::table('Culturetab')->where('id',$id)->get();
        $ids= array('2,3,4');
        $users=DB::table('users')->where('roleid','2')
        ->orWhere('roleid','3')
        ->orWhere('roleid','4')
        ->get();
        return view("pages.Cultureapprove",['app'=>$apps[0],'stat'=>'','users'=>$users,'suc'=>'']);
    }
}
