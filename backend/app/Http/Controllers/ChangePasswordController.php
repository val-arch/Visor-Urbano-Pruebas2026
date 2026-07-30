<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use App\User;
use Illuminate\Support\Facades\Hash;
use App\Traits\ApiResponser;




class ChangePasswordController extends Controller {
    
  use ApiResponser;

   /* public function passwordResetProcess(Request $request){
       // dd($this->updatePasswordRow($request));
      return $this->updatePasswordRow($request)->count() > 0 ? $this->resetPassword($request) : $this->tokenNotFoundError();
    }*/

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
 
}
