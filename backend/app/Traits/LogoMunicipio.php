<?php

namespace App\Traits;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\IConsultaRequisitos;



trait LogoMunicipio{

    public function getImage($folio){

     // $folio  = base64_decode($folio);
      $data = DB::table('municipios')
                ->join('consulta_requisitos','consulta_requisitos.id_municipio','municipios.id')
                ->select('image')
                ->where('consulta_requisitos.folio', $folio)
                ->get();

               $image =  $data[0]->image;

               if($image === NULL){
                 $logo = "https://cuernavaca.visorurbano.com/assets/images/logo_cuernavaca.svg";
                 return $logo;
               }else{
                 $logo = $image;
                 return $logo;

               }
      }

      public function getImageConstruccion($folio){

        // $folio  = base64_decode($folio);
         $data = DB::table('municipios_construccion')
                   ->join('consulta_requisitos_construccion','consulta_requisitos_construccion.municipio','municipios_construccion.nombre')
                   ->select('image')
                   ->where('consulta_requisitos_construccion.folio', $folio)
                   ->get();

                  $image =  $data[0]->image;

                  if($image === NULL){
                    $logo = "https://cuernavaca.visorurbano.com/assets/images/logo_cuernavaca.svg";
                    return $logo;
                  }else{
                    $logo = $image;
                    return $logo;

                  }
         }
}

