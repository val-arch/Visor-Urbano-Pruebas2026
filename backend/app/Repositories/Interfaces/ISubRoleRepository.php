<?php
namespace App\Repositories\Interfaces;

use App\DTOs\SubRoles\SubRoleCreateDto;
use App\DTOs\SubRoles\SubRoleUpdateDto;
use App\Models\Subrole;
use Illuminate\Pagination\Paginator;

interface ISubRoleRepository
{
    public function paginate(int $take): Paginator;
    public function paginateMunucipio(int $take,int $id_municipio): Paginator;
    public function find(int $id): ?Subrole;
    public function store(SubRoleCreateDto $store): Subrole;
    public function update(SubRoleUpdateDto $store): void;
    public function destroy(int $id): void;
}
