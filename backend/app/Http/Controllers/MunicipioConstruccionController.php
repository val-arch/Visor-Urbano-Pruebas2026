<?php
namespace App\Http\Controllers;

use App\DTOs\MunicipiosConstruccion\MunicipioCreateDto;
use App\DTOs\MunicipiosConstruccion\MunicipioUpdateDto;
use App\Http\Controllers\Controller;
use App\Models\Municipio;
use App\Repositories\Interfaces\ILogs;
use App\Repositories\Interfaces\IMunicipioConstruccionRepository;
use App\Traits\ApiResponser;
use App\Traits\LogHistoricoConstruccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


use App\Models\FirmaMunicipio;
use App\Models\FirmaMunicipioConstruccion;
use App\Models\MunicipioConstruccion;
use App\Models\TipoTramiteConstruccion;
use App\Models\TemplatesTramiteConstruccion;
use App\Models\RespuestasTemplatesTramiteConstruccion;
use App\Models\TramiteConstruccion;

class MunicipioConstruccionController extends Controller
{


    use ApiResponser;
    use LogHistoricoConstruccion;
    private IMunicipioConstruccionRepository $municipioRepository;
    public function __construct(IMunicipioConstruccionRepository $municipioRepository)
    {
        $this->municipioRepository = $municipioRepository;
        
    }
    public function index($value = null, Request $request)
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
        if($currentUser->roles_construccion[0]->id !=5){
            return $this->errorResponse('No Cuenta Con Municipio asignado', 404);      
        }

