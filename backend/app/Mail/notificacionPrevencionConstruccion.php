<?php
namespace App\Mail;
 
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
 
class notificacionPrevencionConstruccion extends Mailable {
 
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
    }

    /**
     * Build the message.
     *
     * @return $this
     */

    public function build(){
        return $this->markdown('emails.notificacionPrevencionConstruccion')->subject("Notificaciones");
; 
        // return $this->view('emails.notificacion');    
    }
}
