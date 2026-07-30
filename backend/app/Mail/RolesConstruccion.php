<?php
namespace App\Mail;
 
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
 
class RolesConstruccion extends Mailable {
 
    use Queueable,
        SerializesModels;
    use Queueable, SerializesModels;
    public $token;
    public $id;
    public $municipio;
    public $role_name;
    public $email;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($token,$id,$municipio,$role_name,$email)
    {
        $this->token     = $token;
        $this->id        = $id;
        $this->municipio = $municipio;
        $this->role_name = $role_name;
        $this->email     = $email;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        return $this->markdown('emails.roleConstruccion')->subject("Notificaciones");
               
    }
}