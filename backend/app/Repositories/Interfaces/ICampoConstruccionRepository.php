<?php
namespace App\Repositories\Interfaces;

use App\DTOs\CamposRolesConstruccion\CampoCreateDto;
use App\DTOs\CamposRolesConstruccion\CampoUpdateDto;
use Illuminate\Pagination\Paginator;
use App\Models\Campo;
use App\Models\CampoConstruccion;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


interface ICampoConstruccionRepository {
   
    public function paginate(int $take,int $municipio,string $filter);
    public function getCampos(int $municipio,int $folio);
    public function getCamposId(int $municipio,int $folio,int $id);
    public function find(int $id): ?CampoConstruccion;
    public function store(CampoCreateDto $store): CampoConstruccion;
    public function update(CampoUpdateDto $store): void;
    public function destroy(int $id): void;
    public function camposRequisitos(int $id);
    public function camposRequisitosFile(int $id_municipio);
    public function camposRequisitoTramite(int $id_municipio);
    public function agregarRequisito($id_municipio,$campo_id,$idRequisito);
    
}