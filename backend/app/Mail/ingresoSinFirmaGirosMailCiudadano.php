<?php
namespace App\Mail;
 
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
 
class ingresoSinFirmaGirosMailCiudadano extends Mailable {
 
    use Queueable,
        SerializesModels;
    use Queueable, SerializesModels;
    public $municipio;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($municipio)
    {
        $this->municipio = $municipio;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        return $this->markdown('emails.ingresoSinFirmaGirosMailCiudadano')->subject("Notificaciones");
               
    }
}