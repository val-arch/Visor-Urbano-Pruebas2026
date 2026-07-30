<?php
namespace App\Repositories\Interfaces;

use App\DTOs\Historico\HistoricoUpdateDto;
use Illuminate\Pagination\Paginator;

interface IHistoricoLicenciaRepository
{
    public function paginate(int $take,int $id_municipio,string $filter);
    public function delete(int $id): void;
    public function update(HistoricoUpdateDto $store): void;
  
}
