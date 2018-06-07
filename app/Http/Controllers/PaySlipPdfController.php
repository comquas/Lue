<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use App\User;
use App\Position;
use Carbon;
use App\Leave;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaySlipMail;

class PaySlipPdfController extends Controller
{
    public function make($id){

        $annual=0;
        $sick=0;
      $leave=Leave::where('user_id',$id)->select('type','no_of_day')->get();
          foreach ($leave as $value) {
            if($value->type == 1){
              $annual+=$value->no_of_day;
            }
            elseif($value->type == 2){
                $sick+=$value->no_of_day;
            }
          }
    	$data=["users" => User::where('id',$id)->get(),
    			"title" => "COMQUAS",
    			"now" => Carbon\Carbon::now(),
          "annual" => $annual,
          "sick" => $sick
          ];
          
                    
        $user=User::find($id);

         
         
        $pdfname=$user->name.$user->id.'.pdf';  
        $pdf = PDF::loadView('pdf.pay', $data);
        
        $pdf->save(public_path('payslip/'.$pdfname));
        
        Mail::send('payslip',array(),function($message) use ($user){


        			$message->to($user->email);
        			$message->attach(public_path('/payslip/'.$user->name.$user->id.'.pdf'));
        			$message->subject('Application for issue of Pay Slip');
        });
       


        return redirect()->back()->with('flash','Mail Sent!');
       
        
    }
}
 // Mail::raw('hello', function ($message) {
        // $message->to('hmyo8058@gmail.com');
        //  });
        //return $pdf->download('alia.pdf');