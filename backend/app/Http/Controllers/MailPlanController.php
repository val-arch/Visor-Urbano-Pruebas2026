<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\Planes;
use Illuminate\Support\Facades\Mail;

class MailPlanController extends Controller{

    public function index()
  {
    $mails = [

    ];
      try {
        foreach ($mails as $email) {
          if($email != ''){
            $d =  Mail::to($email)->send(new Planes(1));
              if( count(Mail::failures()) > 0 ) {
                echo "There was one or more failures. They were: <br />";
                foreach(Mail::failures() as $email_address) {
                    echo " - $email_address <br />";
                }

            } else {
                echo "No errors, all sent successfully!";
            }
            sleep(rand(2,6));
        }
        }
      } catch (\Throwable $th) {
          //return $th;
          //return Mail::failures();
      }
  }

}
