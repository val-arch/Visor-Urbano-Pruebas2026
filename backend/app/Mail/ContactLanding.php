<?php
namespace App\Mail;
 
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
 
class ContactLanding extends Mailable {
 
    use Queueable,
        SerializesModels;
    use Queueable, SerializesModels;
    public $nombre;
    public $funcionario;
    public $cargo;
    public $dependencia;
    public $mail;
    public $telefono;
    public $ciudad;
    public $estado;
    public $pais;
    public $mensaje;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($nombre, $funcionario, $cargo, $dependencia, $mail, $telefono, $ciudad, $estado, $pais, $mensaje)
    {
        $this->nombre    = $nombre;
        $this->funcionario = $funcionario;
        $this->cargo = $cargo;
        $this->dependencia = $dependencia;
        $this->mail = $mail;
        $this->telefono = $telefono;
        $this->ciudad = $ciudad;
        $this->estado = $estado;
        $this->pais = $pais;
        $this->mensaje = $mensaje;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        return $this->markdown('emails.contactLanding')->subject("Notificaciones");
               
    }
}