<?php
namespace App\Http\Controllers;
use App\DTOs\ConsultaRequisitosConstruccion\ConsultaRequisitoConstruccionCreateDto;
use App\DTOs\ConsultaRequisitosConstruccion\ConsultaRequisitoConstruccionUpdateDto2;
use App\DTOs\ConsultaRequisitos\ConsultaRequisitoCreateDto;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\IConsultaRequisitosConstruccionRepository;
use App\Repositories\Interfaces\ITramiteConstruccionRepository;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class ConsultaRequisitosConstruccionController extends Controller
{
  use ApiResponser;
  private IConsultaRequisitosConstruccionRepository $consultaRequisitoRepository;
  private ITramiteConstruccionRepository $tramiteRepository;

  public function __construct(IConsultaRequisitosConstruccionRepository $consultaRequisitoRepository, ITramiteConstruccionRepository $tramiteRepository)
  {
    $this->consultaRequisitoRepository = $consultaRequisitoRepository;
    $this->tramiteRepository = $tramiteRepository;
  }
  public function getInfoTramite($folio)
  {
    $folio = base64_decode($folio);
    return $this->consultaRequisitoRepository->getInfoTramite($folio);
  }
  public function getInfoTramiteTipo($folio)
  {
    $folio = base64_decode($folio);
    return $this->consultaRequisitoRepository->getInfoTramiteTipo($folio);
  }
  public function consultaRequisitosPdf($folio, $id)
  {
    $folio  = base64_decode($folio);
    $id     = base64_decode($id);
    return  $this->consultaRequisitoRepository->consultaRequisitosPdf($folio, $id);
    //return view('requisitos',(array)$dataCon);
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
  public function consultaRequisitosPdfConstruccion($folio, $id)
  {
    $folio  = base64_decode($folio);
    $id     = base64_decode($id);
    return  $this->consultaRequisitoRepository->consultaRequisitosPdfConstruccion($folio, $id);
    //return view('requisitos',(array)$dataCon);
  }

  public function getConsultaRequisitosConstruccion($folio)
  {
    $folio  = base64_decode($folio);
    return  $this->consultaRequisitoRepository->getConsultaRequisitosConstruccion($folio);
    //return view('requisitos',(array)$dataCon);
  }

  public function updateConsultaRequisitosConstruccion($folio, Request $request)
  {
    $folio  = base64_decode($folio);
    $data        = $request->all();
    $entry       = new ConsultaRequisitoConstruccionUpdateDto2($data);
    return $this->consultaRequisitoRepository->updateConsultaRequisitosConstruccion($folio, $entry);

    //return view('requisitos',(array)$dataCon);
  }
}
