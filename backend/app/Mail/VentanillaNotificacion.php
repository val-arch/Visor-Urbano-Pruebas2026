<?php
namespace App\Mail;
 
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
 
class VentanillaNotificacion extends Mailable {
 
    use Queueable,
        SerializesModels;
    use Queueable, SerializesModels;
    public $nombre_tramite;
    public $folio_ingreso;
    public $link;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($nombre_tramite,$folio_ingreso,$link)
    {
        $this->nombre_tramite = $nombre_tramite;
        $this->folio_ingreso = $folio_ingreso;
        $this->link = $link;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        return $this->markdown('emails.VentanillaNotificacion')->subject("Notificaciones");
               
    }
}