<?php
namespace App\Repositories\Interfaces;

use App\Models\Usuario;
use App\DTOs\Usuarios\UsuarioCreateDto;
use App\DTOs\Usuarios\UsuarioRoleUpdateDto;
use App\DTOs\Usuarios\UsuarioUpdateDto;
use App\DTOs\Usuarios\UsuarioMunicipioUpdateDto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface IUsuarioRepository
{
    public function paginate(int $take,string $filter);
    public function paginate2(int $take,string $filter);
    public function paginate3(int $take,string $filter);
    public function paginate4(int $take,string $filter);
    public function find(int $id): ? Usuario;
    public function store(UsuarioCreateDto $store, int $value);
    public function storeConstruccion(UsuarioCreateDto $store, int $value);
    public function update(UsuarioUpdateDto $store): void;
    public function updateMunicipio(UsuarioMunicipioUpdateDto $store): void;
    public function destroy(int $id): void;
    public function roleAsignado(int $id);
    public function asignarRole(UsuarioRoleUpdateDto $store,int $id_municipio,$token);
    public function asignarRoleConstruccion(UsuarioRoleUpdateDto $store,int $id_municipio,$token);
    public function asignarSubRole(int $id_usuario, int $id_subrole);
    public function quitarSubRole(int $id_usuario);
    public function quitarRole(int $id): void;
    public function quitarRoleConstruccion(int $id): void;
}