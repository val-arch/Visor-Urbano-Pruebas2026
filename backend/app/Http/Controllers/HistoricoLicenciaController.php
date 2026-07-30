<?php

namespace App\Http\Controllers;

use App\Exports\VisorExport;
use App\Traits\ApiResponser;
use App\Traits\LogHistorico;
use Illuminate\Http\Request;
use App\Exports\HistoricoExport;
use App\Models\HistoricoLicencias;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Repositories\Interfaces\ILogs;
use App\Models\HistoricoLicenciasFiles;
use Illuminate\Support\Facades\Storage;
use App\Imports\HistoricoLicenciaImport;
use App\DTOs\Historico\HistoricoUpdateDto;
use App\Repositories\Interfaces\IHistoricoLicenciaRepository;

class HistoricoLicenciaController extends Controller
{
    use ApiResponser;
    use LogHistorico;
    private IHistoricoLicenciaRepository $historicoRepository;


    public function __construct(IHistoricoLicenciaRepository $historicoRepository)
    {
        $this->historicoRepository = $historicoRepository;
    }
    public function export()
    {
        return Excel::download(new HistoricoExport, 'users.xlsx');
    }
    public function exportVisor()
    {
        return Excel::download(new VisorExport, 'users.xlsx');
    }

    public function importExcel(Request $request)
    {

        try {
            if ($request->hasFile('file')) {
                $extension = File::extension($request->file->getClientOriginalName());
                if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {
                     $file = $request->file('file');

                    $excel =  Excel::import(new HistoricoLicenciaImport, $file);
                    if ($excel) {

                        $data2          = json_encode($request->except(['token']));
                        $valor_anterior = '';
                        $this->historial($accion = 'Se se subio historico de licencias', $valor_anterior, $data2, $tipo = 2, 0);
                        return $this->successResponse(
                            $excel,
                            202
                        );
                    }
                } else {
                    return $this->errorResponse('Favor de Ingresar unicamente archivos excel', 404);
                }
            }
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        } catch (\Error $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }

    public function importar(Request $request)
    {
        Excel::import(new HistoricoLicenciaImport, $request->file('file')->store('files'));
        return false;
        try {
            if ($request->hasFile('file')) {
                $extension = File::extension($request->file->getClientOriginalName());
                if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {
                    $file = $request->file('file');
                    $excel =  Excel::toArray(new HistoricoLicenciaImport, $file);
                    if ($excel) {
                        $data2          = json_encode($request->except(['token']));
                        $valor_anterior = '';
                        //  $this->historial($accion='Se se subio historico de licencias',$valor_anterior,$data2,$tipo=2,0);
                        return $this->successResponse(
                            $excel,
                            202
                        );
                    }
                } else {
                    return $this->errorResponse('Favor de Ingresar unicamente archivos excel', 404);
                }
            }
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        } catch (\Error $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }

    public function update($id, Request $request)
    {
        // Mapping
        $data       = $request->all();
        $data['id'] = $int = (int)$id;
        $entry = new HistoricoUpdateDto($data);

        $HistoricoData   = HistoricoLicencias::where('id', $request->id)->first();
        $valor_anterior = json_encode($HistoricoData->getAttributes());
        // Update
        $this->historicoRepository->update($entry);
        $data2          = json_encode($request->except(['token']));
        $valor_anterior = '';
        $this->historial($accion = 'Se actualizo un registro del historico de  licencias', $valor_anterior, $data2, $tipo = 3, 0);
        return $this->successResponse(
            $entry,
            201
        );
    }




    public function eliminarRegistros()
    {

        HistoricoLicencias::where('id_municipio', Auth::user()->id_municipio)->delete();
        $data2          = 'Se eliminaron todos los registriso historicos de licencias';
        $valor_anterior = '';
        $this->historial($accion = 'Se eliminaron todos los registriso historicos de licencias', $valor_anterior, $data2, $tipo = 6, 0);
        return $this->successResponse('Datos borrados exitosamente', 200);
    }

    public function eliminarFiles($id)
    {

        HistoricoLicenciasFiles::where('id', $id)->delete();
        $data2          = 'Se eliminaron un archivo de una licencia de historico';
        $valor_anterior = '';
        $this->historial($accion = 'Se elimino un archivo', $valor_anterior, $data2, $tipo = 6, 0);
        return $this->successResponse('Datos borrados exitosamente', 200);
    }
    public function eliminarRegistro($id)
    {

        HistoricoLicencias::where('id', $id)->delete();
        $data2          = $id;
        $valor_anterior = '';
        $this->historial($accion = 'Se eliminio un registro del historico de  licencias', $valor_anterior, $data2, $tipo = 6, 0);
        return $this->successResponse('Dato borrado exitosamente', 200);
    }
    public function historico($id)
    {
        $data = DB::table('historico_licencias_giro')
            ->select('*')
            ->where('id', $id)
            ->first();
        if ($data == false) {
            return $this->errorResponse('No tienes permiso', 404);
        } else {
            return $this->successResponse($data);
        }
    }
    public function historicoInfo($id)
    {
        $data = DB::table('refrendos')

            ->select('*')
            ->where('id', $id)
            ->first();
        if ($data == false) {
            return $this->errorResponse('No tienes permiso', 404);
        } else {
            return $this->successResponse($data);
        }
    }
    public function historicoFiles($id)
    {
        $data = DB::table('refrendo_archivos_historico')
            ->select('*')
            ->where('id_historico_licencia', $id)
            ->get();
        if ($data == false) {
            return $this->errorResponse('No tienes permiso', 404);
        } else {
            return $this->successResponse($data);
        }
    }
    public function index(Request $request)
    {
        $currentUser           = Auth::user();
        $id_municipio  = $currentUser->id_municipio;
        return
            $this->successResponse(
                $this->historicoRepository->paginate($request->page, $id_municipio, $request->filter),
                202
            );
    }
    public function getFile($id)
    {
        $data = DB::table('refrendo_archivos')
            ->select('*')
            ->where('id_refrendo', $id)
            ->get();
        if ($data == false) {
            return $this->errorResponse('No tienes permiso', 404);
        } else {
            return $this->successResponse($data);
        }
    }
    public function getFileTipo($id)
    {
        $data = DB::table('refrendos')
            ->select('*')
            ->join('refrendo_archivos', 'refrendos.id', '=', 'refrendo_archivos.id_refrendo')
            ->where('id_tramite', $id)
            ->get();
        if ($data == false) {
            return $this->errorResponse('No tienes permiso', 404);
        } else {
            return $this->successResponse($data);
        }
    }
    public function delete($id)
    {
        $this->historicoRepository->delete($id);
        $data2          = $id;
        $valor_anterior = '';
        $this->historial($accion = 'Se eliminio un registro del historico de  licencias', $valor_anterior, $data2, $tipo = 6, 0);
        return $this->successResponse(
            'Eliminado Correctamente',
            202
        );
    }
}
