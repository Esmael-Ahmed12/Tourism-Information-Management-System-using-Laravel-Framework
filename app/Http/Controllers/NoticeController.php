<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class NoticeController extends Controller
{
    public function index()
    {
        return view("pages.notices")->with(['suc'=>'']);     
    }
    public function saveNotice(Request $req)
    {
        $req->validate(['title'=>'required','type'=>'required','description'=>'required']); 
       $filepath='../logo.jpg';
      if ($req->hasFile('thumb'))  {
        $filename=time().'.'.$req->file('thumb')->getClientOriginalExtension();
        $file=$req->file('thumb');
        $file->move(public_path('/notices/'),$filename);
        $filepath='/notices/'.$filename;
         }
        $day=date('d');
        $month= date('m');
        $year= date('Y');
        $title=$req->input('title');
        $type=$req->input('type');
        $body=$req->input('description');
        $data=array('title'=>$title,'body'=>$body,'type'=>$type,'thumbnail'=>$filepath,'day'=>$day,'month'=>$month,'year'=>$year);
        DB::table('notices')->insert($data);
        return view("pages.notices")->with(['suc'=>"The New/Notice is Successfully Saved!"]); 
    }

    public function allNotice()
    {
        $notices=DB::table('notices')->get();
        return view("pages.allnotices",['notices'=>$notices]);
    }

    public function home()
    {
        $notices=DB::table('notices')->paginate(2);
        $notices=DB::table('notices')->get();
        return view('welcome')->with('notices',$notices);
    }
    public function home2()
    {
        $notices=DB::table('notices')->get();
        return view('result')->with('notices',$notices);
    }
    public function home3()
    {
        $notices=DB::table('notices')->get();
        return view('result1')->with('notices',$notices);
    }

    public function show(Request $req)
    {
        $keyword=$req->input('searchkey');

        $apps=DB::table('appointmentts')
        ->select('appointmentts.*','appresults.*')
        ->join('appresults','appresults.appid','=','appointmentts.id')
        ->where(['appointmentts.casenum' => $keyword])
        ->get();
        //dd($apps);
        $index=count($apps)-1;
        return view('pages.search')->with(['apps'=>$apps, "index"=>$index]);
    }

    public function appDay()
    {
        $notices=DB::table('notices')->get();
        return view('appday')->with('notices',$notices);
    }
    public function appDay1()
    {
        $notices=DB::table('notices')->get();
        return view('appday1')->with('notices',$notices);
    }
    public function showAppDay(Request $req)
    {
        $keyword=$req->input('searchkey');

        $apps1='';
        $apps=DB::table('appointmentts')
        ->select('appointmentts.*','assigneds.*','users.fullname')
        ->join('assigneds','assigneds.caseid','=','appointmentts.id')
        ->join('users','users.id','=','assigneds.uid')
        ->where(['appointmentts.status' => 'assigned','appointmentts.casenum' => $keyword])
        ->get();
        if (count($apps)<1) {
            $apps1=DB::table('appointmentts')
            ->where(['appointmentts.casenum' => $keyword])
            ->get();
        }
        else{
             $apps1=DB::table('appointmentts')
        ->select('appointmentts.*','assigneds.*','users.fullname')
        ->join('assigneds','assigneds.caseid','=','appointmentts.id')
        ->join('users','users.id','=','assigneds.uid')
        ->where(['appointmentts.status' => 'assigned','appointmentts.casenum' => $keyword])
        ->get();
        }

        return view('pages.appdate')->with(['apps'=>$apps,'notAssigned'=>$apps1]);
    }

    public function feedback()
    {
        $succ='';
        return view('feedback')->with(['succ'=>$succ,'tk'=>'']);
    }

    public function saveFeedback(Request $req)
    {
        $req->validate(['title'=>'required','fullname'=>'required','description'=>'required']); 

        $fname=$req->input('fullname');
        $title=$req->input('title');
        $mobile=$req->input('mobile');
        $body=$req->input('description');
        
        $data=array('fullname'=>$fname,'title'=>$title,'body'=>$body,'mobile'=>$mobile);
        DB::table('feedback')->insert($data);
        return view("feedback")->with(['succ'=>"Your Feedback is Successfully Sent!",'tk'=>"Thank You!"]); 
    }
    public function allFeedback()
    {
        $feedback=DB::table('feedback')->get();
        return view('pages.feedback')->with('feedbacks',$feedback);
    }
 
    public function readMore($id)
    {
        $notices=DB::table('notices')->where('id',$id)->get();
        return view("readmore",['notice'=>$notices[0]]);
    }
    public function show11(Request $req)
    {
        $keyword=$req->input('searchkey');

        $apps=DB::table('complain')
        ->select('complain.*','appresults1.*')
        ->join('appresults1','appresults1.appid','=','complain.id')
        ->where(['complain.casenum' => $keyword])
        ->get();
        //dd($apps);

        return view('pages.search1')->with('apps',$apps);
    }
    public function showAppDay1(Request $req)
    {
        $keyword=$req->input('searchkey');

        $apps1='';
        $apps=DB::table('complain')
        ->select('complain.*','assigneds1.*','users.fullname')
        ->join('assigneds1','assigneds1.caseid','=','complain.id')
        ->join('users','users.id','=','assigneds1.uid')
        ->where(['complain.status' => 'assigned','complain.casenum' => $keyword])
        ->get();
        if (count($apps)<1) {
            $apps1=DB::table('complain')
            ->where(['complain.casenum' => $keyword])
            ->get();
        }
        else{
             $apps1=DB::table('complain')
        ->select('complain.*','assigneds1.*','users.fullname')
        ->join('assigneds1','assigneds1.caseid','=','complain.id')
        ->join('users','users.id','=','assigneds1.uid')
        ->where(['complain.status' => 'assigned','complain.casenum' => $keyword])
        ->get();
        }

        return view('pages.appdate1')->with(['apps'=>$apps,'notAssigned'=>$apps1]);
    }
    public function saveFeedback1(Request $req)
    {
        $req->validate(['title'=>'required','fullname'=>'required','description'=>'required']); 

        $fname=$req->input('fullname');
        $title=$req->input('title');
        $mobile=$req->input('mobile');
        $body=$req->input('description');
        
        $data=array('fullname'=>$fname,'title'=>$title,'body'=>$body,'mobile'=>$mobile);
        DB::table('feedback')->insert($data);
        return view("feedback1")->with(['succ'=>"Your Feedback is Successfully Sent!",'tk'=>"Thank You for Your Feedback!"]); 
    }  
}

