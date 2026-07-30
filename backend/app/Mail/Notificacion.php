<?php
namespace App\Mail;
 
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
 
class Notificacion extends Mailable {
 
    use Queueable,
        SerializesModels;
 
  
    use Queueable, SerializesModels;
    public $folio;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($folio)
    {
        $this->folio = $folio;
        //$this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
  

    /*public function build() {
        return $this->markdown('my-email');
    }*/

    public function build(){
        return $this->markdown('emails.notificacion')->subject("Notificaciones")->with([
            'folio' => $this->folio
        ]);
; 
        // return $this->view('emails.notificacion');    
    }
}
