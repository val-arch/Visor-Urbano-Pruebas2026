<?php
namespace App\Repositories\Interfaces;

use App\DTOs\ConsultaRequisitos\ConsultaRequisitoUpdateDto;
use App\DTOs\Requisitos\RequisitoCreateDto;
use App\DTOs\Requisitos\RequisitoUpdateDto;
use App\Models\Requisito;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

interface IRequisitoRepository
{
    public function paginate(int $take,int $municipio): LengthAwarePaginator;
    public function find(int $id): ?Requisito;
    public function store(RequisitoCreateDto $store): Requisito;
    public function update(RequisitoUpdateDto $store): void;
    public function updateRequisito(ConsultaRequisitoUpdateDto $store);
    public function destroy(int $id): void;
    public function existeFolio(string $folio);
    
}
