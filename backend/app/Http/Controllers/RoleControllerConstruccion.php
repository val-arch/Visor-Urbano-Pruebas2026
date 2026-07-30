<?php
namespace App\Http\Controllers;

use App\DTOs\RolesConstruccion\RoleCreateDto;
use App\DTOs\RolesConstruccion\RoleUpdateDto;
use App\Http\Controllers\Controller;
use App\Models\RoleConstruccion;
use App\Repositories\Interfaces\ILogs;
use App\Repositories\Interfaces\IRoleConstruccionRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Traits\ApiResponser;
use App\Traits\LogHistoricoConstruccion;


use Illuminate\Support\Facades\Auth;

class RoleControllerConstruccion extends Controller
{

    use ApiResponser;
    use LogHistoricoConstruccion;
    
    private IRoleConstruccionRepository $roleRepository;
    
    public function __construct(IRoleConstruccionRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
      
    }
    public function index($value = null, Request $request)
    {
        if($value!=null){
            return $this->roleRepository->paginate2(20, $value);
        }else{
            return $this->roleRepository->paginate(20);
        }
    }

    public function index2($id_municipio)
    {
        return $this->roleRepository->getAll($id_municipio);
    }
    public function indexRole()
    {
        $currentUser = Auth::user();
     
        if(!isset($currentUser->id) || $currentUser->id == null  ){
            $this->historialConstruccion($accion='Error en la session','','',$tipo=4,0);
            return $this->errorResponse('Error en la session', 404);      
        }

     
        return $this->roleRepository->paginaterole(20,$currentUser->id,$currentUser->id_municipio);
        
        
       
    }
    public function show(int $id)
    {
        $result = $this->roleRepository->find($id);
        if($result) {
            return $this->successResponse(
                $result,202
            );
        }
        $this->historialConstruccion($accion='Error al Mostrar datos de roles','','',$tipo=4,0);
        return $this->errorResponse('Role No Encotrado', 404);        
       
    }

    public function store($value = null, Request $request)
    {
         // Validation
         $this->validate($request, [
            'name' =>  'required|max:255',
         ]);
        // Mapping
        $currentUser           = Auth::user();
        $data                  = $request->all();
        if(!isset($currentUser->id_municipio) || $currentUser->id_municipio == null  ){
            return $this->errorResponse('No Cuenta Con Municipio asignado', 404);      
        }
        $valor_anterior       = '';
        $data2                = json_encode($request->except(['token']));   
        $this->historialConstruccion($accion='Se registro un nuevo rol',$valor_anterior,$data2,$tipo=2,0);
        $data['id_municipio'] = $currentUser->id_municipio;
        $store  = new RoleCreateDto($data);
        
        // Creation
        if($value!=null){
            $result = $this->roleRepository->store($store, $value);
        }else{
            $result = $this->roleRepository->store($store, 0);
        }
        
        if($result) {       
            return $this->successResponse(
                $result,202
            );
        }else{
            return $this->errorResponse('El rol con este nombre ya existe', 409);   
        }
        $this->historialConstruccion($accion='Error al guardar el role',$valor_anterior,$data2,$tipo=4,0);
        return $this->errorResponse('Error Al Guardar El Rol intentar más tarde', 409);   
        
    }
    public function update($id,Request $request)
    {
          // Validation
          $this->validate($request, [
            'name' => [
                'required',
                Rule::unique('roles_contruccion')->ignore($id)
            ],
            'name' => 'required',
        ]);
     
        // Mapping
        $data       = $request->all();
        $data['id'] = $int = (int)$id;;
        $entry      = new RoleUpdateDto($data);

        $usuariosData   = RoleConstruccion::where('id',  $data['id'])->first();
        $valor_anterior = json_encode($usuariosData->getAttributes());
        $data2          = json_encode($request->except(['token']));   
        $this->historialConstruccion($accion='Se registro un nuevo role',$valor_anterior,$data2,$tipo=2,0);
        // Update
        $this->roleRepository->update($entry);

        if($entry) {       
            return $this->successResponse(
                $entry,202
            );
        }
        $this->historialConstruccion($accion='Error al guardar el role',$valor_anterior,$data2,$tipo=4,0);
        return $this->errorResponse('Error Al Actualizar El Role', 409);      

    }

    public function destroy($id)
    {
        $this->roleRepository->destroy($id);
        return $this->successResponse(
            'Eliminado Correctamente',202
        );
    
       
    }

    public function getUserRole(){
        $currentUser           = Auth::user();
        $id  = $currentUser->id;
        return $this->successResponse(
            $this->roleRepository->getUserRole($id),202
        );
    }

    public function getMyRoleMunicipaly(){
        $currentUser           = Auth::user();
        if(!isset($currentUser->id_municipio) || $currentUser->id_municipio == null  ){
            return $this->currentUsererrorResponse('No Cuenta Con Municipio asignado', 404);      
        }
        $id_municipio  = $currentUser->id_municipio;
        return $this->successResponse(
            $this->roleRepository->getMyRoleMunicipaly($id_municipio),202
        );
    }
}
