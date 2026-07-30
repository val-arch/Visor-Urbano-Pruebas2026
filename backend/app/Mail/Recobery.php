<?php
namespace App\Mail;
 
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
 
class Recobery extends Mailable {
 
    use Queueable,
        SerializesModels;
 
  
    use Queueable, SerializesModels;
    public $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
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
        return $this->markdown('emails.recobery')->subject("Notificaciones")->with([
            'token' => $this->token
        ]);        
    }
}
