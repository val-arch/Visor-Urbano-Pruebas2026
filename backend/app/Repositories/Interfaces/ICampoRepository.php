<?php
namespace App\Repositories\Interfaces;

use App\DTOs\CamposRoles\CampoCreateDto;
use App\DTOs\CamposRoles\CampoUpdateDto;
use Illuminate\Pagination\Paginator;
use App\Models\Campo;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


interface ICampoRepository {
   
    public function paginate(int $take,int $municipio,string $filter);
    public function getCampos(int $municipio,int $folio);
    public function getCamposId(int $municipio,int $folio,int $id);
    public function getCamposRefrendos(int $municipio,int $folio);
    public function find(int $id): ?Campo;
    public function store(CampoCreateDto $store): Campo;
    public function update(CampoUpdateDto $store): void;
    public function destroy(int $id): void;
    public function camposRequisitos(int $id);
    public function camposRequisitosFile(int $id_municipio);
    public function camposRequisitoTramite(int $id_municipio);
    public function camposRequisitoTramiteConstruccion(int $id_municipio);
    public function camposRequisitoTramiteConstruccion2(int $id_municipio, int $id);
    public function camposRequisitoTramiteConstruccion3(int $id_municipio, int $id);
    public function agregarRequisito($id_municipio,$campo_id,$idRequisito);
    
}