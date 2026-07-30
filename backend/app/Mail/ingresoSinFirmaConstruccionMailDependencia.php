<?php
namespace App\Mail;
 
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
 
class ingresoSinFirmaConstruccionMailDependencia extends Mailable {
 
    use Queueable,
        SerializesModels;
    use Queueable, SerializesModels;
    public $nombre_tramite;
    public $folio_requisitos;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($nombre_tramite, $folio_requisitos)
    {
        $this->nombre_tramite = $nombre_tramite;
        $this->folio_requisitos = $folio_requisitos;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        return $this->markdown('emails.ingresoSinFirmaConstruccionMailDependencia')->subject("Notificaciones");
               
    }
}