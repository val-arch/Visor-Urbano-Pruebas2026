<?php
namespace App\Repositories\Interfaces;

use App\Models\GiroApagado;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\Paginator;
use App\DTOs\GirosApagados\GirosApagadosUpdateDto;
use App\DTOs\GirosApagados\GirosApagadosCreateDto;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
interface IGirosApagadosRepository
{
    public function store(GirosApagadosCreateDto $store): GiroApagado;
    public function storeStatus(GirosApagadosCreateDto $store,$status);
    public function storeStatusCedula(GirosApagadosCreateDto $store,$status);
    public function destroy(int $id): void;
    public function getGirosOn(int $take,int $municipio): LengthAwarePaginator;
    
    public function getGirosPublic(int $take,int $municipio):Collection;
    public function getGirosOff(int $take,int $municipio): LengthAwarePaginator;
    public function getGirosAll(int $take,int $municipio,string $order,string $filter): LengthAwarePaginator;

}
