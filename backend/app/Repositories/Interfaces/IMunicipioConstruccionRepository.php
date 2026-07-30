<?php
namespace App\Repositories\Interfaces;

use App\DTOs\MunicipiosConstruccion\MunicipioCreateDto;
use App\DTOs\MunicipiosConstruccion\MunicipioUpdateDto;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\Paginator;
use App\Models\MunicipioConstruccion;
use Illuminate\Pagination\LengthAwarePaginator;

interface IMunicipioConstruccionRepository
{
    public function paginate(int $take,string $order,string $filter): LengthAwarePaginator;
  //  public function paginate2(int $take,string $order,string $filter): LengthAwarePaginator;
    public function find(int $id): ?MunicipioConstruccion;
    public function findMunicipio(int $municipio): ?MunicipioConstruccion;
    public function store(MunicipioCreateDto $store): MunicipioConstruccion;
    public function update(MunicipioUpdateDto $store): void;
    public function image(int $id, UploadedFile $file): void;
    public function firma(int $id, UploadedFile $file): void;
    public function destroy(int $id): void;
}
