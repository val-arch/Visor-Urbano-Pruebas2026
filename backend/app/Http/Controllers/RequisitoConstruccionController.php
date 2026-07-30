<?php

namespace App\Http\Controllers;

use App\DTOs\ConsultaRequisitosConstruccion\ConsultaRequisitoConstruccionUpdateDto;
use App\DTOs\RequisitosConstruccion\RequisitoCreateDto;
use App\DTOs\RequisitosConstruccion\RequisitoUpdateDto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Repositories\Interfaces\IRequisitoConstruccionRepository;
use App\Traits\LogHistoricoConstruccion;

use App\Traits\ApiResponser;
use App\User;
use Illuminate\Support\Facades\Auth;

class RequisitoConstruccionController extends Controller
{
    use ApiResponser;
    use LogHistoricoConstruccion;

    private IRequisitoConstruccionRepository $requisitoRepository;

    public function __construct(IRequisitoConstruccionRepository $requisitoRepository)
    {
        $this->RequisitoRepository = $requisitoRepository;
    }
    public function index()
    {
        $currentUser           = Auth::user();
        if (!isset($currentUser->id_municipio) || $currentUser->id_municipio == null) {
            return $this->errorResponse('No Cuenta Con Municipio asignado', 404);
        }
        return $this->RequisitoRepository->paginate(20, $currentUser->id_municipio);
    }
    public function show(int $id)
    {
        $result = $this->RequisitoRepository->find($id);

        if ($result) {
            return $this->successResponse(
                $result,
                204
            );
        }
        return $this->errorResponse('Producto No Encotrado', 404);
    }
    public function store(Request $request)
    {
        $currentUser           = Auth::user();
        $data                  = $request->all();
        if (!isset($currentUser->id_municipio) || $currentUser->id_municipio == null) {
            return $this->errorResponse('No Cuenta Con Municipio asignado', 404);
        }
        $data['municipios_id']  = $currentUser->id_municipio;

        $this->validate($request, [
            'campos_id'     => 'required',
            'id_requisitos' => 'required',

        ]);
        // Mapping
        $store  = new RequisitoCreateDto($data);
        // Creation
        $result = $this->RequisitoRepository->store($store);
        return $this->successResponse(
            'Guardado Correctamente',
            202
        );
    }

    public function update(int $id, Request $request)
    {
        // Mapping
        $data        = $request->all();
        $data['id']  = $id;
        $entry       = new RequisitoUpdateDto($data);
        $result      = $this->RequisitoRepository->update($entry);

        return $this->successResponse(
            'Actualizado Correctamente',
            202
        );
    }

    public function camposRequisitos($id)
    {
        $result = $this->RequisitoRepository->camposRequisitos($id);
        if ($result) {
            return $this->successResponse(
                $result,
                202
            );
        }
        return $this->errorResponse('Campo No Encotrado', 404);
    }
    public function validarFolioRequisito(string $folio)
    {   
         $result = $this->RequisitoRepository->existeFolio(base64_decode($folio));
        
        if ($result) {
            $muni = $this->RequisitoRepository->municipioOnline($result);
            if ($muni->emitir_licencia) {
                $data2          =
                    $valor_anterior = '';
             //   $this->historialConstruccion($accion = 'Se inicio un nuevo tramite', $valor_anterior, $data2, $tipo = 2, 0);
                return $this->successResponse(
                    $result,
                    202
                );
            } else {
                return $this->errorResponse('Este Municipio no está disponible para hacer el trámite en línea, por lo que se recomienda acudir a él para realizar el trámite y validar la vigencia de los requisitos.', 400);
            }
        }
        return $this->errorResponse('El folio del trámite no existe o ya no está vigente. Ingresa uno correcto o consulta nuevamente los requisitos aquí', 400);
    }

    public function updateConsultaRequisito(int $id, Request $request)
    {
        // Mapping
        $data        = $request->all();
        $data['id']  = $id;
        $entry       = new ConsultaRequisitoConstruccionUpdateDto($data);
        $result      = $this->RequisitoRepository->updateRequisito($entry);

        if ($result == false) {
            return $this->successResponse(
                'Ocurrion un Error',
                405
            );
        } else {
            return $this->successResponse(
                $result,
                202
            );
        }
    }
    public function destroy(int $id)
    {
        $this->RequisitoRepository->destroy($id);

        return $this->successResponse(
            'Eliminado Correctamente',
            202
        );
    }
}
