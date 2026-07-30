<?php
namespace App\Repositories\Interfaces;
use App\Models\ConsultaRequisito;
use App\DTOs\ConsultaRequisitos\ConsultaRequisitoCreateDto;
use App\DTOs\ConsultaRequisitosConstruccion\ConsultaRequisitoConstruccionCreateDto;

interface IConsultaRequisitosRepository {
   
   //public function contadorRequisitos($id_municipio);
   //public function insertRequisitos($data);
    public function getInfoTramite(string $folio);
    public function getInfoTramiteTipo(string $folio);
    public function getInfoTramiteRefrendo(string $folio);
    public function consultaRequisitosPdf(string $folio,int $id);
    public function consultaRequisitos2(ConsultaRequisitoCreateDto $store);
    //interface Construccion
    public function consultaRequisitosConstruccion(ConsultaRequisitoConstruccionCreateDto $store);
    public function consultaRequisitosPdfConstruccion(string $folio,int $id);
}