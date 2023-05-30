<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input as Input;
use DB;
use Auth;
use Illuminate\Support\Facades\Redirect;
class SportController extends Controller
{
    public function Sindex()
    {
        $apps=DB::table('Heritagetab')->where('status','new')->paginate(5);
        return view("pages.Heritagelist",['apps'=>$apps]);
    }
    public function Sport()
    {
        return view("pages.Sport",['stat'=>""]);
    }
       public function SaveSport(Request $req)
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
        DB::table('Sporttab')->insert($data);
        return view("pages.Sportpg",['stat'=>"The Data Successfully Saved!!!"]); 
    }

    public function Sapprove()
    {
        $apps=DB::table('Sporttab')->where('status','new')->orderBy('Sporttab.id')
    ->paginate(5);
        return view("pages.Approvesport",['apps'=>$apps,'suc'=>'']);
    }
    public function Sshow($id)
    {
        $apps=DB::table('sporttab')->where('id',$id)->get();
        $ids= array('2,3,4');
        $users=DB::table('users')->where('roleid','2')
        ->orWhere('roleid','3')
        ->orWhere('roleid','4')
        ->get();
        return view("pages.Sportapprove",['app'=>$apps[0],'stat'=>'','users'=>$users,'suc'=>'']);
    }
}
