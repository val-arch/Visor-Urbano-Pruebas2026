<?php
namespace App\Mail;
 
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
 
class ingresoSinFirmaGirosMailDependencia extends Mailable {
 
    use Queueable,
        SerializesModels;
    use Queueable, SerializesModels;
    public $folio_requisitos;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($folio_requisitos)
    {
        $this->folio_requisitos = $folio_requisitos;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        return $this->markdown('emails.ingresoSinFirmaGirosMailDependencia')->subject("Notificaciones");
               
    }
}