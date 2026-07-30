<?php

namespace App\Http\Controllers;

use App\DTOs\ConsultaRequisitosConstruccion\ConsultaRequisitoConstruccionUpdateDto;
use App\DTOs\ConsultaRequisitos\ConsultaRequisitoUpdateDto;
use App\DTOs\RequisitosConstruccion\RequisitoCreateDto;
use App\DTOs\RequisitosConstruccion\RequisitoUpdateDto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Repositories\Interfaces\IRequisitoRepository;
use App\Repositories\Interfaces\IRequisitoConstruccionRepository;
use App\Traits\LogHistorico;
use App\Traits\LogHistoricoConstruccion;
use App\Traits\ApiResponser;
use App\User;
use Illuminate\Support\Facades\Auth;

class RequisitoController extends Controller
{
    use ApiResponser;
    use LogHistorico;
    use LogHistoricoConstruccion;
    private IRequisitoRepository $requisitoRepository;
    private IRequisitoConstruccionRepository $requisitoConstruccionRepository;

    public function __construct(IRequisitoRepository $requisitoRepository, IRequisitoConstruccionRepository $requisitoConstruccionRepository)
    {
        $this->RequisitoRepository             = $requisitoRepository;
        $this->RequisitoConstruccionRepository = $requisitoConstruccionRepository;
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
        $currentUser           = Auth::user();
        if($currentUser->user_type == 0){
            $role = $currentUser->roles[0]->id;
        }else if($currentUser->user_type == 1){
            $role = $currentUser->roles_construccion[0]->id;
        }
        if ($role > 1) {
            $result = $this->RequisitoRepository->existeFolio(base64_decode($folio));
            if (count($result)>0) {
                $muni = $this->RequisitoRepository->municipioOnline($result);
                if ($muni->emitir_licencia) {
                    $data2          =
                        $valor_anterior = '';
                    $result[0]->typeGoC = 0;
                    $this->historial($accion = 'Se inicio un nuevo tramite', $valor_anterior, $data2, $tipo = 2, 0);
                    return $this->successResponse(
                        $result,
                        202
                    );
                } else {
                    return $this->errorResponse('Este Municipio no está disponible para hacer el trámite en línea, por lo que se recomienda acudir a él para realizar el trámite y validar la vigencia de los requisitos.', 400);
                }
            }
        } else {
            $result = $this->RequisitoRepository->existeFolio(base64_decode($folio));
            if (count($result) > 0) {
                if (count($result)>0) {
                    $muni = $this->RequisitoRepository->municipioOnline($result);
                    if ($muni->emitir_licencia) {
                        $data2          =
                            $valor_anterior = '';
                        $result[0]->typeGoC = 0;
                        $this->historial($accion = 'Se inicio un nuevo tramite', $valor_anterior, $data2, $tipo = 2, 0);
                        return $this->successResponse(
                            $result,
                            202
                        );
                    } else {
                        return $this->errorResponse('Este Municipio no está disponible para hacer el trámite en línea, por lo que se recomienda acudir a él para realizar el trámite y validar la vigencia de los requisitos.', 400);
                    }
                }
            } else {
                $result = $this->RequisitoConstruccionRepository->existeFolio(base64_decode($folio));
                if (count($result)>0) {
                    $muni = $this->RequisitoConstruccionRepository->municipioOnline($result);
                    if ($muni->emitir_licencia) {
                        $data2          =
                            $valor_anterior = '';
                        $this->historialConstruccion($accion = 'Se inicio un nuevo tramite', $valor_anterior, $data2, $tipo = 2, 0);
                        $result[0]->typeGoC = 1;
                        return $this->successResponse(
                            $result,
                            202
                        );
                    } else {
                        return $this->errorResponse('Este Municipio no está disponible para hacer el trámite en línea, por lo que se recomienda acudir a él para realizar el trámite y validar la vigencia de los requisitos.', 400);
                    }
                }
            }
            return $result;
        }
        return $this->errorResponse('El folio del trámite no existe o ya no está vigente. Ingresa uno correcto o consulta nuevamente los requisitos aquí', 400);
    }

    public function updateConsultaRequisito(int $id, Request $request)
    {
        // Mapping
        $data        = $request->all();
        $data['id']  = $id;

        $type = $data['tram_type'];
        unset($data['tram_type']);


        if($type == 0){
            $entry       = new ConsultaRequisitoUpdateDto($data);
            $result      = $this->RequisitoRepository->updateRequisito($entry);
        }else if($type == 1){
            $entry       = new ConsultaRequisitoConstruccionUpdateDto($data);
            $result      = $this->RequisitoConstruccionRepository->updateRequisito($entry);
        }

        if ($result == false) {
            return $this->successResponse(
                'Ocurrion un Error',
                405
            );
        } else {
            return $this->successResponse(
                $type,
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