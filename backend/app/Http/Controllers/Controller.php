<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactLanding;
use App\Traits\ApiResponser;

class Controller extends BaseController
{
    //
    use ApiResponser;

    public function contactFormLanding(Request $request){
        
        $data = $request->all();

        $this->sendMailContactoLanding($data['data']['nombre'], $data['data']['funcionario'], $data['data']['cargo'], $data['data']['dependencia'], $data['data']['mail'], $data['data']['telefono'],  $data['data']['ciudad'],  $data['data']['estado'], $data['data']['pais'], $data['data']['mensaje']);

        return $this->successResponse(200);
    }

    public function sendMailContactoLanding($nombre, $funcionario, $cargo, $dependencia, $mail, $telefono, $ciudad, $estado, $pais, $mensaje){
        Mail::to('daniel@visorurbano.com')->send(new ContactLanding($nombre, $funcionario, $cargo, $dependencia, $mail, $telefono, $ciudad, $estado, $pais, $mensaje));
    }
}
