<?php
namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use App\Traits\LogHistorico;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interfaces\IIdentityRepository;
use App\Repositories\Exceptions\AccessDeniedException;
use App\Repositories\Interfaces\ILogs;


class IdentityController extends Controller
{
    use ApiResponser;
    use LogHistorico;
    private IIdentityRepository $identityRepository;
   
    public function __construct(IIdentityRepository $identityRepository)
    {
        $this->identityRepository = $identityRepository;
       
    }
    
    public function signin(Request $request)
    {
        $data2          = json_encode($request->except(['password']));   
        try {
            $this->historial($accion='Se registra nuevo acceso','',$data2,$tipo=1,0);
            return $this->successResponse(
                $this->identityRepository->signin(
                       $request->input('email'),
                       $request->input('password')
                )  
            );
        } catch (AccessDeniedException $ex) {
            $this->historial($accion='Intento de login','',$data2,$tipo=4,0);
            return $this->errorResponse($ex->getMessage(), 400);
       
        }
    }
   
    
    public function register(Request $request)
    {
      //  return $request;
       $data2     = json_encode($request->except(['password']));   
        try {
            $input = $request->all();
            $this->historial($accion='Se registro un nuevo usuario','',$data2,$tipo=2,0);
            return $this->successResponse(
                $this->identityRepository->store($input)
            );
        } catch (AccessDeniedException $ex) {
            $this->historial($accion='Se Intento registrar un nuevo usuario','',$data2,$tipo=4,0); 
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }
    public function test() 
    {
       // return Auth::user();
    }
}