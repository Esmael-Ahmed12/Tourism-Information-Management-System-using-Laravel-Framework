<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use DateTime;
use Illuminate\Support\Facades\Input;
use Image;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;
use Charts;
/*use Yajra\Datatables\Facades\Datatables;
*/
class ReportController extends Controller
{

public function report(){
	
	$hh=DB::table('Heritagetab')->paginate(10);
            $mnth=0;
	$mtotal=DB::table('Heritagetab')->where('Heritagetab.month',$mnth)->get();
	$total=count($mtotal);
	
	return view("pages.report",['hh'=>$hh,'mtotal'=>$mtotal,'mnth'=>$mnth,'syr'=>0]);
}
}
