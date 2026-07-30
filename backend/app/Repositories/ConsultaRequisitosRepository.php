<?php

namespace App\Repositories;

use App\DTOs\ConsultaRequisitos\ConsultaRequisitoCreateDto;
use App\DTOs\ConsultaRequisitosConstruccion\ConsultaRequisitoConstruccionCreateDto;
use App\Repositories\Interfaces\IConsultaRequisitosRepository;
use App\Traits\ApiResponser;
use App\Traits\LogoMunicipio;
use App\User;
use App\Models\FirmaGiro;
use App\Models\Refrendo;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade as PDF;
use Endroid\QrCode\QrCode;
use Illuminate\Support\Facades\DB;

class ConsultaRequisitosRepository implements IConsultaRequisitosRepository
{

    use ApiResponser;
    use LogoMunicipio;

    public function getInfoTramite($folio)
    {
        $user = Auth::user();
        $id_user = $user->id;
        $userRole = $user->roles[0]->id;


        $data = DB::table('consulta_requisitos')
            ->join('tramite', 'consulta_requisitos.folio', '=', 'tramite.folio')
            ->select('consulta_requisitos.*', 'step_actual', 'tramite.carta_responsiva', 'tramite.pdf_licencia', 'firma_usuario as f_user', 'tramite.id_usuario as t_id_u', 'tramite.id as id_tramite', 'tramite.id_usuario_ventanilla as t_id_uv')
            ->where('consulta_requisitos.folio', $folio)
            ->get();
        if (($userRole > 1) || ($userRole == 1 && $data[0]->t_id_u == $id_user)) {
            $firma = 0;
            $muni = DB::table('municipios')->where(['id' => $data[0]->id_municipio])->first();
            if ($data[0]->f_user != '') {
                $firma = FirmaGiro::find($data[0]->f_user);
            }
            $arr = ['info' => $data[0], 'firma' => (empty($firma->hash_firmado) ? 0 : $firma->hash_firmado)];
            $arr['lic_v'] = $muni->generar_licencia_ventanilla;
            return $this->successResponse($arr);
        } else {
            return $this->errorResponse('No se puede acceder a la información', 403);
        }
    }
    public function getInfoTramiteTipo($folio)
    {
        $user = Auth::user();
        $id_user = $user->id;
        $userRole = $user->roles[0]->id;


        $data = DB::table('consulta_requisitos')
            ->join('tramite', 'consulta_requisitos.folio', '=', 'tramite.folio')
            ->select('consulta_requisitos.*', 'step_actual', 'tramite.carta_responsiva', 'tramite.pdf_licencia', 'firma_usuario as f_user', 'tramite.id_usuario as t_id_u', 'tramite.id as id_tramite', 'tramite.id_usuario_ventanilla as t_id_uv')
            ->where('consulta_requisitos.folio_primary', $folio)
            ->get();
        if (($userRole > 1) || ($userRole == 1 && $data[0]->t_id_u == $id_user)) {
            $firma = 0;
            $muni = DB::table('municipios')->where(['id' => $data[0]->id_municipio])->first();
            if ($data[0]->f_user != '') {
                $firma = FirmaGiro::find($data[0]->f_user);
            }
            $arr = ['info' => $data[0], 'firma' => (empty($firma->hash_firmado) ? 0 : $firma->hash_firmado)];
            $arr['lic_v'] = $muni->generar_licencia_ventanilla;
            return $this->successResponse($arr);
        } else {
            return $this->errorResponse('No se puede acceder a la información', 403);
        }
    }
    public function getInfoTramiteRefrendo($folio)
    {
        $user = Auth::user();
        $id_user = $user->id;
        $userRole = $user->roles[0]->id;
        $RefrendoTramite =  Refrendo::where('id', $folio)->firstOrFail();
        $data = DB::table('consulta_requisitos')
            ->join('tramite', 'consulta_requisitos.id', '=', 'tramite.id_consulta_requisitos')
            ->select('consulta_requisitos.*', 'step_actual', 'tramite.carta_responsiva', 'tramite.pdf_licencia', 'firma_usuario as f_user', 'tramite.id_usuario as t_id_u', 'tramite.id as id_tramite', 'tramite.id_usuario_ventanilla as t_id_uv')
            ->where('consulta_requisitos.id', $RefrendoTramite->id_consulta_requisitos)
            ->get();
        if (($userRole > 1) || ($userRole == 1 && $data[0]->t_id_u == $id_user)) {
            $firma = 0;
            $muni = DB::table('municipios')->where(['id' => $data[0]->id_municipio])->first();
            if ($data[0]->f_user != '') {
                $firma = FirmaGiro::find($data[0]->f_user);
            }
            $arr = ['info' => $data[0], 'firma' => (empty($firma->hash_firmado) ? 0 : $firma->hash_firmado)];
            $arr['lic_v'] = $muni->generar_licencia_ventanilla;
            return $this->successResponse($arr);
        } else {
            return $this->errorResponse('No se puede acceder a la información', 403);
        }
    }
    public function contadorRequisitos($id_municipio)
    {
    }
    public function insertRequisitos($data)
    {
    }
    public function consultaRequisitosPdf($folio, $id)
    {

        $qrCode = new QrCode("https://api.visorurbano.cuernavaca.gob.mx/consulta_requisitos/requisitos/" . base64_encode($folio) . "/" . base64_encode($id));

        $data = DB::table('consulta_requisitos')
            ->where('id', $id)
            ->where('folio', $folio)
            ->get();
        $data2 = DB::table('respuestas_json')
            ->where('id_tramite', $id)
            ->get();

        if (count($data) == 0 || count($data2) == 0) {
            return $this->errorResponse('No existe en la base de datos la consulta de requisitos', 404);
        }

        $id_municipio = $data[0]->id_municipio;

        $data3 = DB::table('municipios')
            ->where('id', $id_municipio)
            ->get();
        $respuestasConsulta = DB::table('campos')->join('respuestas', 'campos.name', 'respuestas.name')
            ->select('campos.*', 'respuestas.value')->where('respuestas.id_tramite', $id)->whereIn('campos.tipo_tramite', ['consulta', 'consulta_requisitos'])->get();

        $imagen = $data3[0]->image == '' ? 'https://cuernavaca.visorurbano.com/assets/images/logo_cuernavaca.svg' : $data3[0]->image;
        $n = 0;
        //return substr($data[0]->codigo_scian,0,3);
        if (substr($data[0]->codigo_scian, 0, 3) == 111) {
            $n = 111;
        }
        if (substr($data[0]->codigo_scian, 0, 2) == 52) {
            $n = 52;
        }
        $apoyo_economico = DB::table('apoyos_economicos')
            ->whereIn('scian', [0, $n])
            ->get();
        $le = json_decode($data2[0]->respuestas, true);
        $qrCode = $qrCode->writeString(); //Salida en formato de texto
        $qrCode = base64_encode($qrCode);
        $logo = $this->getImage($folio);

        $dataCon = array('data' => $data[0], 'municipio' => $data3[0], 'respuestas' => $le, 'qr' => $qrCode, 'apoyo' => $apoyo_economico, 'image' => $imagen, 'logo' =>  $logo, 'rc' => $respuestasConsulta);
        $pdf = PDF::loadView('requisitos', (array) $dataCon)->setWarnings(false);
        $pdf->setOptions(
            [
                'isPhpEnabled' => true,
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true
            ]
        );

        return $pdf->stream($folio . '.pdf');
    }

