<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class CambiarContrasenaController extends Controller{
    use ApiResponser;

    public function cambiarContrasena(Request $request){
        
        $usuarioActualId = Auth::user()->id;
        $email = Auth::user()->email;
        $hashedPassword = Auth::user()->getAuthPassword();
        $currentPassword = $request->currentPassword;
        $passwordConfirm = $request->passwordConfirm;


        if (Hash::check($currentPassword, $hashedPassword)) {
            $option = User::where('id', '=', $usuarioActualId)->first();
            $option->password = Hash::make($passwordConfirm);
            $option->save();
            // reset password response
            return $this->successResponse(
                'Cambiado Correctamente',202
            );
        }else{
            return $this->errorResponse('Su contrasena actual no es valida.', 422);
        }
      
    }



}