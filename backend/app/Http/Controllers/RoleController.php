<?php
namespace App\Http\Controllers;

use App\DTOs\Roles\RoleCreateDto;
use App\DTOs\Roles\RoleUpdateDto;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Repositories\Interfaces\ILogs;
use App\Repositories\Interfaces\IRoleRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Traits\ApiResponser;
use App\Traits\LogHistorico;


use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{

    use ApiResponser;
    use LogHistorico;

    private IRoleRepository $roleRepository;

    public function __construct(IRoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;

    }
    public function index($value = null, Request $request)
    {
        if($value!=null){
            return $this->roleRepository->paginate2(30, $value);
        }else{
            return $this->roleRepository->paginate(30);
        }
    }
    public function indexRole($value=null)
    {

        $currentUser = Auth::user();

        if(!isset($currentUser->id) || $currentUser->id == null  ){
            $this->historial($accion='Error en la session','','',$tipo=4,0);
            return $this->errorResponse('Error en la session', 404);
        }

        if($value != null){
            return $this->roleRepository->paginaterole(20,$currentUser->id,$currentUser->id_municipio, $value);
        }else{
            return $this->roleRepository->paginaterole(20,$currentUser->id,$currentUser->id_municipio, 0);
        }



    }
    public function indexRole2()
    {
        
        $currentUser = Auth::user();
     
        if(!isset($currentUser->id) || $currentUser->id == null  ){
            $this->historial($accion='Error en la session','','',$tipo=4,0);
            return $this->errorResponse('Error en la session', 404);      
        }
        return $this->roleRepository->paginaterole(10,$currentUser->id,$currentUser->id_municipio);
       
    }
    public function show(int $id)
    {
        $result = $this->roleRepository->find($id);
        if($result) {
            return $this->successResponse(
                $result,202
            );
        }
        $this->historial($accion='Error al Mostrar datos de roles','','',$tipo=4,0);
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
        $this->historial($accion='Se registro un nuevo rol',$valor_anterior,$data2,$tipo=2,0);
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
        $this->historial($accion='Error al guardar el role',$valor_anterior,$data2,$tipo=4,0);
        return $this->errorResponse('Error Al Guardar El Rol intentar más tarde', 409);

    }
    public function update($id,Request $request)
    {
          // Validation
          $this->validate($request, [
            'name' => [
                'required',
                Rule::unique('roles')->ignore($id)
            ],
            'name' => 'required',
        ]);

        // Mapping
        $data       = $request->all();
        $data['id'] = $int = (int)$id;;
        $entry      = new RoleUpdateDto($data);

      ;
        $usuariosData   = Role::where('id',  $data['id'])->first();
        $valor_anterior = json_encode($usuariosData->getAttributes());
        $data2          = json_encode($request->except(['token']));
        $this->historial($accion='Se registro un nuevo role',$valor_anterior,$data2,$tipo=2,0);
        // Update
        $this->roleRepository->update($entry);

        if($entry) {
            return $this->successResponse(
                $entry,202
            );
        }
        $this->historial($accion='Error al guardar el role',$valor_anterior,$data2,$tipo=4,0);
        return $this->errorResponse('Error Al Actualizar El Role', 409);

    }

    public function destroy($id)
    {
        $this->roleRepository->destroy($id);
        return $this->successResponse(
            'Eliminado Correctamente',202
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
