<?php

namespace App\Repositories;

use App\DTOs\ConsultaRequisitosConstruccion\ConsultaRequisitoConstruccionCreateDto;
use App\DTOs\ConsultaRequisitosConstruccion\ConsultaRequisitoConstruccionUpdateDto2;
use App\DTOs\ConsultaRequisitos\ConsultaRequisitoCreateDto;
use App\Models\ConsultaRequisitoConstruccion;
use App\Models\FichaTecnica;
use App\Models\FirmaGiro;
use App\Models\MunicipioConstruccion;
use App\Repositories\Interfaces\IConsultaRequisitosConstruccionRepository;
use App\Traits\ApiResponser;
use App\Traits\LogoMunicipio;
use Barryvdh\DomPDF\Facade as PDF;
use Endroid\QrCode\QrCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\CampoConstruccion;

class ConsultaRequisitosConstruccionRepository implements IConsultaRequisitosConstruccionRepository
{

    use ApiResponser;
    use LogoMunicipio;

    public function getInfoTramite($folio)
    {
        $user = Auth::user();
        $id_user = $user->id;

        if($user->user_type == 0){
            $userRole = $user->roles[0]->id;
        }else if($user->user_type == 1){
            $userRole = $user->roles_construccion[0]->id;
        }

        $data = DB::table('consulta_requisitos_construccion')
            ->join('tramite_construccion', 'consulta_requisitos_construccion.folio', '=', 'tramite_construccion.folio')
            ->join('tipo_tramites_construccion', 'consulta_requisitos_construccion.tramite_relacionado', '=', 'tipo_tramites_construccion.id')
            ->select('consulta_requisitos_construccion.*', 'step_actual', 'tramite_construccion.carta_responsiva', 'tramite_construccion.pdf_licencia', 'tramite_construccion.firma_usuario as f_user', 'tramite_construccion.id_usuario as t_id_u', 'tramite_construccion.id as id_tramite', 'tramite_construccion.id_usuario_ventanilla as t_id_uv', 'tipo_tramites_construccion.tramite')
            ->where('consulta_requisitos_construccion.folio', $folio)
            ->get();

        if (($userRole > 1) || ($userRole == 1 && $data[0]->t_id_u == $id_user)) {
            $firma = 0;
            $muni = DB::table('municipios_construccion')->where(['id' => $data[0]->id_municipio])->first();
            if ($data[0]->f_user != '') {
                $firma = FirmaGiro::find($data[0]->f_user);
            }
            $arr = ['info' => $data[0], 'firma' => (empty($firma->hash_firmado) ? 0 : $firma->hash_firmado)];
            // $arr['lic_v'] = $muni->generar_licencia_ventanilla;
            return $this->successResponse($arr);
        } else {
            return $this->errorResponse('No se puede acceder a la información', 403);
        }
    }
    public function getInfoTramiteTipo($folio)
    {
        $user = Auth::user();
        $id_user = $user->id;
        $userRole = $user->roles_construccion[0]->id;

        $data = DB::table('consulta_requisitos_construccion')
            ->join('tramite', 'consulta_requisitos_construccion.folio', '=', 'tramite_construccion.folio')
            ->select('consulta_requisitos_construccion.*', 'step_actual', 'tramite_construccion.carta_responsiva', 'tramite_construccion.pdf_licencia', 'firma_usuario as f_user', 'tramite_construccion.id_usuario as t_id_u', 'tramite_construccion.id as id_tramite', 'tramite_construccion.id_usuario_ventanilla as t_id_uv')
            ->where('consulta_requisitos_construccion.folio_primary', $folio)
            ->get();
        if (($userRole > 1) || ($userRole == 1 && $data[0]->t_id_u == $id_user)) {
            $firma = 0;
            $muni = DB::table('municipios_construccion')->where(['id' => $data[0]->id_municipio])->first();
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

        $qrCode = new QrCode("https://api-visorurbano.jalisco.gob.mx/consulta_requisitos/requisitos/" . base64_encode($folio) . "/" . base64_encode($id));

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
        $respuestasConsulta = DB::table('campos_construccion')->join('respuestas_construccion', 'campos_construccion.name', 'respuestas_construccion.name')
            ->select('respuestas_construccion.*', 'respuestas_construccion.value')->where('respuestas_construccion.id_tramite', $id)->whereIn('campos_construccion.tipo_tramite', ['consulta', 'consulta_requisitos'])->get();

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

        $dataCon = array('data' => $data[0], 'municipio' => $data3[0], 'respuestas' => $le, 'qr' => $qrCode, 'apoyo' => $apoyo_economico, 'image' => $imagen, 'logo' => $logo, 'rc' => $respuestasConsulta);
        $pdf = PDF::loadView('requisitos', (array) $dataCon)->setWarnings(false);
        $pdf->setOptions(
            [
                'isPhpEnabled' => true,
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
            ]
        );

        return $pdf->stream($folio . '.pdf');
    }

    public function consultaRequisitos2(ConsultaRequisitoCreateDto $store)
    {
        // $user = Auth::user();
        $id_user = 0;
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
            'venta_alcohol' => $alcohol,
        ];
        $insertID = DB::table('consulta_requisitos_construccion')
            ->insertGetId($datos);

        $campos = DB::table("campos_construccion")
            ->join('requisitos_construccion', 'campos_construccion.id', '=', 'requisitos_construccion.campos_id')
            ->select('campos_construccion.*')
            ->where('requisitos_construccion.municipios_id', [$id_municipio])
            ->where('status', 1)
            ->where('tipo_tramite', '!=', 'consulta_requisitos_construccion')
            ->where('tipo_tramite', '!=', 'consulta')
        //->whereIn('type', ['file','multifile'])
            ->get();

        $respuesta = new \stdClass;
        $camposDinamicos = (($store->camposDinamicos));
        $camposDinamicos['caracter_solicitante'] = $caracter_solicitante;
        $camposDinamicos['tipo_persona'] = $tipo_persona;
        $camposDinamicos['superficie_propiedad'] = $superficie_propiedad;
        $camposDinamicos['superficie_actividad'] = $superficie_actividad;
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
                        date('Y-m-d H:m:s'),
                    ]
                );
        } else {
            $insert = DB::table('respuestas_construccion_json')->insert(
                [
                    'id_tramite' => $insertID,
                    'id_usuario' => 1,
                    'respuestas' => json_encode($respuesta),
                    'created_at' => date('Y-m-d H:m:s'),
                    'updated_at' => date('Y-m-d H:m:s'),
                ]
            );
        }

        return $json;
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///                                     Repositorios Requisitos Construccion
    ///
    ///
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function curlUrl($url)
    {

        $urlDecoded = str_replace(' ', '%20', $url);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $urlDecoded,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
    public function consultaRequisitosConstruccion(ConsultaRequisitoConstruccionCreateDto $store)
    {

        $id_user = 0;
        $data = $store;
        $tramite_relacionado = $store->tramite_relacionado ?? null;
        $calle = $store->calleC ?? null;
        $colonia = $store->coloniaC ?? null;
        $municipio = $store->municipio ?? null;
        $id_municipio = $store->id_municipio ?? null;
        $superficie_propiedad = $store->superficie_propiedad ?? null;
        $sotano = $store->sotano ?? null;
        $mdemolicion = $store->mdemolicion ?? null;
        $nombre_solicitante = $store->nombreC ?? null;
        $url_minimapa = $store->url_minimapa ?? null;
        $restricciones = $store->restricciones ?? null;
        $niveles_nuevos_construir = $store->niveles_nuevos_construir ?? null;
        $superficie_habitacional = $store->superficie_habitacional ?? null;
        $superficie_comercial_servicios = $store->superficie_comercial_servicios ?? null;
        $superficie_industrial = $store->superficie_industrial ?? null;
        $superficie_turistico = $store->superficie_turistico ?? null;
        $superficie_equipamiento = $store->superficie_equipamiento ?? null;
        $superficie_espacios_verdes = $store->superficie_espacios_verdes ?? null;
        $superficie_otro = $store->superficie_otro ?? null;
        $concepto_otro = $store->concepto_otro ?? null;
        $numero_viviendas = $store->numero_viviendas ?? null;
        $coords = $store->coords ?? null;
        $uuid = $store->uuid ?? null;

        $arreglo_respuestas = [];

        $contador = DB::table('consulta_requisitos_construccion')
            ->select(DB::raw('count(*) as c'))
            ->where('id_municipio', $id_municipio)
            ->where('anio_folio', date('Y'))
            ->get();

        //return  $contador[0]->c;
        $contador = $contador[0]->c + 1;
        $folio = "OP" . $id_municipio . "-$contador/" . date('Y');
        //Inserta los datos enviados desde el front end
        $datos = [
            'calle' => $calle,
            'colonia' => $colonia,
            'municipio' => $municipio,
            'tramite_relacionado' => $tramite_relacionado,
            'id_municipio' => $id_municipio,
            'superficie_propiedad' => $superficie_propiedad,
            'nombre_solicitante' => $nombre_solicitante,
            'sotano' => $sotano,
            'mdemolicion' => $mdemolicion,
            'url_minimapa' => $url_minimapa,
            'restricciones' => json_encode($restricciones),
            'anio_folio' => date('Y'),
            'created_at' => date('Y-m-d H:m:s'),
            'folio' => $folio,
            'superficie_habitacional' => $superficie_habitacional,
            'superficie_comercial_servicios' => $superficie_comercial_servicios,
            'superficie_industrial' => $superficie_industrial,
            'superficie_turistico' => $superficie_turistico,
            'superficie_equipamiento' => $superficie_equipamiento,
            'superficie_espacios_verdes' => $superficie_espacios_verdes,
            'superficie_otro' => $superficie_otro,
            'concepto_otro' => $concepto_otro,
            'niveles_nuevos_construir' => $niveles_nuevos_construir,
            'numero_viviendas' => $numero_viviendas,
            'coords' => $coords,
            'uuid' => $uuid,
        ];

        try {
            $insertID = DB::table('consulta_requisitos_construccion')->insertGetId($datos, 'id');
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }

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
        $camposDinamicos = (($store->camposDinamicos));

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
            }
            if ($key->requerido == 2) {

                $flags = [];

                $pushArray = false;

                array_push($flags, $this->operacion($superficie_propiedad, $key->actividad_condicion, $key->actividad_metros));
                $total_contruccion_temp = $superficie_habitacional + $superficie_comercial_servicios + $superficie_industrial + $superficie_turistico + $superficie_equipamiento + $superficie_espacios_verdes + $superficie_otro;
                array_push($flags, $this->operacion($total_contruccion_temp, $key->construccion_total_metros_condicion, $key->construccion_total_metros_value));

                $array_uso = json_decode($key->construccion_uso);
                $array_metros = json_decode($key->construccion_uso_metros);
                $array_destino = json_decode($key->construccion_uso_destino);
                for ($i = 0; $i < count($array_uso); ++$i) {
                    if ($array_destino[$i] == "Habitacional") {
                        array_push($flags, $this->operacion($superficie_habitacional, $array_uso[$i], $array_metros[$i]));
                    } else if ($array_destino[$i] == "Comercial y/o servicios") {
                        array_push($flags, $this->operacion($superficie_comercial_servicios, $array_uso[$i], $array_metros[$i]));
                    } else if ($array_destino[$i] == "Industrial") {
                        array_push($flags, $this->operacion($superficie_industrial, $array_uso[$i], $array_metros[$i]));
                    } else if ($array_destino[$i] == "Alojamiento temporal (Turístico)") {
                        array_push($flags, $this->operacion($superficie_turistico, $array_uso[$i], $array_metros[$i]));
                    } else if ($array_destino[$i] == "Equipamiento") {
                        array_push($flags, $this->operacion($superficie_equipamiento, $array_uso[$i], $array_metros[$i]));
                    } else if ($array_destino[$i] == "Espacios verdes,abiertos y recreativos") {
                        array_push($flags, $this->operacion($superficie_espacios_verdes, $array_uso[$i], $array_metros[$i]));
                    } else if ($array_destino[$i] == "Otro") {
                        array_push($flags, $this->operacion($superficie_otro, $array_uso[$i], $array_metros[$i]));
                    }
                }

                array_push($flags, $this->operacion($mdemolicion, $key->demolicion_metros_condicion, $key->demolicion_metros_value));

                array_push($flags, $this->operacion($niveles_nuevos_construir, $key->nivel_nuevo_construccion_condicion, $key->nivel_nuevo_construccion_value));

                array_push($flags, $this->operacion($sotano, $key->sotano_nuevo_construccion_condicion, $key->sotano_nuevo_construccion_value));

                array_push($flags, $this->operacion($numero_viviendas, $key->viviendas_construccion_condicion, $key->viviendas_construccion_value));

                if ($key->todasCondiciones == "true") {
                    $pushArray = true;
                    if (in_array(0, $flags)) {
                        $pushArray = false;
                    }
                } else {
                    if (in_array(1, $flags)) {
                        $pushArray = true;
                    }
                }

                //if(todasCondiciones == true)
                /**
                 * Validar que se cumplan todas las condiciones
                 * Si cumple con  cada una una de las condiciones se hace el array_push
                 *
                 */
                /**
                 *
                 * Validar que se cumpla algunas de las condiciones
                 * Si cumple con una de las condiciones se hace el array_push
                 *
                 **/

                //print_r($flags);

                if ($pushArray) {
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
        $mun = DB::table('municipios_construccion')
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
                        date('Y-m-d H:m:s'),
                    ]
                );
        } else {
            $insert = DB::table('respuestas_construccion_json')->insert(
                [
                    'id_tramite' => $insertID,
                    'id_usuario' => 1,
                    'respuestas' => json_encode($respuesta),
                    'created_at' => date('Y-m-d H:m:s'),
                    'updated_at' => date('Y-m-d H:m:s'),
                ]
            );
        }
        return $json;
    }

    public function getConsultaRequisitosConstruccion($folio)
    {
        $data = DB::table('consulta_requisitos_construccion')
            ->where('folio', $folio)
            ->get();
        return $data;
    }

    public function updateConsultaRequisitosConstruccion($folio, ConsultaRequisitoConstruccionUpdateDto2 $store)
    {
        $entry = ConsultaRequisitoConstruccion::where('folio', $folio)->first();
        $entry->superficie_habitacional = $store->superficie_habitacional;
        $entry->superficie_comercial_servicios = $store->superficie_comercial_servicios;
        $entry->superficie_industrial = $store->superficie_industrial;
        $entry->superficie_turistico = $store->superficie_turistico;
        $entry->superficie_equipamiento = $store->superficie_equipamiento;
        $entry->superficie_espacios_verdes = $store->superficie_espacios_verdes;
        $entry->superficie_otro = $store->superficie_otro;
        $entry->mdemolicion = $store->mdemolicion;
        $entry->save();
    }

    public function consultaRequisitosPdfConstruccion($folio, $id)
    {
        $qrCode = new QrCode("https://api-visorurbano.jalisco.gob.mx/consulta_requisitosConstruccion/requisitos/" . base64_encode($folio) . "/" . base64_encode($id));
        $data = DB::table('consulta_requisitos_construccion')
            ->where('id', $id)
            ->where('folio', $folio)
            ->get();

        $uuid = $data[0]->uuid;

        ///*****************************************************/
        $ficha = FichaTecnica::where('uuid', $uuid)->first();
        $urlLic = env('APP_URL');
        $coordenadas = $ficha->coordenadas;
        $direccion = $ficha->direccion;
        $metros = $ficha->metros;
        $img = $ficha->img;
        $metros = json_decode($metros);

        $qrCode = new QrCode();

        // var_dump($qrCode);
        // die();

        // $qrCode->setlogoPath("https://visorurbano.guadalajara.gob.mx/assets/img/logo.png");
        // $qrCode->setSize(500);
        // $qrCode->setLogoSize(500);
        // $qrCode->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0));
        // $qrCode->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0));
        // $qrCode->setLabel('My label');

        // var_dump($qrCode);
        // die();

        $c = json_decode(base64_decode($coordenadas));
        $text_coords = "";
        for ($i = 0; $i < count($c); $i++) {
            $text_coords .= $c[$i][0] . " " . $c[$i][1] . ($i + 1 == count($c) ? "" : ",");
        }
        $coords = "POLYGON (($text_coords))";
        $url = env('GEOSERVER_APP') . "ows?service=WFS&request=GetFeature&version=2.0.0&typename=VUJ:planparcialdesarrollourbano32613&outputFormat=application/json&cql_filter=INTERSECTS(geom, " . $coords . ")";
        $response = $this->curlUrl($url);
        $data_c = json_decode($response, true);
        $error = false;
        $messageError = '';
        if (isset($data_c['numberReturned'])) {
            if ($data_c['numberReturned'] > 0) {
                $features = $data_c['features'];
                $i = 0;
                $tt = 0;
                foreach ($features as $value) {
                    $clave = $value['properties']['clave_de_clasificacion_de_zona_secundaria'];
                    if ($features[0]['properties']['municipio_id'] == 98) {
                        $clave = $value['properties']['clave_de_zonificacion'];
                    }

                    $exp = explode(',', $clave);
                    foreach ($exp as $key) {
                        $url2 = env("DJANGO_REST") . "norma-control-edificacion/?municipio={$value['properties']['municipio_id']}&clave={$key}&distrito={$value['properties']['distrito']}";
                        $d = $this->curlUrl($url2);
                        if (count(array($d)) > 0) {
                            $features[$i] = $features[$tt];
                            $features[$i]['properties']['normas_control_edificacion'] = json_decode($d);
                            $features[$i]['properties']['clave_act'] = $key;
                            $i++;
                        }
                    }
                    $tt++;
                }
                // return ($features);
                // die();

                $municipio = MunicipioConstruccion::find($features[0]['properties']['municipio_id']);
                $logo = "https://cuernavaca.visorurbano.com/assets/images/logo_cuernavaca.svg";
                if (count($features) == 0) {
                    $error = true;
                    $messageError = 'Count';
                }
            } else {
                $error = true;
                $messageError = 'asd';
            }
        } else {
            $error = true;
            $messageError = 'asd';
        }

        //********************************************* */
        $file_requisitos = [];
        //$year = Carbon::now()->year;
        $data2   = DB::table('campos_construccion')->where('id_municipio', $data[0]->id_municipio)->where('active', true)->whereIn('tramite_relacionado', [$data[0]->tramite_relacionado, 0])->whereIn ('type', ['file', 'multifile'])->where('type', '!=', 'boolean')->get();

        $consulta_requisitos_construccion2 = DB::table('consulta_requisitos_construccion')->where('folio', $folio)->get();

        $tramite = DB::table('tipo_tramites_construccion')->where('id', $data[0]->tramite_relacionado)->where('id_municipio', $data[0]->id_municipio)->get();

        /*$tipo_tramite = explode('-', $tramite[0]->folio_interno);

        $latestRecord = DB::table('consulta_requisitos_construccion')
            ->where('folio_interno', $tipo_tramite[0])
            ->where('id_municipio', $data[0]->id_municipio)
            ->orderBy('id', 'desc')
            ->first();

        if (!is_null($latestRecord)) {
            $folio_internos = explode('-', $latestRecord->folio_interno);
            $folio_internos2 = explode('/', $folio_internos[1]);
            $folio_interno  = $folio_internos[0] . "-" . ($folio_internos2[0] + 1) ."/" . $year;
        } else {
            // split folio_interno on dash
            $folio_internos = explode('-', $tramite[0]->folio_interno);
            $folio_internos2 = explode('/', $folio_internos[1]);
            $folio_interno  = $folio_internos[0] . "-" . ($folio_internos2[0] + 1) ."/" . $year;
        }

        //$folio_interno = $folio_interno . "/". $year;

        if ($consulta_requisitos_construccion2[0]->folio_interno == '') {
            $updatedRows = DB::table('consulta_requisitos_construccion')
                ->where('folio', $folio)
                ->update(['folio_interno' => $folio_interno]);
        }*/
        $default_preguntas = $tramite[0]->default_preguntas;
        error_log($default_preguntas . " default");
        foreach ($data2 as $value) {
            if ($default_preguntas == 1) {
                $cumpleTodo = true;
                $cumpleUna = false;
                if ($value->destino_construccion != '[]') {
                    $respuestas = DB::table('consulta_requisitos_construccion')->where('folio', $folio)->get();
                    $clean = str_replace("[", "", $value->destino_construccion);
                    $clean = str_replace("]", "", $clean);
                    $clean = str_replace('"', "", $clean);
                    $array_temp = explode(",", $clean);
                    foreach ($array_temp as $value_array) {
                        switch ($value_array) {
                            case 'Habitacional':
                                if ($respuestas[0]->superficie_habitacional <= 0) {
                                    $cumpleTodo = false;
                                } else {
                                    $cumpleUna = true;
                                }
                                break;
                            case 'Comercial servicios':
                                if ($respuestas[0]->superficie_comercial_servicios <= 0) {
                                    $cumpleTodo = false;
                                } else {
                                    $cumpleUna = true;
                                }
                                break;
                            case 'Industrial':
                                if ($respuestas[0]->superficie_industrial <= 0) {
                                    $cumpleTodo = false;
                                } else {
                                    $cumpleUna = true;
                                }
                                break;
                            case 'Alojamiento temporal':
                                if ($respuestas[0]->superficie_turistico <= 0) {
                                    $cumpleTodo = false;
                                } else {
                                    $cumpleUna = true;
                                }
                                break;
                            case 'Equipamiento':
                                if ($respuestas[0]->superficie_equipamiento <= 0) {
                                    $cumpleTodo = false;
                                } else {
                                    $cumpleUna = true;
                                }
                                break;
                            case 'Espacios verdes':
                                if ($respuestas[0]->superficie_espacios_verdes <= 0) {
                                    $cumpleTodo = false;
                                } else {
                                    $cumpleUna = true;
                                }
                                break;
                            case 'Otro':
                                if ($respuestas[0]->superficie_otro <= 0) {
                                    $cumpleTodo = false;
                                } else {
                                    $cumpleUna = true;
                                }
                                break;
                        }
                    }
                }
                if ($value->construccion_uso_destino != '[]') {
                    $respuestas = DB::table('consulta_requisitos_construccion')->where('folio', $folio)->get();
                    $clean = str_replace("[", "", $value->construccion_uso_destino);
                    $clean = str_replace("]", "", $clean);
                    $clean = str_replace('"', "", $clean);
                    $array_temp = explode(",", $clean);
                    $clean2 = str_replace("[", "", $value->construccion_uso);
                    $clean2 = str_replace("]", "", $clean2);
                    $clean2 = str_replace('"', "", $clean2);
                    $array_temp2 = explode(",", $clean2);
                    $clean3 = str_replace("[", "", $value->construccion_uso_metros);
                    $clean3 = str_replace("]", "", $clean3);
                    $clean3 = str_replace('"', "", $clean3);
                    $array_temp3 = explode(",", $clean3);
                    $count = 0;
                    foreach ($array_temp as $value_array) {
                        switch ($value_array) {
                            case 'Habitacional':
                                if ((int) $respuestas[0]->superficie_habitacional != 0) {
                                    if ($array_temp2[$count] == '<=') {
                                        if ($this->operacion((int) $respuestas[0]->superficie_habitacional, '<=', (int) $array_temp3[$count])) {
                                            $cumpleUna = true;
                                        } else {
                                            $cumpleTodo = false;
                                        }
                                    } else {
                                        if ($this->operacion((int) $respuestas[0]->superficie_habitacional, '>=', (int) $array_temp3[$count])) {
                                            $cumpleUna = true;
                                        } else {
                                            $cumpleTodo = false;
                                        }
                                    }
                                }
                                break;
                            case 'Comercial servicios':
                                if ((int) $respuestas[0]->superficie_comercial_servicios != 0) {
                                    if ($array_temp2[$count] == '<=') {
                                        if ($this->operacion((int) $respuestas[0]->superficie_comercial_servicios, '<=', (int) $array_temp3[$count])) {
                                            $cumpleUna = true;
                                        } else {
                                            $cumpleTodo = false;
                                        }
                                    } else {
                                        if ($this->operacion((int) $respuestas[0]->superficie_comercial_servicios, '>=', (int) $array_temp3[$count])) {
                                            $cumpleUna = true;
                                        } else {
                                            $cumpleTodo = false;
                                        }
                                    }
                                }
                                break;
                            case 'Industrial':
                                if ((int) $respuestas[0]->superficie_industrial != 0) {
                                    if ($array_temp2[$count] == '<=') {
                                        if ($this->operacion((int) $respuestas[0]->superficie_industrial, '<=', (int) $array_temp3[$count])) {
                                            $cumpleUna = true;
                                        } else {
                                            $cumpleTodo = false;
                                        }
                                    } else {
                                        if ($this->operacion((int) $respuestas[0]->superficie_industrial, '>=', (int) $array_temp3[$count])) {
                                            $cumpleUna = true;
                                        } else {
                                            $cumpleTodo = false;
                                        }
                                    }
                                }
                                break;
                            case 'Alojamiento temporal':
                                if ((int) $respuestas[0]->superficie_turistico != 0) {
                                    if ($array_temp2[$count] == '<=') {
                                        if ($this->operacion((int) $respuestas[0]->superficie_turistico, '<=', (int) $array_temp3[$count])) {
                                            $cumpleUna = true;
                                        } else {
                                            $cumpleTodo = false;
                                        }
                                    } else {
                                        if ($this->operacion((int) $respuestas[0]->superficie_turistico, '>=', (int) $array_temp3[$count])) {
                                            $cumpleUna = true;
                                        } else {
                                            $cumpleTodo = false;
                                        }
                                    }
                                }
                                break;
                            case 'Equipamiento':
                                if ((int) $respuestas[0]->superficie_equipamiento != 0) {
                                    if ($array_temp2[$count] == '<=') {
                                        if ($this->operacion((int) $respuestas[0]->superficie_equipamiento, '<=', (int) $array_temp3[$count])) {
                                            $cumpleUna = true;
                                        } else {
                                            $cumpleTodo = false;
                                        }
                                    } else {
                                        if ($this->operacion((int) $respuestas[0]->superficie_equipamiento, '>=', (int) $array_temp3[$count])) {
                                            $cumpleUna = true;
                                        } else {
                                            $cumpleTodo = false;
                                        }
                                    }
                                }
                                break;
                            case 'Espacios verdes':
                                if ((int) $respuestas[0]->superficie_espacios_verdes != 0) {
                                    if ($array_temp2[$count] == '<=') {
                                        if ($this->operacion((int) $respuestas[0]->superficie_espacios_verdes, '<=', (int) $array_temp3[$count])) {
                                            $cumpleUna = true;
                                        } else {
                                            $cumpleTodo = false;
                                        }
                                    } else {
                                        if ($this->operacion((int) $respuestas[0]->superficie_espacios_verdes, '>=', (int) $array_temp3[$count])) {
                                            $cumpleUna = true;
                                        } else {
                                            $cumpleTodo = false;
                                        }
                                    }
                                }
                                break;
                            case 'Otro':
                                if ((int) $respuestas[0]->superficie_otro != 0) {
                                    if ($array_temp2[$count] == '<=') {
                                        if ($this->operacion((int) $respuestas[0]->superficie_otro, '<=', (int) $array_temp3[$count])) {
                                            $cumpleUna = true;
                                        } else {
                                            $cumpleTodo = false;
                                        }
                                    } else {
                                        if ($this->operacion((int) $respuestas[0]->superficie_otro, '>=', (int) $array_temp3[$count])) {
                                            $cumpleUna = true;
                                        } else {
                                            $cumpleTodo = false;
                                        }
                                    }
                                }
                                break;
                        }
                        $count++;
                    }
                }

                if ($value->construccion_total_metros_condicion != '0') {
                    $respuestas = DB::table('consulta_requisitos_construccion')->where('folio', $folio)->get();
                    $total_mts = $respuestas[0]->superficie_habitacional + $respuestas[0]->superficie_comercial_servicios + $respuestas[0]->superficie_industrial + $respuestas[0]->superficie_turistico + $respuestas[0]->superficie_equipamiento + $respuestas[0]->superficie_espacios_verdes + $respuestas[0]->superficie_otro;

                    if ($value->construccion_total_metros_condicion == '>=') {
                        if ($this->operacion((int) $total_mts, '>=', (int) $value->construccion_total_metros_value)) {
                            $cumpleUna = true;
                        } else {
                            $cumpleTodo = false;
                        }
                    } else {
                        if ($this->operacion((int) $total_mts, '<=', (int) $value->construccion_total_metros_value)) {
                            $cumpleUna = true;
                        } else {
                            $cumpleTodo = false;
                        }
                    }
                }

                if ($value->demolicion_metros_condicion != '0') {
                    $respuestas = DB::table('consulta_requisitos_construccion')->where('folio', $folio)->get();
                    if ($value->demolicion_metros_condicion == '>=') {
                        if ($this->operacion((int) $respuestas[0]->mdemolicion, '>=', (int) $value->demolicion_metros_value)) {
                            $cumpleUna = true;
                        } else {
                            $cumpleTodo = false;
                        }
                    } else {
                        if ($this->operacion((int) $respuestas[0]->mdemolicion, '<=', (int) $value->demolicion_metros_value)) {
                            $cumpleUna = true;
                        } else {
                            $cumpleTodo = false;
                        }
                    }
                }

                if ($value->viviendas_construccion_condicion != '0') {
                    $respuestas = DB::table('consulta_requisitos_construccion')->where('folio', $folio)->get();
                    if ($value->viviendas_construccion_condicion == '>=') {
                        if ($this->operacion((int) $respuestas[0]->numero_viviendas, '>=', (int) $value->viviendas_construccion_value)) {
                            $cumpleUna = true;
                        } else {
                            $cumpleTodo = false;
                        }
                    } else {
                        if ($this->operacion((int) $respuestas[0]->numero_viviendas, '<=', (int) $value->viviendas_construccion_value)) {
                            $cumpleUna = true;
                        } else {
                            $cumpleTodo = false;
                        }
                    }
                }

                if ($value->nivel_nuevo_construccion_condicion != '0') {
                    $respuestas = DB::table('consulta_requisitos_construccion')->where('folio', $folio)->get();
                    if ($value->nivel_nuevo_construccion_condicion == '>=') {
                        if ($this->operacion((int) $respuestas[0]->niveles_nuevos_construir, '>=', (int) $value->nivel_nuevo_construccion_value)) {
                            $cumpleUna = true;
                        } else {
                            $cumpleTodo = false;
                        }
                    } else {
                        if ($this->operacion((int) $respuestas[0]->niveles_nuevos_construir, '<=', (int) $value->nivel_nuevo_construccion_value)) {
                            $cumpleUna = true;
                        } else {
                            $cumpleTodo = false;
                        }
                    }
                }

                if ($value->sotano_nuevo_construccion_condicion != '0') {
                    $respuestas = DB::table('consulta_requisitos_construccion')->where('folio', $folio)->get();
                    if ($value->sotano_nuevo_construccion_condicion == '>=') {
                        if ($this->operacion((int) $respuestas[0]->sotano, '>=', (int) $value->sotano_nuevo_construccion_value)) {
                            $cumpleUna = true;
                        } else {
                            $cumpleTodo = false;
                        }
                    } else {
                        if ($this->operacion((int) $respuestas[0]->sotano, '<=', (int) $value->sotano_nuevo_construccion_value)) {
                            $cumpleUna = true;
                        } else {
                            $cumpleTodo = false;
                        }
                    }
                }

                if($value->tramite_relacionado == 0 && ($value->pregunta_si_o_no_condicion != '0' && $value->pregunta_si_o_no_condicion != '')){
                    $temp = DB::table('campos_construccion')->select('name')->where('type', 'boolean')->where('description', $value->pregunta_si_o_no_condicion)->get();

                    $temp2 = DB::table('respuestas_construccion')->select('value')->where('id_tramite', $id)->where('name', $temp[0]->name)->get();

                    error_log($value->description);

                    error_log($temp2[0]->value);

                    error_log($value->pregunta_si_o_no_value);

                    if ($temp2[0]->value == 'true' && $value->pregunta_si_o_no_value == 1) {
                        array_push($file_requisitos, $value);
                    }else if($temp2[0]->value == 'false' && $value->pregunta_si_o_no_value == 0){
                        array_push($file_requisitos, $value);
                    }
                }else if ($value->pregunta_si_o_no_condicion != '0' && $value->pregunta_si_o_no_condicion != '') {

                    $temp = DB::table('campos_construccion')->select('name')->where('type', 'boolean')->where('description', $value->pregunta_si_o_no_condicion)->get();

                    $temp2 = DB::table('respuestas_construccion')->select('value')->where('id_tramite', $id)->where('name', $temp[0]->name)->get();

                    if ($temp2[0]->value == 'true' && $value->pregunta_si_o_no_value == 1) {
                        $cumpleUna = true;
                    } else {
                        $cumpleTodo = false;
                    }
                }

                if ($value->actividad_condicion != '0') {
                    $respuestas = DB::table('consulta_requisitos_construccion')->where('folio', $folio)->get();

                    if ($value->actividad_condicion == '>=') {
                        if ($respuestas[0]->superficie_propiedad <= $value->actividad_metros) {
                            $cumpleTodo = false;
                        } else {
                            $cumpleUna = true;
                        }
                    } else {
                        if ($respuestas[0]->superficie_propiedad >= $value->actividad_metros) {
                            $cumpleTodo = false;
                        } else {
                            $cumpleUna = true;
                        }
                    }
                }

                if($value->tramite_relacionado != 0){
                    if ($value->todasCondiciones == 'true') {
                        if ($cumpleTodo) {
                            array_push($file_requisitos, $value);
                        }
                    } else {
                        if ($cumpleUna) {
                            array_push($file_requisitos, $value);
                        }
                    }
                }else{
                    array_push($file_requisitos, $value);
                }


            } else {
                if ($value->pregunta_si_o_no_condicion != '0' && $value->pregunta_si_o_no_condicion != '') {

                    $temp = DB::table('campos_construccion')->select('name')->where('type', 'boolean')->where('description', $value->pregunta_si_o_no_condicion)->get();

                    $temp2 = DB::table('respuestas_construccion')->select('value')->where('id_tramite', $id)->where('name', $temp[0]->name)->get();

                    if ($temp2[0]->value == 'true' && $value->pregunta_si_o_no_value == 1) {
                        array_push($file_requisitos, $value);
                    }else if($temp2[0]->value == 'false' && $value->pregunta_si_o_no_value == 0){
                        array_push($file_requisitos, $value);
                    }
                }else{
                    array_push($file_requisitos, $value);
                }

            }
        }
        if (count($data) == 0) {
            return $this->errorResponse('No existe en la base de datos la consulta de requisitos', 404);
        }

        $id_municipio = $data[0]->id_municipio;
        $data3 = DB::table('municipios_construccion')
            ->where('id', $id_municipio)
            ->get();
        $respuestasConsulta = DB::table('campos_construccion')->join('respuestas_construccion', 'campos_construccion.name', 'respuestas_construccion.name')->select('campos_construccion.*', 'respuestas_construccion.value')->where('respuestas_construccion.id_tramite', $id)->whereIn('campos_construccion.tipo_tramite', ['consulta', 'consulta_requisitos'])->get();

        $imagen = $data3[0]->image == '' ? 'https://cuernavaca.visorurbano.com/assets/images/logo_cuernavaca.svg' : $data3[0]->image;
        $apoyo_economico = '';

        $qrCode = $qrCode->writeString(); //Salida en formato de texto
        $qrCode = base64_encode($qrCode);
        $logo = $this->getImageConstruccion($folio);
        if (isset($features)) {
            $dataCon = array('img' => $img, 'features' => $features, 'data' => $data[0], 'municipio' => $data3[0], 'respuestas' => $file_requisitos, 'qr' => $qrCode, 'apoyo' => $apoyo_economico, 'image' => $imagen, 'logo' => $logo, 'rc' => $respuestasConsulta, 'tramite' => $tramite,'folio_interno'=> $folio_interno);

        } else {
            /*$dataCon = array('img' => $img, 'features' => null, 'data' => $data[0], 'municipio' => $data3[0], 'respuestas' => $file_requisitos, 'qr' => $qrCode, 'apoyo' => $apoyo_economico, 'image' => $imagen, 'logo' => $logo, 'rc' => $respuestasConsulta, 'tramite' => $tramite,'folio_interno'=>$folio_interno);*/
            $dataCon = array('img' => $img, 'features' => null, 'data' => $data[0], 'municipio' => $data3[0], 'respuestas' => $file_requisitos, 'qr' => $qrCode, 'apoyo' => $apoyo_economico, 'image' => $imagen, 'logo' => $logo, 'rc' => $respuestasConsulta, 'tramite' => $tramite);

        }

        $pdf = PDF::loadView('requisitosConstruccion', (array) $dataCon)->setWarnings(false);
        $pdf->setOptions(
            [
                'isPhpEnabled' => true,
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
            ]
        );
        return $pdf->stream($folio . '.pdf');
    }

    public function operacion($valor_uno, $operador, $valor_dos)
    {
        switch ($operador) {
            case '>=':
                if ($valor_uno >= $valor_dos) {
                    return true;
                }
                return false;
                break;

            case '<=':
                if ($valor_uno <= $valor_dos) {
                    return true;
                }
                return false;
                break;

            case '0':
                return true;
                break;

        }
    }
}
