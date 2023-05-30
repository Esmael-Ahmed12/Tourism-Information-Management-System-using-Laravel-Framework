<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input as Input;
use DB;
use Auth;
class HeritageController extends Controller
{
    public function Rindex()
    {
    	$apps=DB::table('relishtab')->where('status','new')->paginate(5);
    	return view("pages.Relishlist",['apps'=>$apps]);
    }
    public function hindex()
    {
        $apps=DB::table('relishtab')->where('status','new')->paginate(5);
        return view("pages.Relishlist",['apps'=>$apps]);
    }
     public function Rindex1()
    {
        $apps=DB::table('relishtab')->where('status','new')->paginate(5);
        return view("pages.Relishlist1",['apps'=>$apps]);
    }
    public function RelishPage()
    {
    	return view("pages.RelishPage",['stat'=>""]);
    }
       public function SaveRelish(Request $req)
    {
    	$req->validate(['rname'=>'required','catagory'=>'required','woreda'=>'required', 'kebele'=>'required', 'place'=>'required','croad'=>'required']); 
        $filepath='#';
        if ($req->hasFile('file'))  { 
        $filename=time().'.'.$req->file('file')->getClientOriginalExtension();
        $file=$req->file('file');
        $file->move(public_path('/documents/'),$filename);
        $filepath='/documents/'.$filename;
        }
        $day=date('d');
        $month= date('m');
        $year= date('Y');
        $rname=$req->input('rname');
        $catagory=$req->input('catagory');
    	$woreda=$req->input('woreda');
        $kebele=$req->input('kebele');
        $place=$req->input('place');
        $croad=$req->input('croad');
	$data=array('rname'=>$rname,'catagory'=>$catagory,'woreda'=>$woreda,'kebele'=>$kebele,'place'=>$place,'croad'=>$croad, 'photo'=>$filepath,'status'=>'new','day'=>$day,'month'=>$month,'year'=>$year);
		DB::table('relishtab')->insert($data);
    	return view("pages.RelishPage",['stat'=>"The Information is Successfully Saved!"]);	
    }
    public function editRelish($id)
    {
     $apps=DB::table('relishtab')
        ->where(['relishtab.id' => $id])
        ->get();
        return view("pages.editRelish",['app'=>$apps[0],'stat'=>'']);   
    }
    public function saverelishEdit(Request $req)
    {
        $req->validate(['rname'=>'required','catagory'=>'required','woreda'=>'required','kebele'=>'required','place'=>'required','croad'=>'required']); 
        $filepath='#';
        if ($req->hasFile('file'))  { 
        $filename=time().'.'.$req->file('file')->getClientOriginalExtension();
        $file=$req->file('file');
        $file->move(public_path('/documents/'),$filename);
        $filepath='/documents/'.$filename;
        }
        $aid=$req->input('id');
        $rname=$req->input('rname');
        $catagory=$req->input('catagory');
        $woreda=$req->input('woreda');
        $kebele=$req->input('kebele');
        $place=$req->input('place');
        $croad=$req->input('croad');
        $data=array('rname'=>$rname,'catagory'=>$catagory,'woreda'=>$woreda,'kebele'=>$kebele,'place'=>$place,'croad'=>$croad, 'photo'=>$filepath);
        DB::table('relishtab')
                    ->where('id', $aid)
                    ->update($data);
        return view("pages.RelishPage",['stat'=>"The Information is Successfully Updated!"]);  
    }
    public function editRelish1()
    {
     $apps=DB::table('relishtab')->where('status','new')->paginate(5);
        return view("pages.editRelish1",['app'=>$apps[0],'stat'=>'']); 
    }
    public function Relishlist1()
    {
      $apps=DB::table('relishtab')->where('status','new')->paginate(5);
        return view("pages.Relishlist1",['app'=>$apps[0],'stat'=>'']);
    }
    public function Rapprove(Request $req)
    {
         $aid=$req->input('id');
            DB::table('relishtab')
                    ->where('id', $aid)
            ->update(['status'=>'approved']);
            $apps=DB::table('relishtab')->where('status','new')->paginate(5);
    	return view("pages.Relishlist1",['stat'=>"Successfully Approved!",'apps'=>$apps]);  
    }
     public function RelishInfo()
    {
        $succ='';
        return view('RelishInfo')->with(['succ'=>$succ,'tk'=>'']);
    }
     public function allRelish()
    {
        $relishtab=DB::table('relishtab')->get();
        return view("pages.allrelish",['relishtab'=>$relishtab]);
    }

public function rhome()
    {
        $relishtab=DB::table('relishtab')->paginate(2);
        $relishtab=DB::table('relishtab')->get();
        return view('RelishInfo')->with('relishtab',$relishtab);
    }
}


