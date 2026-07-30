<?php
namespace App\Repositories\Interfaces;

use App\DTOs\Municipios\MunicipioCreateDto;
use App\DTOs\Municipios\MunicipioUpdateDto;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\Paginator;
use App\Models\Municipio;
use Illuminate\Pagination\LengthAwarePaginator;

interface IMunicipioRepository
{
    public function paginate(int $take,string $order,string $filter): LengthAwarePaginator;
    public function find(int $id): ?Municipio;
    public function findMunicipio(int $municipio): ?Municipio;
    public function store(MunicipioCreateDto $store): Municipio;
    public function update(MunicipioUpdateDto $store): void;
    public function image(int $id, UploadedFile $file): void;
    public function firma(int $id, UploadedFile $file): void;
    public function destroy(int $id): void;
}
