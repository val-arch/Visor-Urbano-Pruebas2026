<?php
namespace App\Http\Controllers;

use App\DTOs\CamposRoles\CampoCreateDto;
use App\DTOs\CamposRoles\CampoUpdateDto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Requisito;
use App\Repositories\Interfaces\ICampoRepository;
use App\Repositories\Interfaces\ILogs;
use App\Traits\ApiResponser;
use App\Traits\LogHistorico;
use App\User;
use Illuminate\Support\Facades\Auth;

class CampoController extends Controller
{
    use ApiResponser;
    use LogHistorico;
    private ICampoRepository $campoRepository;
  
    public function __construct(ICampoRepository $campoRepository)
    {
        $this->CampoRepository = $campoRepository;
     
    }
    public function index(Request $request)
    {
      
        $currentUser = Auth::user();
        if(!isset($currentUser->id_municipio) || $currentUser->id_municipio == null  ){
            return $this->errorResponse('No Cuenta Con Municipio asignado', 403);      
        }
        return $this->CampoRepository->paginate($request->page,$currentUser->id_municipio,$request->filter);
    }

        //get Campos para el tramite
        public function getCamposRefrendos($folio) 
        {
          
            $currentUser = Auth::user();
          /*  $userRole = $currentUser->roles[0]->id;
            if(!isset($currentUser->id_municipio) || $currentUser->id_municipio == null  ){
                return $this->errorResponse('No Cuenta Con Municipio asignado', 404);      
            }*/
            return $this->CampoRepository->getCamposRefrendos($currentUser->id_municipio,$folio);
        }
    
  
  
    public function getCampos($folio) 
    {    
        $currentUser = Auth::user();
        return $this->CampoRepository->getCampos($currentUser->id_municipio,$folio);
    }

    public function indexCampos()
    {
        $currentUser = Auth::user();

        if(!isset($currentUser->id_municipio) || $currentUser->id_municipio == null  ){
            return $this->errorResponse('No Cuenta Con Municipio asignado', 404);      
        }
        return $this->CampoRepository->paginate(20,$currentUser->id_municipio);
    }


    public function show(int $id)
    {
        $result = $this->CampoRepository->find($id);

        if($result) {
            return $this->successResponse(
                $result,204
            );
        }
        return $this->errorResponse('Producto No Encotrado', 404);      
    }
    public function store(Request $request)
    {
        $currentUser           = Auth::user();
        $data                  = $request->all();
        if(!isset($currentUser->id_municipio) || $currentUser->id_municipio == null  ){
            return $this->errorResponse('No Cuenta Con Municipio asignado', 404);      
        }
        $data['id_municipio']  = $currentUser->id_municipio;
        $data['status']        = 1;
        $this->validate($request, [
            'name'            => 'required',
            'type'            => 'required',
            'description'     => 'required',
             //'fundamento'      => 'required',
            'step'            => 'required',
            'secuencia'       => 'required',
            'requerido'       => 'required'
        ]);
        // Mapping
        $store          = new CampoCreateDto($data);
        
        $data2          = json_encode($request->except(['token','condicion_visible']));   
        $valor_anterior = '';
        //tipo 2 registro nuevo

        $this->historial($accion='Se agrego un requisito nuevo',$valor_anterior,$data2,$tipo=2,0);
        // Creation
        $result = $this->CampoRepository->store($store);
        return $this->successResponse(
            'Guardado Correctamente',202
        );
    }

    public function update(int $id, Request $request)
    {
        // Mapping
        $data        = $request->all();
        $data['id']  = $id;
        $entry       = new CampoUpdateDto($data);
        $result      = $this->CampoRepository->update($entry);
     
        $data2          = json_encode($request->except(['token']));   
	$usuariosData   = Requisito::where('id', $request->id)->first();
        //$valor_anterior = json_encode($usuariosData->toArray());
        //Actualizacion de datos
	if ($usuariosData) {
    		$valor_anterior = json_encode($usuariosData->getAttributes());
	} else {
    // Maneja la situación, por ejemplo, enviando un mensaje de error o un JSON vacío
    		$valor_anterior = json_encode([]);
	}
	
	$this->historial($accion='Se Actualizo un requisito ',$valor_anterior,$data2,$tipo=3,0);
      
        return $this->successResponse(
            'Actualizado Correctamente',202
        );
    }

    public function camposRequisitos($id){
        $result = $this->CampoRepository->camposRequisitos($id);
        if($result) {
            return $this->successResponse(
                $result,202
            );
        }
        return $this->errorResponse('Campo No Encotrado', 404);      
    }

    public function camposRequisitosFile($id_municipio){
        /*$currentUser = Auth::user();
        if(!isset($currentUser->id_municipio) || $currentUser->id_municipio == null  ){
            return $this->errorResponse('No Cuenta Con Municipio asignado', 404);      
        } */

        $result = $this->CampoRepository->camposRequisitosFile($id_municipio);
        if($result) {  
            return $this->successResponse(
                $result,202
            );
        }
        return $this->errorResponse('Campo No Encotrado', 404);      
    }

    public function camposRequisitosTramite($id_municipio){

        $result = $this->CampoRepository->camposRequisitoTramite($id_municipio);
        if($result) {   
            return $this->successResponse(
                $result,202
            );
        }
        return $this->errorResponse('Campo No Encotrado', 404);      
    }
    public function camposRequisitosTramiteConstruccion($id_municipio){

        $result = $this->CampoRepository->camposRequisitoTramiteConstruccion($id_municipio);
        if($result) {   
            return $this->successResponse(
                $result,202
            );
        }
        return $this->errorResponse('Campo No Encotrado', 404);      
    }

    public function camposRequisitosTramiteConstruccion2($id_municipio, $id){

        $result = $this->CampoRepository->camposRequisitoTramiteConstruccion2($id_municipio, $id);
        if($result) {   
            return $this->successResponse(
                $result,202
            );
        }
        return $this->errorResponse('Campo No Encotrado', 404);      
    }

    public function camposRequisitosTramiteConstruccion3($id_municipio, $id){

        $result = $this->CampoRepository->camposRequisitoTramiteConstruccion3($id_municipio, $id);
        if($result) {   
            return $this->successResponse(
                $result,202
            );
        }
        return $this->errorResponse('Campo No Encotrado', 404);      
    }

    public function destroy(int $id)
    {
        $this->CampoRepository->destroy($id);
        
        return $this->successResponse(
            'Eliminado Correctamente',202
        );
    }

    public function  agregarRequisito(Request $request){
        
        $data = $request->all();
        $currentUser           = Auth::user();
        $data['id_municipio']  = $currentUser->id_municipio;

        $data2          = json_encode($request->except(['token']));   
        $valor_anterior = '';
        //Actualizacion de datos
        $this->historial($accion='Se cambio estatus requisito ',$valor_anterior,$data2,$tipo=3,0);

        $result = $this->CampoRepository->agregarRequisito($data['id_municipio'],$data['id_campo'],$data['id_requisito']);
        return $this->successResponse(
            'Guardado Correctamente',202
        );
    }
}
