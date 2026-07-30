<?php
namespace App\Http\Controllers;

use App\DTOs\SubRoles\SubRoleCreateDto;
use App\DTOs\SubRoles\SubRoleUpdateDto;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ISubRoleRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;

class SubRoleController extends Controller
{

    use ApiResponser;
    private ISubRoleRepository $subroleRepository;

    public function __construct(ISubRoleRepository $subroleRepository)
    {
        $this->subroleRepository = $subroleRepository;
    }
    public function index()
    {
        return   
         $this->successResponse(
            $this->subroleRepository->paginate(20),202
        );
       
    }
    public function indexbyMunucipio()
    {

        $currentUser  = Auth::user();
    
        if(!isset($currentUser->id_municipio) || $currentUser->id_municipio == null  ){
            return $this->errorResponse('No Cuenta Con Municipio asignado', 404);      
        }
        $id_municipio = $currentUser->id_municipio;
       
        return  $this->successResponse(
            $this->subroleRepository->paginateMunucipio(20,$id_municipio),202
        );
       
    }
    public function show(int $id)
    {
        $result = $this->subroleRepository->find($id);
        if($result) {
             
            return $this->successResponse(
                $result,202
            );
        }
        return $this->errorResponse('Role No Encotrado', 404);        
       
    }
    public function store(Request $request)
    {
        $currentUser           = Auth::user();
        $data                  = $request->all();
        if(!isset($currentUser->id_municipio) || $currentUser->id_municipio == null  ){
            return $this->errorResponse('No Cuenta Con Municipio asignado', 404);      
        }
        $data['id_municipio']  = $currentUser->id_municipio;
         // Validation
         $this->validate($request, [
            'nombre' => 'required'
        ]);

     
        // Mapping
        $store = new SubRoleCreateDto($data);
        // Creation
        $result = $this->subroleRepository->store($store);
        return $this->successResponse(
            'Guardado Correctamente',202
        );
    }
    public function update($id,Request $request)
    {
          // Validation
          $this->validate($request, [
            'nombre' => [
                'required',
                Rule::unique('subroles')->ignore($id)
            ],
            'nombre' => 'required',
        ]);
     
        // Mapping
        $data       = $request->all();
        $data['id'] = $int = (int)$id;;
        $entry      = new SubRoleUpdateDto($data);
        // Update
        $this->subroleRepository->update($entry);
        return $this->successResponse(
            'Actualizado Correctamente',202
        );
  
    }

    public function destroy($id)
    {
        $this->subroleRepository->destroy($id);
        return $this->successResponse(
            'Eliminado Correctamente',202
        );
    }
}
