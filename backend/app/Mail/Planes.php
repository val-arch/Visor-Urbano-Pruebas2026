<?php
namespace App\Mail;
 
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
 
class Planes extends Mailable {
 
    use Queueable,
        SerializesModels;
 
  
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($i)
    {
        $this->folio = $i;
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
        return $this->markdown('emails.fin')
        ->subject("Visor Urbano: Un recuento del 2021")
        // ->from('jcorrea@visorurbano.com','Visor Urbano Jalisco')
        ->replyTo('jcorrea@visorurbano.com', 'Christian Correa')->bcc('sergio@visorurbano.com', 'Sergio');
        // return $this->view('emails.notificacion');    
    }
}
