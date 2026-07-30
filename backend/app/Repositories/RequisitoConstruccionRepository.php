<?php

namespace App\Repositories;

use App\DTOs\ConsultaRequisitosConstruccion\ConsultaRequisitoConstruccionUpdateDto;
use App\DTOs\RequisitosConstruccion\RequisitoCreateDto;
use App\DTOs\RequisitosConstruccion\RequisitoUpdateDto;
use App\Models\ConsultaRequisito;
use App\Models\ConsultaRequisitoConstruccion;
use App\Models\RequisitoConstruccion;
use App\Models\TramiteConstruccion;

use App\Repositories\Interfaces\IRequisitoConstruccionRepository;
use App\Traits\ApiResponser;
use App\User;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RequisitoConstruccionRepository implements IRequisitoConstruccionRepository
{
    use ApiResponser;
    public function paginate(int $take, int $municipio): LengthAwarePaginator
    {
        return DB::table("requisitos")->select('*')
            ->where('municipios_id', $municipio)
            ->paginate($take);
    }
    public function find(int $id): ?RequisitoConstruccion
    {
        return RequisitoConstruccion::find($id);
    }
    public function store(RequisitoCreateDto $store): RequisitoConstruccion
    {
        $entry = new RequisitoConstruccion();
        $entry->municipios_id = $store->municipios_id;
        $entry->campos_id = $store->campos_id;
        $entry->id_requisitos = $store->id_requisitos;
        $entry->save();

        return $entry;
    }
    public function update(RequisitoUpdateDto $store): void
    {
        $entry = RequisitoConstruccion::find($store->id);
        $entry->municipios_id = $store->municipios_id;
        $entry->campos_id = $store->campos_id;
        $entry->id_requisitos = $store->id_requisitos;
        $entry->save();
    }

    public function updateRequisito(ConsultaRequisitoConstruccionUpdateDto $store)
    {
        $userRole  = null;
        $id_user = Auth::user()->id;
        $user = User::find($id_user);

        if ($user->roles_construccion->isEmpty()) {
            return false;
        }
        $userRole = $user->roles_construccion[0]->id ?: null;
        $entry = ConsultaRequisitoConstruccion::find($store->id);
        $entry->id_usuario = $id_user;
        $entry->save();
        $q = TramiteConstruccion::where('folio', $entry->folio)->first();
      
        // if (isset($q)) {
        //     dd("test");
        // }

        if (!$q) {
            $tramite = new TramiteConstruccion();
            $tramite->folio = $entry->folio;
            $tramite->step_actual = 0;
            $tramite->firma_usuario = '';
            $tramite->id_usuario = $id_user;
            $tramite->id_usuario_ventanilla = $userRole > 1 ? $id_user : 0;
            $tramite->rol_ingreso = $userRole;
            $tramite->fecha_inicio_tramite = Carbon::now();
            $tramite->fecha_visto_ventanilla = $userRole > 1 ? Carbon::now() : null;
            $tramite->pdf_licencia = '';
            $tramite->status = 1;
            $tramite->tipo_tramite = $entry->tramite_relacionado;
            $tramite->save();
            return  $tramite;
        } else {
            if ($entry->id_usuario != 0) {
                if (($userRole == 1 && $id_user == $entry->id_usuario)) {
                    return true;
                } else if ($userRole > 1) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    public function destroy(int $id): void
    {
        $requisito = RequisitoConstruccion::find($id);
        $requisito->delete();
    }

    public function existeFolio(string $folio)
    {
     

        return ConsultaRequisitoConstruccion::where('folio', $folio)
        ->whereDate('created_at', '>', Carbon::now()
            ->subDays(30))->get();
    }

    public function municipioOnline($data)
    {
        return DB::table("municipios_construccion")->select('*')
            ->where('id', $data[0]->id_municipio)->first();
    }
}
