<?php
namespace App\Repositories\Interfaces;

use App\Models\Role;
use Illuminate\Pagination\Paginator;
use App\DTOs\Roles\RoleCreateDto;
use App\DTOs\roles\RoleUpdateDto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface IRoleRepository
{
    public function paginate(int $take):LengthAwarePaginator;
    public function paginate2(int $take, int $value):LengthAwarePaginator;
    public function paginaterole(int $take,int $id_usuario,int $id_municipio):LengthAwarePaginator;
    public function find(int $id): ?Role;
    public function store(RoleCreateDto $store, int $value);
    public function update(RoleUpdateDto $store);
    public function destroy(int $id): void;
}
