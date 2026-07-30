<?php
namespace App\Repositories\Interfaces;

use App\Models\RoleConstruccion;
use Illuminate\Pagination\Paginator;
use App\DTOs\RolesConstruccion\RoleCreateDto;
use App\DTOs\RolesConstruccion\RoleUpdateDto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface IRoleConstruccionRepository
{
    public function paginate(int $take):LengthAwarePaginator;
    public function getAll($id_municipio);
    public function paginate2(int $take, int $value):LengthAwarePaginator;
    public function paginaterole(int $take,int $id_usuario,int $id_municipio):LengthAwarePaginator;
    public function find(int $id): ?RoleConstruccion;
    public function store(RoleCreateDto $store, int $value);
    public function update(RoleUpdateDto $store);
    public function destroy(int $id): void;
}