    public function consultaRequisitos2(ConsultaRequisitoCreateDto $store)
    {
       // $user = Auth::user();
        $id_user =0;
        $data = $store;
        $calle = $store->calle ?? null;
        $colonia = $store->colonia ?? null;
        $municipio = $store->municipio ?? null;
        $id_municipio = $store->id_municipio ?? null;
        $codigo_scian = $store->codigo_scian ?? 0;
        $nombre_scian = $store->nombre_scian ?? 0;
        $superficie_propiedad = $store->superficie_propiedad ?? null;
        $superficie_actividad = $store->superficie ?? null;
        $nombre_solicitante = $store->nombre ?? null;
        $caracter_solicitante = $store->caracter ?? null;
        $tipo_persona = $store->tipo_persona ?? null;
        $alcohol = $store->alcohol ?? 0;
        $url_minimapa = $store->url_minimapa ?? null;
        $restricciones = $store->restricciones ?? null;
        $arreglo_respuestas = [];

        $contador = DB::table('consulta_requisitos')
            ->select(DB::raw('count(*) as c'))
            ->where('id_municipio', $id_municipio)
            ->where('anio_folio', date('Y'))
            ->get();

        //return  $contador[0]->c;
        $contador = $contador[0]->c + 1;
        $folio = $id_municipio . "-$contador/" . date('Y');
        //Inserta los datos enviados desde el front end
        $datos = ['calle' => $calle,
            'colonia' => $colonia,
            'municipio' => $municipio,
            'id_municipio' => $id_municipio,
            'codigo_scian' => $codigo_scian,
            'nombre_scian' => $nombre_scian,
            'superficie_propiedad' => $superficie_propiedad,
            'superficie_actividad' => $superficie_actividad,
            'nombre_solicitante' => $nombre_solicitante,
            'caracter_solicitante' => $caracter_solicitante,
            'url_minimapa' => $url_minimapa,
            'tipo_persona' => $tipo_persona,
            'restricciones' => json_encode($restricciones),
            'created_at' => date('Y-m-d H:m:s'),
            'anio_folio' => date('Y'),
            'folio' => $folio,
            'venta_alcohol'=> $alcohol];
        $insertID = DB::table('consulta_requisitos')
            ->insertGetId($datos);

        $campos = DB::table("campos")
            ->join('requisitos', 'campos.id', '=', 'requisitos.campos_id')
            ->select('campos.*')
            ->where('requisitos.municipios_id', [$id_municipio])
            ->where('status', 1)
            ->where('tipo_tramite', '!=', 'consulta_requisitos')
            ->where('tipo_tramite', '!=', 'consulta')
        //->whereIn('type', ['file','multifile'])
            ->get();

        $respuesta = new \stdClass;
        $camposDinamicos = (($store->camposDinamicos));
        $camposDinamicos['caracter_solicitante'] = $caracter_solicitante;
        $camposDinamicos['tipo_persona'] = $tipo_persona;
        $camposDinamicos['superficie_propiedad'] = $superficie_propiedad;
        $camposDinamicos['superficie_actividad'] = $superficie_actividad;
        if($alcohol == 0){
            $alcohol = 6;
        }
        $camposDinamicos['check_alcohol'] = $alcohol;
        ///return $camposDinamicos;
        foreach ($store->camposDinamicos as $key => $value) {
            $respuesta->{$key} = (object) [
                'name' => $key,
                'respuesta' => $value,
                'created_at' => date('Y-m-d H:m:s'),
                'updated_at' => date('Y-m-d H:m:s'),
            ];
            $r = [
                'id_tramite' => $insertID,
                'id_usuario' => $id_user,
                'name' => $key,
                'value' => $value,
                'created_at' => date('Y-m-d H:m:s'),
                'updated_at' => date('Y-m-d H:m:s'),
            ];
            array_push($arreglo_respuestas, $r);
        }
        foreach ($campos as $key) {
            if ($key->requerido == 1) {
                $respuesta->{$key->name} = (object) [
                    'campo' => $key,
                    'tipo' => $key->type,
                    'name' => $key->name,
                    'respuesta' => null,
                    'created_at' => date('Y-m-d H:m:s'),
                    'updated_at' => date('Y-m-d H:m:s'),
                ];
                $r = [
                    'id_tramite' => $insertID,
                    'id_usuario' => $id_user,
                    'name' => $key->name,
                    'value' => null,
                    'created_at' => date('Y-m-d H:m:s'),
                    'updated_at' => date('Y-m-d H:m:s'),
                ];
                array_push($arreglo_respuestas, $r);
            } else if ($key->requerido == 2) {
                if ($key->condicion_visible) {

                    $con = preg_replace('/\/(\w*)/', '(isset($camposDinamicos["$1"]) ? $camposDinamicos["$1"] : null)', $key->condicion_visible);
                    // echo $con;
                    if (eval("return ($con) ? 1 : 0;")) {
                        $respuesta->{$key->name} = (object) [
                            'campo' => $key,
                            'tipo' => $key->type,
                            'name' => $key->name,
                            'respuesta' => null,
                            'created_at' => date('Y-m-d H:m:s'),
                            'updated_at' => date('Y-m-d H:m:s'),
                        ];
                        $r = [
                            'id_tramite' => $insertID,
                            'id_usuario' => 0,
                            'name' => $key->name,
                            'value' => null,
                            'created_at' => date('Y-m-d H:m:s'),
                            'updated_at' => date('Y-m-d H:m:s'),
                        ];
                        array_push($arreglo_respuestas, $r);
                    }
                } else if ($key->condicion_giro != '') {
                    $girosIf = $key->condicion_giro;
                    $tieneComa = strpos($girosIf, ',');
                    if ($tieneComa === false) {
                        if ($codigo_scian == $girosIf) {
                            $respuesta->{$key->name} = (object) [
                                'campo' => $key,
                                'tipo' => $key->type,
                                'name' => $key->name,
                                'respuesta' => null,
                                'created_at' => date('Y-m-d H:m:s'),
                                'updated_at' => date('Y-m-d H:m:s'),
                            ];
                            $r = [
                                'id_tramite' => $insertID,
                                'id_usuario' => 0,
                                'name' => $key->name,
                                'value' => null,
                                'created_at' => date('Y-m-d H:m:s'),
                                'updated_at' => date('Y-m-d H:m:s'),
                            ];
                            array_push($arreglo_respuestas, $r);
                        }
                    } else {
                        $arregloGiro = explode(",", $girosIf);
                        for ($i = 0; $i < count($arregloGiro); $i++) {
                            if ($codigo_scian == $arregloGiro[$i]) {
                                $respuesta->{$key->name} = (object) [
                                    'campo' => $key,
                                    'tipo' => $key->type,
                                    'name' => $key->name,
                                    'respuesta' => null,
                                    'created_at' => date('Y-m-d H:m:s'),
                                    'updated_at' => date('Y-m-d H:m:s'),
                                ];
                                $r = [
                                    'id_tramite' => $insertID,
                                    'id_usuario' => 0,
                                    'name' => $key->name,
                                    'value' => null,
                                    'created_at' => date('Y-m-d H:m:s'),
                                    'updated_at' => date('Y-m-d H:m:s'),
                                ];
                                array_push($arreglo_respuestas, $r);
                            }
                        }

                    }
                }
            }
        }
        $respuesta_i = DB::table('respuestas')
            ->where('id_tramite', $insertID)
            ->get();
        if (count($respuesta_i) > 0) {

            /*DB::table('respuestas')
        ->where('id_tramite',$tramite)
        ->delete();*/
        } else {

            $insert2 = DB::table('respuestas')
                ->insert(
                    $arreglo_respuestas
                );
        }
        //return $respuesta;
        $json = ($respuesta);

        $json->url = "consulta_requisitos/requisitos/" . base64_encode($folio) . "/" . base64_encode($insertID);
        $json->folio = $folio;
        $mun = DB::table('municipios')
            ->where('id', $id_municipio)
            ->first();
        $json->emitir_licencia = $mun->emitir_licencia;

        $respuesta2 = DB::table('respuestas_json')
            ->where('id_tramite', $insertID)
            ->get();
        if (count($respuesta2) > 0) {
            $insert = DB::table('respuestas_json')
                ->where('id_tramite', $insertID)
                ->update(
                    ['id_tramite' => $insertID,
                        'id_usuario' => 1,
                        'respuestas' => json_encode($respuesta),
                        'updated_at' =>
                        date('Y-m-d H:m:s')]
                );
        } else {
            $insert = DB::table('respuestas_json')->insert(
                ['id_tramite' => $insertID,
                    'id_usuario' => 1,
                    'respuestas' => json_encode($respuesta),
                    'created_at' => date('Y-m-d H:m:s'),
                    'updated_at' => date('Y-m-d H:m:s')]
            );
        }

        return $json;

    }


    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///                                     Repositorios Requisitos Construccion
    ///
    ///
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function consultaRequisitosConstruccion(ConsultaRequisitoConstruccionCreateDto $store)
    {

        $id_user = 0;
        $data = $store;
        $calle = $store->calleC ?? null;
        $colonia = $store->coloniaC ?? null;
        $municipio = $store->municipio ?? null;
        $id_municipio = $store->id_municipio ?? null;
        $superficie_propiedad = $store->superficie_propiedad ?? null;
        $niveles_nuevos_construir = $store->niveles_nuevos_construir ?? null;
        $numero_viviendas = $store->numero_viviendas ?? null;
        $sotano = $store->sotano ?? null;
        $mdemolicion = $store->mdemolicion ?? null;
        $nivelesObra = $store->nivelesObra ?? null;
        $nombre_solicitante = $store->nombreC ?? null;
        $url_minimapa = $store->url_minimapa ?? null;
        $restricciones = $store->restricciones ?? null;
        $arreglo_respuestas = [];
        $superficie_habitacional = $store->superficie_habitacional ?? null;
        $superficie_comercial_servicios = $store->superficie_comercial_servicios ?? null;
        $superficie_industrial = $store->superficie_industrial ?? null;
        $superficie_turistico = $store->superficie_turistico ?? null;
        $superficie_equipamiento = $store->superficie_equipamiento ?? null;
        $superficie_espacios_verdes = $store->superficie_espacios_verdes ?? null;
        $superficie_otro = $store->superficie_otro ?? null;
        $concepto_otro = $store->concepto_otro ?? null;


        $contador = DB::table('consulta_requisitos_construccion')
            ->select(DB::raw('count(*) as c'))
            ->where('id_municipio', $id_municipio)
            ->where('anio_folio', date('Y'))
            ->get();

        //return  $contador[0]->c;
        $contador = $contador[0]->c + 1;
        $folio = $id_municipio . "-$contador/" . date('Y');
        //Inserta los datos enviados desde el front end
        $datos = [
            'calle' => $calle,
            'colonia' => $colonia,
            'municipio' => $municipio,
            'id_municipio' => $id_municipio,
            'superficie_propiedad' => $superficie_propiedad,
            'nombre_solicitante' => $nombre_solicitante,
            'niveles_nuevos_construir' => $niveles_nuevos_construir,
            'numero_viviendas' => $numero_viviendas,
            'sotano' => $sotano,
            'mdemolicion' => $mdemolicion,
            'url_minimapa' => $url_minimapa,
            'restricciones' => json_encode($restricciones),
            'created_at' => date('Y-m-d H:m:s'),
            'anio_folio' => date('Y'),
            'folio' => $folio,
            'superficie_habitacional' => $superficie_habitacional,
            'superficie_comercial_servicios' => $superficie_comercial_servicios,
            'superficie_industrial' => $superficie_industrial,
            'superficie_turistico' => $superficie_turistico,
            'superficie_equipamiento' => $superficie_habitacional,
            'superficie_espacios_verdes' => $superficie_espacios_verdes,
            'superficie_equipamiento' => $superficie_equipamiento,
            'superficie_otro' => $superficie_otro,
            'concepto_otro' => $concepto_otro,
        ];
        $insertID = DB::table('consulta_requisitos_construccion')
            ->insertGetId($datos);
        $campos = DB::table("campos_construccion")
            ->join('requisitos_construccion', 'campos_construccion.id', '=', 'requisitos_construccion.campos_id')
            ->select('campos_construccion.*')
            ->where('requisitos_construccion.municipios_id', [$id_municipio])
            ->where('status', 1)
            ->where('tipo_tramite', '!=', 'consulta_requisitos')
            ->where('tipo_tramite', '!=', 'consulta')
            //->whereIn('type', ['file','multifile'])
            ->get();
        $respuesta = new \stdClass;
        foreach ($campos as $key) {
            if ($key->requerido == 1) {
                $respuesta->{$key->name} = (object) [
                    'campo' => $key,
                    'tipo' => $key->type,
                    'name' => $key->name,
                    'respuesta' => null,
                    'created_at' => date('Y-m-d H:m:s'),
                    'updated_at' => date('Y-m-d H:m:s'),
                ];
                $r = [
                    'id_tramite' => $insertID,
                    'id_usuario' => $id_user,
                    'name' => $key->name,
                    'value' => null,
                    'created_at' => date('Y-m-d H:m:s'),
                    'updated_at' => date('Y-m-d H:m:s'),
                ];
                array_push($arreglo_respuestas, $r);
            }
        }
        $respuesta_i = DB::table('respuestas_construccion')
            ->where('id_tramite', $insertID)
            ->get();
        if (count($respuesta_i) > 0) {
        } else {
            $insert2 = DB::table('respuestas_construccion')
                ->insert(
                    $arreglo_respuestas
                );
        }
        $json = ($respuesta);
        $json->url = "consulta_requisitosConstruccion/requisitos/" . base64_encode($folio) . "/" . base64_encode($insertID);
        $json->folio = $folio;
        $mun = DB::table('municipios')
            ->where('id', $id_municipio)
            ->first();
        $json->emitir_licencia = $mun->emitir_licencia;
        $respuesta2 = DB::table('respuestas_construccion_json')
            ->where('id_tramite', $insertID)
            ->get();
        if (count($respuesta2) > 0) {
            $insert = DB::table('respuestas_construccion_json')
                ->where('id_tramite', $insertID)
                ->update(
                    [
                        'id_tramite' => $insertID,
                        'id_usuario' => 1,
                        'respuestas' => json_encode($respuesta),
                        'updated_at' =>
                        date('Y-m-d H:m:s')
                    ]
                );
        } else {
            $insert = DB::table('respuestas_construccion_json')->insert(
                [
                    'id_tramite' => $insertID,
                    'id_usuario' => 1,
                    'respuestas' => json_encode($respuesta),
                    'created_at' => date('Y-m-d H:m:s'),
                    'updated_at' => date('Y-m-d H:m:s')
                ]
            );
        }
        return $json;
    }


