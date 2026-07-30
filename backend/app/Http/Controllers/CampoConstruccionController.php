<?php
namespace App\Http\Controllers;

use App\DTOs\CamposRolesConstruccion\CampoCreateDto;
use App\DTOs\CamposRolesConstruccion\CampoUpdateDto;
use App\DTOs\CamposRolesConstruccion\CampoUpdateDto2;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Requisito;
use App\Models\TramiteConstruccion;
use App\Repositories\Interfaces\ICampoConstruccionRepository;
use App\Repositories\Interfaces\ILogs;
use App\Traits\ApiResponser;
use App\Traits\LogHistoricoConstruccion;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Models\CampoConstruccion;

class CampoConstruccionController extends Controller
{
    use ApiResponser;
    use LogHistoricoConstruccion;
    private ICampoConstruccionRepository $campoRepository;
  
    public function __construct(ICampoConstruccionRepository $campoRepository)
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
  
    public function getCampos($folio) 
    {    
        $currentUser = Auth::user();
        return $this->CampoRepository->getCampos($currentUser->id_municipio,$folio);
    }

    public function actualizarNombreInteresado(Request $request, $folio){
        $folio = base64_decode($folio);
        $data        = $request->all();
        TramiteConstruccion::where('folio', $folio)->update(['nombre_solicitante_oficial' => $data['name']]);
    }

    public function actualizarDireccion(Request $request, $folio){
        $folio = base64_decode($folio);
        $data        = $request->all();
        TramiteConstruccion::where('folio', $folio)->update(['direccion_tramite' => $data['direccion']]);
    }

    public function getCampos2($folio) 
    {    
        $currentUser = Auth::user();
        return $this->CampoRepository->getCampos2($currentUser->id_municipio,$folio);
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
        if(!isset($currentUser->id_municipio) || $currentUser->id_municipio == null  ){
            return $this->errorResponse('No Cuenta Con Municipio asignado', 404);      
        }

        if($request['type'] == 'boolean'){
            $request['requerido'] = 1;
            $request['tipo_tramite'] = 'consulta_requisitos';
            $request['step'] = 1;

        }
        $this->validate($request, [
            'name'            => 'required',
            'type'            => 'required',
            'description'     => 'required',
                 //'fundamento'      => 'required',
            'step'            => 'required',
            'secuencia'       => 'required',
            'requerido'       => 'required'
        ]);
        $data                  = $request->all();
        $data['id_municipio']  = $currentUser->id_municipio;
        $data['status']        = 1;
        
        // Mapping
        $store          = new CampoCreateDto($data);
        $data2          = json_encode($request->except(['token','condicion_visible']));   
        $valor_anterior = '';
        //tipo 2 registro nuevo

       // $this->historialConstruccion($accion='Se agrego un requisito nuevo',$valor_anterior,$data2,$tipo=2,0);
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
        
      
        return $this->successResponse(
            'Actualizado Correctamente',202
        );
    }

    public function update2(int $id, Request $request)
    {
        // Mapping
        $data        = $request->all();
        $data['id']  = $id;
        $entry       = new CampoUpdateDto2($data);
        $result      = $this->CampoRepository->update2($entry);
     
        $data2          = json_encode($request->except(['token']));   
        $usuariosData   = Requisito::where('id', $request->id)->first();
        $valor_anterior = json_encode($usuariosData->getAttributes());
        //Actualizacion de datos
        $this->historialConstruccion($accion='Se Actualizo un requisito ',$valor_anterior,$data2,$tipo=3,0);
      
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
        $this->historialConstruccion($accion='Se cambio estatus requisito ',$valor_anterior,$data2,$tipo=3,0);

        $result = $this->CampoRepository->agregarRequisito($data['id_municipio'],$data['id_campo'],$data['id_requisito']);
        return $this->successResponse(
            'Guardado Correctamente',202
        );
    }
}