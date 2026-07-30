<?php
namespace App\Repositories\Interfaces;

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\Paginator;
use App\DTOs\Tramites\TramiteCreateDto;
use App\DTOs\Tramites\TramiteUpdateDto;

interface ITramiteRepository
{
    public function paginate(int $take): Paginator;
    public function getListadoDirRev(string $folio);
    public function getListadoDirSolv(string $folio);
    public function getListadoLicenciasVisor(string $folio);
    public function find(int $id): ?Product;
    public function ingreso(string $folio);
    public function ingresoRefrendo(string $folio);
    public function store(TramiteCreateDto $store): Product;
    public function update(TramiteUpdateDto $store): void;
    public function image(int $id, UploadedFile $file): void;
    public function destroy(int $id): void;
    public function noFirmaElectronica(string $folio);
    public function ingresoTramiteContinuar(string $folio);
    public function getListado(string $folio='');
    public function getListadoDesechados(string $folio='');
    public function getNombreSolicitante(string $folio);
    public function getNombreSolicitanteRefrendo(string $folio);
    public function getListadoVentanilla(string $folio);
    public function getDueno(string $folio);
    public function getDataDueno(string $folio);
    public function getDataDuenoRefrendo(string $folio);
    public function getRespuesta($id_tramite,$name);
    public function copyTramite(string $folio,int $id_municipio);
    public function copyTramiteHist(string $id);

}
