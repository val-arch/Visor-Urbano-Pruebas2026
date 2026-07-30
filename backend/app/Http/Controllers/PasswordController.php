<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\User;
use App\Repositories\Interfaces\ILogs;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Traits\ApiResponser;
use App\Traits\LogHistorico;
use App\Mail\Recobery;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PasswordController extends Controller {
    
  use LogHistorico;
  use ApiResponser;
  
   
  public function sendMail($email){
      $token = $this->generateToken($email);

      Mail::to($email)->send(new Recobery($token));
  }

  public function validEmail($email) {
    return !!User::where('email', $email)->first();
  }


    
  
    public function sendPasswordResetEmail(Request $request){
        // If email does not exist
        $data2                = json_encode($request->except(['token']));  
        if(!$this->validEmail($request->email)) {
            $this->historial('Se intento recueprar  la contraseña','', 0,1,0, 4,$data2);  
            return response()->json([
                'message' => 'Email does not exist.'
            ], Response::HTTP_NOT_FOUND);
        } else {
            // If email exists
            $this->sendMail($request->email);
            $this->historial('Se recupero la contraseña','', 0,1,0, 4,$data2);  
            return response()->json([
                'message' => 'Check your inbox, we have sent a link to reset email.'
            ], Response::HTTP_OK);            
        }
      }

  public function generateToken($email){
    $isOtherToken = DB::table('recover_password')->where('email', $email)->first();

    if($isOtherToken) {
      return $isOtherToken->token;
    }

    $token = Str::random(80);;
    $this->storeToken($token, $email);
    return $token;
  }

  public function storeToken($token, $email){
      DB::table('recover_password')->insert([
          'email' => $email,
          'token' => $token,
          'created_at' => Carbon::now()            
      ]);
  }


    public function passwordResetProcess(Request $request){
     
      if($this->updatePasswordRow($request)->count() > 0){
           return $this->resetPassword($request);
      }elseif (!DB::table('recover_password')->where('email', '=', $request->email)->exists() && DB::table('recover_password')->where('token', '=', $request->passwordToken)->exists()) { 
            return $this->errorResponse('Correo electronico no encontrado', 404);
          
     }elseif(!DB::table('recover_password')->where('token', '=', $request->token)->exists()){
        return $this->errorResponse('Token Invalido', 406); 
      } 

  }

    // Verify if token is valid
    private function updatePasswordRow(Request $request){
       return DB::table('recover_password')->where([
           'email' => $request->email,
           'token' => $request->passwordToken
       ]);
    }

    // Token not found response
    private function tokenNotFoundError() {
        return response()->json([
          'error' => 'Either your email or token is wrong.'
        ],Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    // Reset password
    private function resetPassword(Request $request) {
        // find email

       $userData = User::where('email', $request->email)->first();
        // update password
        $userData->password = Hash::make($request->password);
        $userData->update();
      
        
        // remove verification data from db
        $this->updatePasswordRow($request)->delete();

        // reset password response
        return response()->json([
          'data'=>'Password has been updated.'
        ],Response::HTTP_CREATED);
    } 
    


    
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