        if($value != null){
           // return $this->municipioRepository->paginate2(20,$order,$filtro);
        }else{
            return $this->municipioRepository->paginate(20,$order,$filtro);
        }

        
    }

    public function show(int $id)
    {
        $result = $this->municipioRepository->find($id);
        if($result) {
            
            return $this->successResponse(
                $result,202
            );
        }
        return $this->errorResponse('Producto No Encotrado', 404);      
    }
    public function showMunicipio(Request $request)
    {
        $currentUser = Auth::user();
        
        if($currentUser->roles_construccion[0]->id == 5){
            $municipio_id = $request->input('id');
        }else if(!isset($currentUser->id_municipio) || $currentUser->id_municipio == null  ){
            return $this->errorResponse('No Cuenta Con Municipio asignado', 404);      
        }else{
            $municipio_id = $currentUser->id_municipio;
        }
        
        $result = $this->municipioRepository->findMunicipio($municipio_id);
        if($result) {
            return $this->successResponse(
                $result,202
            );
           
        }
        return $this->errorResponse('Producto No Encotrado', 404);      
    }

    public function store(Request $request)
    {
       
        // Validation
        $this->validate($request, [
            'nombre'   => 'required',
            'director' => 'required',
        ]);
        // Mapping
        $store = new MunicipioCreateDto($request->all());
       
        $data2          = json_encode($request->except(['token']));   
        $valor_anterior = '';
        $this->historialConstruccion($accion='Se registro un nuevo  municipio',$valor_anterior,$data2,$tipo=2,0);
    
        $result = $this->municipioRepository->store($store);
        return $this->successResponse(
            'Guardado Correctamente',202
        );
    }

    public function update(int $id, Request $request)
    {

        // Validation
        $this->validate($request, [
           // 'nombre'    => 'required',
            'director'  => 'required'
        ]);

        // Mapping
        $data        = $request->all();
        $data['id']  = $id;
        if( $data['ficha_tramite'] ){
            $data['ficha_tramite']  = 1;
        }else{
            $data['ficha_tramite']  = 0;
        }
      

        $data2          = json_encode($request->except(['token']));   
      
        $usuariosData   = MunicipioConstruccion::where('id', $request->id)->first();
        $valor_anterior = json_encode($usuariosData->getAttributes());
        $this->historialConstruccion($accion='Se Actualizo los datos del municipio',$valor_anterior,$data2,$tipo=3,0);
    

        $entry       = new MunicipioUpdateDto($data);
        // Update
        $this->municipioRepository->update($entry);
        return $this->successResponse(
            'Actualizado Correctamente',202
        );
    }

    public function image(int $id, Request $request)
    {

        $currentUser = Auth::user();
        if(!isset($currentUser->id_municipio) || $currentUser->id_municipio == null  ){
            return $this->errorResponse('No Cuenta Con Municipio asignado', 404);      
        }
      //  $id = $currentUser->id_municipio;
        $this->validate($request,
         [
            'image' => 'mimes:jpeg,png,bmp,tiff',
            'image' => 'required'

        ]);
        $this->municipioRepository ->image($id,$request->file('image'));
        $data2          = json_encode($request->except(['token']));   
        $usuariosData   = MunicipioConstruccion::where('id', $request->id)->first();
        $valor_anterior = json_encode($usuariosData->getAttributes());
        $this->historialConstruccion($accion='Se Actualizo Imagen del  municipio',$valor_anterior,$data2,$tipo=3,0);
        

        return $this->successResponse(
            'Actualizado Correctamente',204
        );
    }
    public function firma(int $id, Request $request)
    {

        $currentUser = Auth::user();
        if(!isset($currentUser->id_municipio) || $currentUser->id_municipio == null  ){
            return $this->errorResponse('No Cuenta Con Municipio asignado', 404);      
        }
      //  $id = $currentUser->id_municipio;
        $this->validate($request,
         [
            'image' => 'mimes:jpeg,png,bmp,tiff',
            'image' => 'required'

        ]);
        $this->municipioRepository ->firma(
            $id,
            $request->file('image'));

            $data2          = json_encode($request->except(['token']));   
            $usuariosData   = MunicipioConstruccion::where('id', $request->id)->first();
            $valor_anterior = json_encode($usuariosData->getAttributes());
            $this->historialConstruccion($accion='Se Actualizo firma del  municipio',$valor_anterior,$data2,$tipo=3,0);
        
            return $this->successResponse(
                'Actualizado Correctamente',204
            );
    }


   public function destroy(int $id)
    {
        $this->municipioRepository->destroy($id);

        return $this->successResponse(
            'Eliminado Correctamente',204
        );
    }

    
    public function getFirmas(Request $request){
        $currentUser = Auth::user();
        $role = $currentUser->roles_construccion[0]->id;
        if($currentUser->roles_construccion[0]->id == 5){
            $municipio_id = $request->input('id');
        }else{
            $municipio_id = $currentUser->id_municipio;
        }
        if($role< 4){
            return $this->errorResponse('No tienes acceso a la función', 403);
        }
        $firmas = FirmaMunicipioConstruccion::where(['id_municipio'=>$municipio_id])->orderBy('id','asc')->get();
        return $this->successResponse(
            $firmas,200
        );
    }
    public function firmateLicencia($order, Request $request){
        $data = $request->all();
        $currentUser = Auth::user();
        $role = $currentUser->roles_construccion[0]->id;
        if($role!=4){
            return $this->errorResponse('No tienes acceso a la función', 403);
        }
        if($request->file('image')){
            $this->validate($request,
            [
               'image' => 'mimes:jpeg,png,bmp,tiff',
   
           ]);
           $file = $request->file('image');
           $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
            // path
            $path     = app()->basePath('public/images/municipio/'.$currentUser->id_municipio.'/');
            // upload
            $file->move($path, $filename);
        }
        $firmas = FirmaMunicipioConstruccion::where(['id_municipio'=>$currentUser->id_municipio,'orden'=>$order])->first();
        if($firmas){
            //Update
            $d = ['nombre_firmante'=>$data['nombre_firma'],
            'dependencia'=>$data['cargo_firma'],
            'firma'=> $request->file('image') ? env('APP_URL').'images/municipio/'.$currentUser->id_municipio.'/'. $filename : ($data['image'] ?? null)];
            $f = FirmaMunicipioConstruccion::where(['id_municipio'=>$currentUser->id_municipio,'orden'=>$order])->update(
                    $d);
            $d['id']= $firmas->id;

            $data2          = json_encode($request->except(['token']));   
           
            $valor_anterior = '';
            $this->historialConstruccion($accion='Se Actualizo firma del  municipio',$valor_anterior,$data2,$tipo=3,0);
        
            return $this->successResponse($d,200);  
        }else{
            //insert
            $f = new FirmaMunicipioConstruccion();
            $f->nombre_firmante = $data['nombre_firma'];
            $f->dependencia     = $data['cargo_firma'];
            $f->orden           = $order;
            $f->firma           = $request->file('image') ? env('APP_URL').'images/municipio/'.$currentUser->id_municipio.'/'. $filename : null;
            $f->id_municipio    = $currentUser->id_municipio;
            $f->save();    
            $data2          = json_encode($request->except(['token']));   
           
            $valor_anterior = '';
            $this->historialConstruccion($accion='Se Actualizo firma del  municipio',$valor_anterior,$data2,$tipo=3,0);
        
            return $this->successResponse($f,200);        
        }
        
    }
    public function deleteFirma($id){
        $currentUser = Auth::user();
        $role = $currentUser->roles_construccion[0]->id;
        if($role!=4){
            return $this->errorResponse('No tienes acceso a la función', 403);
        }
        $firmas = FirmaMunicipioConstruccion::find($id);
        if($firmas && ($firmas->id_municipio == $currentUser->id_municipio)){
            //Update
            $firmas->delete();
            $data2          = $id;
           
            $valor_anterior = '';
            $this->historialConstruccion($accion='Se Elimino firma del  municipio',$valor_anterior,$data2,$tipo=6,0);
            return $this->successResponse(
                "Borrada con exito",200
            );  
            
        }else{
            //insert
            return $this->errorResponse('No se puede borrar', 402);         
        }
        
    }

    public function addFile(int $id, $request): void
    {
        
    }

    public function storeCampoTemplate(Request $request){

        $currentUser = Auth::user();

        $campo = new TemplatesTramiteConstruccion();
        $campo->id_tramite = $request->id_tramite;
        $campo->id_municipio = $currentUser->id_municipio;
        $campo->campo = $request->campo;
        if($campo->save()){
            return $this->successResponse(202);
        }else{
            return $this->errorResponse('Product not found', 404);  
        }
    }

    public function emitirResolutivo($id){

        $tramite  = TramiteConstruccion::where('id', $id)->update(['resolutivo' => true]);
        if($tramite){
            return $this->successResponse(202);
        }else{
            return $this->errorResponse('Product not found', 404);  
        }
    }

    public function storeRespuestaTemplate(Request $request){

        $campo = RespuestasTemplatesTramiteConstruccion::where('id_municipio', $request->id_municipio)->where('id_campo', $request->id)->where('id_tramite_construccion', $request->id_tramite_construccion)->where('id_tramite', $request->id_tramite)->first();

        $nombreCampo = TemplatesTramiteConstruccion::where('id_municipio', $request->id_municipio)->where('id', $request->id)->first();
 
        if ($campo !== null) {
            $campo = RespuestasTemplatesTramiteConstruccion::where('id_municipio', $request->id_municipio)->where('id_campo', $request->id)->where('id_tramite_construccion', $request->id_tramite_construccion)->where('id_tramite', $request->id_tramite)->update(['respuesta' => $request->value, 'campo' => $nombreCampo->campo]);
            if($campo){
                return $this->successResponse(202);
            }else{
                return $this->errorResponse('Product not found', 404);  
            }
        } else {
            $campo = new RespuestasTemplatesTramiteConstruccion();
            $campo->id_tramite = $request->id_tramite;
            $campo->id_tramite_construccion = $request->id_tramite_construccion;
            $campo->id_municipio = $request->id_municipio;
            $campo->id_campo = $request->id;
            $campo->respuesta = $request->value;
            $campo->campo = $nombreCampo->campo;
            
            if($campo->save()){
                return $this->successResponse(202);
            }else{
                return $this->errorResponse('Product not found', 404);  
            }
        }
    }

    public function updateCampoTemplate(Request $request){

        $update = TemplatesTramiteConstruccion::where('id',$request->id)->update(
            ['campo'=>$request->campo]
        );
        
        if($update){
            return $this->successResponse(202);
        }else{
            return $this->errorResponse('Product not found', 404);  
        }
    }

    public function storeTipoTramite(Request $request){

        $tramite = new TipoTramiteConstruccion();
        $tramite->tramite = $request->tramite;
        $tramite->folio_interno = $request->folio_interno;
        $tramite->default_preguntas = $request->default_preguntas;
        $tramite->id_municipio = $request->id_municipio;
        if($tramite->save()){
            return $this->successResponse(202);
        }else{
            return $this->errorResponse('Product not found', 404);  
        }
    }


    public function storeFirma(Request $request){

        $firma = new FirmaMunicipioConstruccion();
        $firma->nombre_firmante = $request->nombre_firmante;
        $firma->dependencia = $request->dependencia;
        $firma->id_municipio = $request->id_municipio;
        
        if($firma->save()){
            return $this->successResponse(202);
        }else{
            return $this->errorResponse('Product not found', 404);  
        }
    }

    public function editFirma(Request $request){

        $update = FirmaMunicipioConstruccion::where('id',$request->id)->update(
            ['nombre_firmante'=>$request->nombre_firmante, 'dependencia'=>$request->dependencia]
        );
        
        if($update){
            return $this->successResponse(202);
        }else{
            return $this->errorResponse('Product not found', 404);  
        }
    }

    public function removeFirma($id){

        return FirmaMunicipioConstruccion::find($id)->delete();

    }

    public function getTipoTramite($id_municipio){

        return TipoTramiteConstruccion::where('id_municipio', $id_municipio)->orderBy('id', 'asc')->get();

    }

    public function getCamposTemplate($id_tramite){
        $currentUser = Auth::user();
        return TemplatesTramiteConstruccion::where('id_municipio', $currentUser->id_municipio)->where('id_tramite', $id_tramite)->orderBy('id', 'asc')->get();

    }

    public function getRespuestasTemplate($id_tramite_relacionado, $id_tramite_construccion, $id_municipio){
        return RespuestasTemplatesTramiteConstruccion::where('id_municipio', $id_municipio)->where('id_tramite', $id_tramite_relacionado)->where('id_tramite_construccion', $id_tramite_construccion)->orderBy('id', 'asc')->get();

    }

    public function getTipoTramite2($tramite_relacionado){

        return TipoTramiteConstruccion::where('id', $tramite_relacionado)->withTrashed()->get();

    }

    public function removeTipoTramites($id){

        return TipoTramiteConstruccion::find($id)->delete();

    }

    public function removeCampoTemplate($id){

        return TemplatesTramiteConstruccion::find($id)->delete();

    }

    public function updateTipoTramite($id, Request $request){

        $tramite = TipoTramiteConstruccion::find($id);

        $tramite->tramite = $request->tramite;
        $tramite->folio_interno = $request->folio_interno;
        $tramite->default_preguntas = $request->default_preguntas;

        if($tramite->save()){
            return $this->successResponse(202);
        }else{
            return $this->errorResponse('Product not found', 404);  
        }
    }

}
