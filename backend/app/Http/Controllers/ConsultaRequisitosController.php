<?php

namespace App\Http\Controllers;
use App\DTOs\consultaRequisitosConstruccion\ConsultaRequisitoConstruccionCreateDto;
use App\DTOs\ConsultaRequisitos\ConsultaRequisitoCreateDto;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\IConsultaRequisitosRepository;
use App\Repositories\Interfaces\ITramiteRepository;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class ConsultaRequisitosController extends Controller
{   
    use ApiResponser;
    private IConsultaRequisitosRepository $consultaRequisitoRepository;
    private ITramiteRepository $tramiteRepository;
   
    public function __construct(IConsultaRequisitosRepository $consultaRequisitoRepository,ITramiteRepository $tramiteRepository)
    {
        $this->consultaRequisitoRepository = $consultaRequisitoRepository;
        $this->tramiteRepository = $tramiteRepository;
    }
    public function getInfoTramite($folio){
      $folio = base64_decode($folio);
      return $this->consultaRequisitoRepository->getInfoTramite($folio);
    }
    public function getInfoTramiteTipo($folio){
      $folio = base64_decode($folio);
      return $this->consultaRequisitoRepository->getInfoTramiteTipo($folio);
    }
    public function getInfoTramiteRefrendo($folio){
      $folio = base64_decode($folio);
      return $this->consultaRequisitoRepository->getInfoTramiteRefrendo($folio);
    }
    public function consultaRequisitosPdf($folio,$id){
      $folio  = base64_decode($folio);
      $id     = base64_decode($id);
      return  $this->consultaRequisitoRepository->consultaRequisitosPdf($folio,$id);
    }
    public function consultaRequisitos2(Request $request)
    {
        $store = new ConsultaRequisitoCreateDto($request->all());
        return $this->successResponse( 
          $this->consultaRequisitoRepository->consultaRequisitos2($store)
        );
     }
     public function consultaRequisitosConstruccion(Request $request)
     {
         $store = new ConsultaRequisitoConstruccionCreateDto($request->all());
         return $this->successResponse( 
           $this->consultaRequisitoRepository->consultaRequisitosConstruccion($store)
         ); 
    }
    public function consultaRequisitosPdfConstruccion($folio,$id){
      $folio  = base64_decode($folio);
      $id     = base64_decode($id);
      return  $this->consultaRequisitoRepository->consultaRequisitosPdfConstruccion($folio,$id);
      //return view('requisitos',(array)$dataCon); 
    }
}