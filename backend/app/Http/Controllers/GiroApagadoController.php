<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DTOs\GirosApagados\GirosApagadosCreateDto;
use App\Models\GirosConfiguracion;
use App\Models\GirosImpacto;
use App\Repositories\Interfaces\IGirosApagadosRepository;
use App\Repositories\Interfaces\ILogs;
use App\Traits\ApiResponser;
use App\Traits\LogHistorico;
use App\User;
use Illuminate\Support\Facades\Auth;

class GiroApagadoController extends Controller
{
    use ApiResponser;
    use LogHistorico;
    private IGirosApagadosRepository $giroApagadoRepository;
   

    public function __construct(IGirosApagadosRepository $giroApagadoRepository)
    {
        $this->giroApagadoRepository = $giroApagadoRepository;
      
    }
    public function index()
    {
      
    }
    public function store(Request $request)
    {
        // Validation
        $this->validate($request, [
            'giros_id'      => 'required',
            'municipios_id' => 'required'
        ]);
        // Mapping
        $store  = new GirosApagadosCreateDto($request->all());
        // Creation
     
        $valor_anterior = '';
        $data2          = json_encode($request->except(['token']));  

        //Actualizacon de datos
        $this->historial($accion='Se Apago un giro',$valor_anterior,$data2,$tipo=3,0); 
        $result = $this->giroApagadoRepository->store($store);
        return $this->successResponse(
            'Guardado Correctamente',202
        );
    }

    public function storeStatusCedula(Request $request,$status)
    {
        // Validation
        $this->validate($request, [
            'giros_id'      => 'required',
            'municipios_id' => 'required'
            
        ]);
       // $status = 0;
        if($status == 1){
            $status = 0;
        }else{
            $status = 1;
        }
        // Mapping
        $store  = new GirosApagadosCreateDto($request->all());
        // Creation

        $valor_anterior = '';
        $data2          = json_encode($request->except(['token']));  

        $this->historial($accion='Se actualizo el status de la cedula',$valor_anterior,$data2,$tipo=3,0); 
        $result = $this->giroApagadoRepository->storeStatusCedula($store,$status);
        return $this->successResponse(
            'Guardado Correctamente',202
        );
    }
    public function storeStatus(Request $request,$status)
    {
        // Validation
        $this->validate($request, [
            'giros_id'      => 'required',
            'municipios_id' => 'required'
            
        ]);
       // $status = 0;
        if($status == 1){
            $status = 0;
        }else{
            $status = 1;
        }
        // Mapping
        $store  = new GirosApagadosCreateDto($request->all());
        // Creation
     
        $valor_anterior = '';
        $data2          = json_encode($request->except(['token']));  
        $this->historial($accion='Se Actualizo el status del giro',$valor_anterior,$data2,$tipo=3,0); 
        $result = $this->giroApagadoRepository->storeStatus($store,$status);
        return $this->successResponse(
            'Guardado Correctamente',202
        );
    }
    public function destroy(int $id)
    { 
        $valor_anterior = '';
        $data2          = $id;  
        $this->giroApagadoRepository->destroy($id);
        $this->historial($accion='Se Encendio un giro',$valor_anterior,$data2,$tipo=3,0); 
   
        return $this->successResponse(
            'Eliminado Correctamente',202
        );
    }
    public function storeCedula(Request $request)
    {
        // Validation
        $this->validate($request, [
            'giro_id'      => 'required',
            'municipio_id' => 'required'
        ]);
        // Mapping
        $store  = $request->all();
        // return $this->successResponse(
        //     $store,202
        // );
        // Creation
        $data2          = json_encode($request->except(['token']));  
        $valor_anterior = '';
        $this->historial($accion='Se encendio cedula de apertura',$valor_anterior,$data2,$tipo=3,0); 
   
        $result = $this->giroApagadoRepository->storeCedula($store);
        return $this->successResponse(
            'Guardado Correctamente',202
        );
    }

