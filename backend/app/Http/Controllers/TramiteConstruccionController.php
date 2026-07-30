<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ITramiteConstruccionRepository;
use App\Traits\ApiResponser;
use App\Traits\LogHistoricoConstruccion;



class TramiteConstruccionController extends Controller
{
    use ApiResponser;
    use LogHistoricoConstruccion;
    private ITramiteConstruccionRepository $tramiteRepository;
   
    public function __construct(ITramiteConstruccionRepository $tramiteRepository)
    {
        $this->tramiteRepository = $tramiteRepository;
      
    }

    public function index()
    {
        return $this->tramiteRepository->paginate(20);
    }
    
    
    public function ingreso(Request $request){
        $datos =$request->all();
        $datos2 =json_encode($datos);
        $folio =  base64_decode($datos['folio']);
        $valor_anterior = $folio;
        $this->historialConstruccion($accion='Se Valido  Captura de tramite',$valor_anterior,$datos2,$tipo=2,0);
        return $this->tramiteRepository->ingreso($folio);

    }
  
    public function noFirmaElectronica(Request $request, $folio){
        $datos  = $request->all();
        $datos2 = json_encode($datos);
        $folio  = base64_decode($folio);
        $data   = $this->tramiteRepository->noFirmaElectronica($folio);     

        $valor_anterior = $folio;
        $this->historialConstruccion($accion='Finalizo Captura de tramite',$valor_anterior,$datos2,$tipo=3,0);
       
        if($data == false){
            return $this->errorResponse('No tienes permiso', 403);  
        }else{
            return $this->successResponse($data);
        }
    }
    public function ingresoTramiteContinuar(Request $request, $folio){
        $datos = $request->all();
        $folio = base64_decode($folio);
        $datos = $request->all();
        $datos2= json_encode($datos);
        $data  = $this->tramiteRepository->ingresoTramiteContinuar($folio);
        $valor_anterior =  $folio;
        $this->historialConstruccion($accion='Se envio la solicitud del tramite',$valor_anterior,$datos2,$tipo=2,0);
    
        if($data == false){
            return $this->errorResponse('No tienes permiso', 403);  
        }else if($data == "tramite_duplicado"){
            return $this->errorResponse("El trámite ya ha sido ingresado previamente, consúltalo en tu bandeja de trámites", 400);
        }else{
            return $this->successResponse($data);
        }
    }
    public function getListado(Request $request){       
         $query = $request->query();
         $folio = $query['folio'] ?? '';
        //  return $folio;
        $data = $this->tramiteRepository->getListado($folio);
        if($data == false){
            return $this->errorResponse('No tienes permiso', 403);  
        }else{
            return $this->successResponse($data);
        }
    }
    public function getListadoVentanilla(Request $request){

         $query = $request->query();
         $folio = $query['folio'] ?? '';
         
        //  return $folio;
        $data = $this->tramiteRepository->getListadoVentanilla($folio);
        if($data == false){
            return $this->errorResponse('No tienes permiso', 403);  
        }else{
            return $this->successResponse($data);
        }
    }
    public function getListadoDirRev(Request $request){       
         $query = $request->query();
         $folio = $query['folio'] ?? '';
        //  return $folio;
        $data = $this->tramiteRepository->getListadoDirRev($folio);
        if($data == false){
            return $this->errorResponse('No tienes permiso', 403);  
        }else{
            return $this->successResponse($data);
        }
    }
    public function getListadoLicenciasVisor(Request $request){
         $query = $request->query();
         $folio = $query['filter'] ?? '';
        //  return $folio;
        $data = $this->tramiteRepository->getListadoLicenciasVisor($folio);
        if($data == false){
            return $this->errorResponse('No tienes permiso', 403);  
        }else{
            return $this->successResponse($data);
        }
    }
    public function getListadoDirSolv(Request $request){

       
         $query = $request->query();
         $folio = $query['folio'] ?? '';
         
        //  return $folio;

        $data = $this->tramiteRepository->getListadoDirSolv($folio);
        
        if($data == false){
            return $this->errorResponse('No tienes permiso', 403);  
        }else{
            return $this->successResponse($data);
        }
    }

    public function show(int $id)
    {
        $result = $this->tramiteRepository->find($id);

        if($result) {
            return $result;
        }

        return $this->errorResponse('Producto No Encotrado', 404);      
    }

    public function store(Request $request)
    {
        // Validation
        $this->validate($request, [
            'sku' => 'required|unique:products',
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric|min:1'
        ]);
        // Mapping
      /*  $store = new ProductCreateDto($request->all());
        // Creation
        $result = $this->productRepository->store($store);
        return $this->successResponse(
            'Guardado Correctamente',204
        );*/
    }

    public function update(int $id, Request $request)
    {
        // Validation
        $this->validate($request, [
            'sku' => [
                'required',
                Rule::unique('products')->ignore($id)
            ],
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric|min:1'
        ]);

        // Mapping
        $data = $request->all();
        $data['id'] = $id;

    /*    $entry = new ProductUpdateDto($data);

        // Update
        $this->productRepository->update($entry);

        return $this->successResponse(
            'Actulizado Correctamente',204
        );*/
    }


    public function destroy(int $id)
    {
        $this->tramiteRepository->destroy($id);
        
        return $this->successResponse(
            'Eliminado Correctamente',204
        );
    }
}
