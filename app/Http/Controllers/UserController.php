<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Hash;
use Auth;
class UserController extends Controller
{

    public function index()
    {
    	$users=DB::table('users')->get();
    	return view("users.users",['users'=>$users]);
    }
    public function createForm()
    {            
        return view("users.create")->with(['succ'=>'']);;
    }

    public function create(Request $req)
    {

	$req->validate(['name'=>'required|unique:users','password'=>'required|min:6','confirm-password'=>'required|same:password']); 

        $fname=$req->input('fullname');
    	$uname=$req->input('name');
		$pwd=$req->input('password');
        $urole=$req->input('role');
        $email=$req->input('email');
        
		$data=array('name'=>$uname,'password'=>Hash::make($pwd),'email'=>$email,'roleid'=>$urole,'fullname'=>$fname);
		DB::table('users')->insert($data);
        return view("users.create")->with(['succ'=>"You have Created an Account Successfully!!!"]); 
    }
	public function editUser($id)
    {
 	$user=DB::table('users')->where('id',$id)->get();
    return view("users.editUser",['user'=>$user[0]]);
        
    }
	public function reset()
    {
        $status='';
        $rid=0;
        if(Auth::user()!=null){
            $rid=Auth::user()->roleid;
        }
        //where('roleid',$rid)->
        $users=DB::table('users')->get();
        return view("users.reset",['users'=>$users,'status'=>$status]);
    }

public function savereset(Request $req){
 	$req->validate(['password'=>'required|min:6','confirm-password'=>'required|same:password']);
 	$pwd=$req->input('password');
 	$uid=$req->input('uid');
    DB::table('users')
            ->where('id', $uid)
            ->update(['password'=>Hash::make($pwd)]);
             $users=DB::table('users')->get();
    return view("users.reset",['users'=>$users,'status'=>'Your Password is Changed!!!']);
}
public function saveEdit(Request $req)
    {
	$selectusr=DB::table('users')->where('name',$req->input('oldname'))->get();
    $uid=$selectusr[0]->id;

        $fname=$req->input('fullname');
        $urole=$req->input('urole');

		if ($fname!="") {
			DB::table('users')
		            ->where('id', $uid)
		            ->update(['fullname'=>$fname]);
		}

		if ($urole!="0") {
			DB::table('users')
		            ->where('id', $uid)
		            ->update(['roleid'=>$urole]);
		}
		$users=DB::table('users')->get();
    	return view("users.users",['users'=>$users]);	
    }

    public function login(Request $req)
    {
    	$req->validate(['loginname'=>'required','password'=>'required']);        

        $uname=$req->input('loginname');
        $pwd=$req->input('password');   
        
        $data=array('name'=>$uname,'password'=>$pwd);
        if(Auth::attempt($data)){
            $rid=Auth::user()->roleid;
            $users=DB::table('users')->where('roleid',$rid)->get();
            
    $id=Auth::user()->id;
   // $apps=DB::table('appointmentts')->get();
   // $assignedapps=DB::table('appointmentts')->where('status','ተመድቧል')->get();
    //$newapps=DB::table('appointmentts')->where('status','new')->get();
    //$appstoyou=DB::table('assigneds')->where('uid',$id)->get();
    //$male=DB::table('appointmentts')->where('sex','ወንድ')->get();
    //$femal=DB::table('appointmentts')->where('sex','ሴት')->get();
    return view("pages.dashboard");    
     //return view("pages.dashboard",['apps'=>count($apps),'assigned'=>count($assignedapps),'new'=>count($newapps),'toyou'=>count($appstoyou),'male'=>count($male),'female'=>count($femal)]);      
        }
        else{
            return view("login")->with(['succ'=>"The Username or Password is not Correct."]);  
        }    
    }
    public function logout()
    {
        Auth::logout();
        return view("login");
    }
   // public function dashboard()
   // {
   // $id=Auth::user()->id;
   // $apps=DB::table('appointmentts')->get();
   // $assignedapps=DB::table('appointmentts')->where('status','ተመድቧል')->get();
   // $newapps=DB::table('appointmentts')->where('status','new')->get();
   // $appstoyou=DB::table('assigneds')->where('uid',$id)->get();  
   // $male=DB::table('appointmentts')->where('sex','ወንድ')->get();
   // $femal=DB::table('appointmentts')->where('sex','ሴት')->get();
   //  return view("pages.dashboard",['apps'=>count($apps),'assigned'=>count($assignedapps),'new'=>count($newapps),'toyou'=>count($appstoyou),'male'=>count($male),'female'=>count($femal)]);
   // }
}