    public function consultaRequisitosPdfConstruccion($folio, $id)
    {
        $qrCode = new QrCode("https://api.visorurbano.cuernavaca.gob.mx/consulta_requisitosConstruccion/requisitos/" . base64_encode($folio) . "/" . base64_encode($id));

        $data = DB::table('consulta_requisitos_construccion')
            ->where('id', $id)
            ->where('folio', $folio)
            ->get();
        $data2 = DB::table('respuestas_construccion_json')
            ->where('id_tramite', $id)
            ->get();

        if (count($data) == 0 || count($data2) == 0) {
            return $this->errorResponse('No existe en la base de datos la consulta de requisitos', 404);
        }

        $id_municipio = $data[0]->id_municipio;

        $data3 = DB::table('municipios_construccion')
            ->where('id', $id_municipio)
            ->get();
        $respuestasConsulta = DB::table('campos_construccion')->join('respuestas_construccion', 'campos_construccion.name', 'respuestas_construccion.name')->select('campos_construccion.*', 'respuestas_construccion.value')->where('respuestas_construccion.id_tramite', $id)->whereIn('campos_construccion.tipo_tramite', ['consulta', 'consulta_requisitos'])->get();
        $imagen = $data3[0]->image == '' ? 'https://cuernavaca.visorurbano.com/assets/images/logo_cuernavaca.svg' : $data3[0]->image;


        $apoyo_economico = '';
        $le = json_decode($data2[0]->respuestas, true);
        $qrCode = $qrCode->writeString(); //Salida en formato de texto
        $qrCode = base64_encode($qrCode);
        $logo = $this->getImageConstruccion($folio);

        $dataCon = array('data' => $data[0], 'municipio' => $data3[0], 'respuestas' => $le, 'qr' => $qrCode, 'apoyo' => $apoyo_economico, 'image' => $imagen, 'logo' =>  $logo, 'rc' => $respuestasConsulta);
        $pdf = PDF::loadView('requisitosConstruccion', (array) $dataCon)->setWarnings(false);
        $pdf->setOptions(
            [
                'isPhpEnabled' => true,
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true
            ]
        );

        return $pdf->stream($folio . '.pdf');
    }
}