    public function destroyCedula(int $id)
    {   
        $data2            = $id;  
        $valor_anterior   = '';  
        $this->historial($accion='Se Apago cedula de apertura',$valor_anterior,$data2,$tipo=3,0); 
   
        $this->giroApagadoRepository->destroyCedula($id);
        return $this->successResponse(
            'Eliminado Correctamente',202
        );
    }
    public function storeImpacto(Request $request)
    {
        // Validation
        $this->validate($request, [
            'impacto'      => 'required',
            'giro_id'      => 'required',
            'municipio_id' => 'required'
        ]);

        
        // Mapping
        $store  = $request->all();
        $data2          = json_encode($request->except(['token']));  
        $valor_anterior = '';
        $this->historial($accion='Se asigno un impacto',$valor_anterior,$data2,$tipo=3,0); 
   


        $result = $this->giroApagadoRepository->storeImpacto($store);
        return $this->successResponse(
            $result,202
        );
    }

    public function updateImpacto(Request $request)
    {
        $store  = $request->all();
        $data2          = json_encode($request->except(['token']));  
        
        $impactoData   = GirosConfiguracion::where('giros_id', $request->giro_id)->first();
        $valor_anterior = json_encode($impactoData->getAttributes());
        $this->historial($accion='Se Actualizo  un impacto',$valor_anterior,$data2,$tipo=3,0); 
   
        $d = $this->giroApagadoRepository->updateImpacto($store['municipio_id'],$store['giro_id'],$store['impacto']);
        return $this->successResponse(
            $d,202
        );
    }
    public function getGirosOn(Request $request)
    {
      
        $currentUser = Auth::user();
        if(!isset($currentUser->id_municipio) || $currentUser->id_municipio == null  ){
            return $this->errorResponse('No Cuenta Con Municipio asignado', 404);      
        }
        
        return $this->successResponse(
             $this->giroApagadoRepository->getGirosOn(20, $currentUser->id_municipio),202
        );
        
      
        
    }
    public function getGirosOnPublic(Request $request)
    {  
        $respuesta    = $request->all();
        if($respuesta==null){
            $id_municipio =0;
        }else{
            $id_municipio = $respuesta['municipio_id'];
        }        
        $currentUser = Auth::user();
          return $this->successResponse(
            $this->giroApagadoRepository->getGirosPublic(20,$id_municipio ),202
        );
 
    }

    public function getGirosAllMapa(Request $request)
    {
        $order     ='';
        $respuesta = $request->all();

        $id_municipio = $respuesta['municipio_id'];

        if(isset($respuesta['oder'])){
            $order = $respuesta['oder'];
        }

        if($id_municipio == null  ){
            return $this->errorResponse('No Cuenta Con Municipio asignado', 404);      
        }

        return $this->giroApagadoRepository->getGirosallMapa(50000, $id_municipio, $order);    
    }


    public function getGirosAdmin(){
        $currentUser = Auth::user();
        if(!isset($currentUser->id_municipio) || $currentUser->id_municipio == null  ){
            return $this->errorResponse('No Cuenta Con Municipio asignado', 404);      
        }
        return $this->successResponse(
            $this->giroApagadoRepository->getGirosPublic(20,$currentUser->id_municipio ),202
        );
    }
    public function getGirosAll(Request $request)
    {
        $order     ='';
        $filtro    ='';
        $respuesta = $request->all();

        if(isset($respuesta['oder'])){
            $order = $respuesta['oder'];
        }
        if(isset($respuesta['filter'])){
            $filtro = $respuesta['filter'];
        }
        $currentUser = Auth::user();
        if(!isset($currentUser->id_municipio) || $currentUser->id_municipio == null  ){
            return $this->errorResponse('No Cuenta Con Municipio asignado', 404);      
        }

        return $this->giroApagadoRepository->getGirosall(15, $currentUser->id_municipio,$order,$filtro);    
    }
    public function getGirosOff(Request $request)
    {
        $currentUser = Auth::user();
        if(!isset($currentUser->id_municipio) || $currentUser->id_municipio == null  ){
            return $this->errorResponse('No Cuenta Con Municipio asignado', 404);      
        }
        $datos =$request->all();
        return $this->giroApagadoRepository->getGirosOff(20, $currentUser->id_municipio);
    }


}
