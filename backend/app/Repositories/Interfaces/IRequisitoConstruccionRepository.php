<?php

namespace App\Repositories\Interfaces;

use App\DTOs\ConsultaRequisitosConstruccion\ConsultaRequisitoConstruccionUpdateDto;
use App\DTOs\RequisitosConstruccion\RequisitoCreateDto;
use App\DTOs\RequisitosConstruccion\RequisitoUpdateDto;
use App\Models\RequisitoConstruccion;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

interface IRequisitoConstruccionRepository
{
  public function paginate(int $take, int $municipio): LengthAwarePaginator;
  public function find(int $id): ?RequisitoConstruccion;
  public function store(RequisitoCreateDto $store): RequisitoConstruccion;
  public function update(RequisitoUpdateDto $store): void;
  public function updateRequisito(ConsultaRequisitoConstruccionUpdateDto $store);
  public function destroy(int $id): void;
  public function existeFolio(string $folio);
}
