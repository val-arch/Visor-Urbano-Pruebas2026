<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use App\Traits\ApiResponser;
use App\Models\Role;
use App\Models\UserRole;
use App\Models\FirmaGiro;
use App\Models\FirmaConstruccion;
use App\Models\Tramite;
use App\Models\TramiteConstruccion;
use App\Models\ConsultaRequisito;
use App\Models\ConsultaRequisitoConstruccion;
use App\User;
use Illuminate\Support\Facades\Auth;




class FirmaElectronicaController extends Controller
{
    use ApiResponser;

    public function index(Request $request)
    {
        try {
            $params = $_REQUEST;
            $id_user = Auth::user()->id;
            $role = Auth::user()->roles[0]->id;
            $dataRequest = $request->all();
            $params['password'] = trim($params['password']);
            $params['cadena'] = trim($params['cadena']);
            $params['cargo'] = $role; // trim($params['cargo']);
            $params['curp'] = $dataRequest['curp'];
            $params['id_tramite'] = $dataRequest['id_tramite'];
            $params['parte_tramite'] = $dataRequest['parte_tramite'];
            $folio = ConsultaRequisito::find($dataRequest['id_tramite'])->folio;
            // return $folio;

            if (empty($dataRequest['curp'])) {
                return $this->errorResponse('password es un parametro requerido.', 422);
            }
            if (empty($dataRequest['id_tramite'])) {
                return $this->errorResponse('Tramite es un parametro requerido.', 422);
            }
            if (empty($dataRequest['cadena'])) {
                return $this->errorResponse('cadena es un parametro requerido.', 422);
            }



            // Valida Archivos
            $max_size = 5000;

            if (empty($_FILES['file_cer'])) {
                return $this->errorResponse('El archivo cer es requerido.', 422);
            } else {
                $file_cer = $_FILES['file_cer'];
                $file_cer_name = $file_cer['name'];
                $ext_cer = strtolower(pathinfo($file_cer_name, PATHINFO_EXTENSION));
                $file_cer_size = $file_cer["size"] / 1024;
                $allowedExts_cer = array("cer");


                if (!in_array($ext_cer, $allowedExts_cer)) {
                    return $this->errorResponse('El archivo cer no tiene una extensión permitida.', 422);
                }
                if ($file_cer_size > $max_size) {
                    return $this->errorResponse('El archivo cer no tiene un tamaño permitido.', 422);
                }
            }
            if (empty($_FILES['file_key'])) {
                return $this->errorResponse('El archivo key requerido.', 422);
            } else {
                $file_key = $_FILES['file_key'];
                $file_key_name = $file_key['name'];
                $ext_key = strtolower(pathinfo($file_key_name, PATHINFO_EXTENSION));
                $file_key_size = $file_key["size"] / 1024;
                $allowedExts_key = array("key");

                if (!in_array($ext_key, $allowedExts_key)) {

                    return $this->errorResponse('El archivo key no tiene una extensión permitida.', 422);
                }
                if ($file_key_size > $max_size) {

                    return $this->errorResponse('El archivo key no tiene un tamaño permitido.', 422);
                }
            }


            //Sube Archivo CER y KEY
            //$carpeta = '/usr/share/nginx/html/firma_construccion_pruebas/'.$params['curp'];
            $carpeta = 'firma_licencias_giro/' . $params['id_tramite'];

            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777, true);
            }

            $loclizacion_cer = $carpeta . "/cer_file." . $ext_cer;
            move_uploaded_file($file_cer["tmp_name"], $loclizacion_cer);

            $loclizacion_key = $carpeta . "/key_file." . $ext_key;
            move_uploaded_file($file_key["tmp_name"], $loclizacion_key);


            $response =  new \stdClass;
            $response->code = 200;


            $direct = $carpeta;

            //Crea Archivo Con la Cadena a Firmar
            $ruta_cadena = $direct . '/cadena.txt';

            $cadena = fopen($ruta_cadena, 'w+');
            $cadena = fwrite($cadena, $params['cadena']);

            //Crea Archivo Archivo en Blanco para Guardar el Has BASE_64
            $ruta_sello = $direct . '/sello.txt';
            $sello = fopen($ruta_sello, 'w+');



            //leer CER

            $datos_serial = shell_exec("openssl x509 -inform der -in " . $loclizacion_cer . " -noout -serial");
            $datos_vigencia = shell_exec("openssl x509 -inform der -in " . $loclizacion_cer . " -noout -dates");
            $datos_email = shell_exec("openssl x509 -inform der -in " . $loclizacion_cer . " -noout -email");
            $datos_subject = shell_exec("openssl x509 -inform der -in " . $loclizacion_cer . " -noout -subject");

            $datos_serial = str_replace('serial=', '', $datos_serial);
            $array_subject = explode(',', $datos_subject);

            $curp = '';
            $nombre = '';
            $rfc = '';


            if (isset($array_subject[6])) {
                $curp = str_replace('serialNumber = ', '', $array_subject[6]);
            }
            if (isset($array_subject[5])) {
                $rfc = str_replace('x500UniqueIdentifier = ', '', $array_subject[5]);
            }
            if (isset($array_subject[1])) {
                $nombre = str_replace('name = ', '', $array_subject[1]);
            }


            //Crear Archivo .pem del CER
            $salida_cer = shell_exec("openssl x509 -in " . $loclizacion_cer . " -out " . $loclizacion_cer . ".pem -inform DER -outform PEM");

            //Crear Archivo .pem del KEY
            $salida_key = shell_exec("openssl pkcs8 -inform DER -in " . $loclizacion_key . " -out " . $loclizacion_key . ".pem -passin pass:" . $params['password'] . " 2>&1");

            //Valida password del KEY
            if (!empty($salida_key)) {
                if (unlink($loclizacion_cer) && unlink($loclizacion_key) && unlink($loclizacion_cer . ".pem") && unlink($loclizacion_key . ".pem") && unlink($ruta_cadena) && unlink($ruta_sello)) {
                    //   if(rmdir($carpeta)){
                    return $this->errorResponse('Contraseña incorrecta.', 409);
                    // }
                }
                return $this->errorResponse('Contraseña incorrecta.', 409);
            }
            $rfcCadena = explode('|+|', explode('|RFC|=|', $params['cadena'])[1]);
            $curpCadena = explode('|+|', explode('|CURP|=|', $params['cadena'])[1]);
            // $validacion = (trim($rfcCadena[0]) == trim($rfc) && trim($curpCadena[0]) == trim($curp));
            $validacion = (trim($curpCadena[0]) == trim($curp));

            // if($params['tipo_persona'] == 'M'){
            //     $validacion = (trim($rfcCadena[0]) == trim($rfc));
            // }

            // if($params['cargo'] == 0){
            //     $validacion1 = $params['cargo'] != 0;
            // }else{
            //    /* if(strpos($_SERVER['HTTP_REFERER'],'adjuntar_archivos_ciudadano') >= 0){
            //         $validacion1 = 1;
            //     }else{
            //         $validacion1 = $params['cargo'] != 1;
            //     }*/
            // }

            /*if( $validacion  || $validacion1){*/
            // Firma la cadena del Archivo cadena.txt y la guerda en sello.txt
            $firma = shell_exec('openssl dgst -sha256 ' . $loclizacion_cer . '.pem ' . $ruta_cadena . ' | openssl enc -base64 -A > ' . $direct . '/sello1.txt');

            $hash = '';
            if (empty($firma)) {
                $signature = fopen($direct . '/sello1.txt', 'r');
                $response->signature = stream_get_contents($signature);
                $hash = $response->signature;
                fclose($signature);
            } else {
                return $this->errorResponse('Error al crear el certificado.', 500);
            }

            //Eliminacion de archivos guardados
            foreach (glob($carpeta . "/*") as $archivos_carpeta) {
                unlink($archivos_carpeta);
            }
            //rmdir($carpeta);

            if (!empty($hash)) {
                $data = array(
                    'hash' => $hash,
                    'cadena' => $params['cadena'],
                    'numero_serie' => $datos_serial,
                    'nombre' => $nombre,
                    'cargo' => '',
                    'curp' => $curp,
                    'rfc' => $rfc,
                    'correo' => $datos_email,
                    'vigencia' => $datos_vigencia,
                    'fecha' => date('Y-m-d')
                );

                $firmaGiro = new FirmaGiro();
                $firmaGiro->id_tramite = $dataRequest['id_tramite'];
                $firmaGiro->id_usuario = $id_user;
                $firmaGiro->rol = $role;
                $firmaGiro->hash_a_firmar = $dataRequest['cadena'];
                $firmaGiro->hash_firmado = $hash;
                $firmaGiro->response = json_encode($data);
                $firmaGiro->save();
                if ($role == 1) {
                    $tramiteE = Tramite::where('folio', $folio)->update(['firma_usuario' => $firmaGiro->id, 'tengo_firma' => 1]);
                }
                return $this->successResponse($data);
                // echo json_encode(array('status'=>200, 'data' => $data));
            } else {
                return $this->errorResponse('Error al crear el certificado.', 500);
            }
            /*  }else{
            return $this->errorResponse('No coinciden los datos con la firma', 500);
        } */
        } catch (Exception $e) {

            echo json_encode(array('status' => $e->getCode(), 'message' => $e->getMessage(), 'line' => $e->getLine()));
        }
    }

    public function firmaConstruccion(Request $request)
    {
        try {
            $params = $_REQUEST;
            $id_user = Auth::user()->id;
            $role = Auth::user()->roles_construccion[0]->id;
            $dataRequest = $request->all();
            $params['password'] = trim($params['password']);
            $params['cadena'] = trim($params['cadena']);
            $params['cargo'] = $role; // trim($params['cargo']);
            $params['curp'] = $dataRequest['curp'];
            $params['id_tramite'] = $dataRequest['id_tramite'];
            $params['parte_tramite'] = $dataRequest['parte_tramite'];
            $folio = ConsultaRequisitoConstruccion::find($dataRequest['id_tramite'])->folio;
            // return $folio;

            if (empty($dataRequest['curp'])) {
                return $this->errorResponse('password es un parametro requerido.', 422);
            }
            if (empty($dataRequest['id_tramite'])) {
                return $this->errorResponse('Tramite es un parametro requerido.', 422);
            }
            if (empty($dataRequest['cadena'])) {
                return $this->errorResponse('cadena es un parametro requerido.', 422);
            }



            // Valida Archivos
            $max_size = 5000;

            if (empty($_FILES['file_cer'])) {
                return $this->errorResponse('El archivo cer es requerido.', 422);
            } else {
                $file_cer = $_FILES['file_cer'];
                $file_cer_name = $file_cer['name'];
                $ext_cer = strtolower(pathinfo($file_cer_name, PATHINFO_EXTENSION));
                $file_cer_size = $file_cer["size"] / 1024;
                $allowedExts_cer = array("cer");


                if (!in_array($ext_cer, $allowedExts_cer)) {
                    return $this->errorResponse('El archivo cer no tiene una extensión permitida.', 422);
                }
                if ($file_cer_size > $max_size) {
                    return $this->errorResponse('El archivo cer no tiene un tamaño permitido.', 422);
                }
            }
            if (empty($_FILES['file_key'])) {
                return $this->errorResponse('El archivo key requerido.', 422);
            } else {
                $file_key = $_FILES['file_key'];
                $file_key_name = $file_key['name'];
                $ext_key = strtolower(pathinfo($file_key_name, PATHINFO_EXTENSION));
                $file_key_size = $file_key["size"] / 1024;
                $allowedExts_key = array("key");

                if (!in_array($ext_key, $allowedExts_key)) {

                    return $this->errorResponse('El archivo key no tiene una extensión permitida.', 422);
                }
                if ($file_key_size > $max_size) {

                    return $this->errorResponse('El archivo key no tiene un tamaño permitido.', 422);
                }
            }


            //Sube Archivo CER y KEY
            //$carpeta = '/usr/share/nginx/html/firma_construccion_pruebas/'.$params['curp'];
            $carpeta = 'firma_licencias_construccion/' . $params['id_tramite'];

            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777, true);
            }

            $loclizacion_cer = $carpeta . "/cer_file." . $ext_cer;
            move_uploaded_file($file_cer["tmp_name"], $loclizacion_cer);

            $loclizacion_key = $carpeta . "/key_file." . $ext_key;
            move_uploaded_file($file_key["tmp_name"], $loclizacion_key);


            $response =  new \stdClass;
            $response->code = 200;


            $direct = $carpeta;

            //Crea Archivo Con la Cadena a Firmar
            $ruta_cadena = $direct . '/cadena.txt';

            $cadena = fopen($ruta_cadena, 'w+');
            $cadena = fwrite($cadena, $params['cadena']);

            //Crea Archivo Archivo en Blanco para Guardar el Has BASE_64
            $ruta_sello = $direct . '/sello.txt';
            $sello = fopen($ruta_sello, 'w+');



            //leer CER

            $datos_serial = shell_exec("openssl x509 -inform der -in " . $loclizacion_cer . " -noout -serial");
            $datos_vigencia = shell_exec("openssl x509 -inform der -in " . $loclizacion_cer . " -noout -dates");
            $datos_email = shell_exec("openssl x509 -inform der -in " . $loclizacion_cer . " -noout -email");
            $datos_subject = shell_exec("openssl x509 -inform der -in " . $loclizacion_cer . " -noout -subject");

            $datos_serial = str_replace('serial=', '', $datos_serial);
            $array_subject = explode(',', $datos_subject);

            $curp = '';
            $nombre = '';
            $rfc = '';


            if (isset($array_subject[6])) {
                $curp = str_replace('serialNumber = ', '', $array_subject[6]);
            }
            if (isset($array_subject[5])) {
                $rfc = str_replace('x500UniqueIdentifier = ', '', $array_subject[5]);
            }
            if (isset($array_subject[1])) {
                $nombre = str_replace('name = ', '', $array_subject[1]);
            }


            //Crear Archivo .pem del CER
            $salida_cer = shell_exec("openssl x509 -in " . $loclizacion_cer . " -out " . $loclizacion_cer . ".pem -inform DER -outform PEM");

            //Crear Archivo .pem del KEY
            $salida_key = shell_exec("openssl pkcs8 -inform DER -in " . $loclizacion_key . " -out " . $loclizacion_key . ".pem -passin pass:" . $params['password'] . " 2>&1");

            //Valida password del KEY
            if (!empty($salida_key)) {
                if (unlink($loclizacion_cer) && unlink($loclizacion_key) && unlink($loclizacion_cer . ".pem") && unlink($loclizacion_key . ".pem") && unlink($ruta_cadena) && unlink($ruta_sello)) {
                    //   if(rmdir($carpeta)){
                    return $this->errorResponse('Contraseña incorrecta.', 409);
                    // }
                }
                return $this->errorResponse('Contraseña incorrecta.', 409);
            }
            $rfcCadena = explode('|+|', explode('|RFC|=|', $params['cadena'])[1]);
            $curpCadena = explode('|+|', explode('|CURP|=|', $params['cadena'])[1]);
            // $validacion = (trim($rfcCadena[0]) == trim($rfc) && trim($curpCadena[0]) == trim($curp));
            $validacion = (trim($curpCadena[0]) == trim($curp));

            // if($params['tipo_persona'] == 'M'){
            //     $validacion = (trim($rfcCadena[0]) == trim($rfc));
            // }

            // if($params['cargo'] == 0){
            //     $validacion1 = $params['cargo'] != 0;
            // }else{
            //    /* if(strpos($_SERVER['HTTP_REFERER'],'adjuntar_archivos_ciudadano') >= 0){
            //         $validacion1 = 1;
            //     }else{
            //         $validacion1 = $params['cargo'] != 1;
            //     }*/
            // }

            /*if( $validacion  || $validacion1){*/
            // Firma la cadena del Archivo cadena.txt y la guerda en sello.txt
            $firma = shell_exec('openssl dgst -sha256 ' . $loclizacion_cer . '.pem ' . $ruta_cadena . ' | openssl enc -base64 -A > ' . $direct . '/sello1.txt');

            $hash = '';
            if (empty($firma)) {
                $signature = fopen($direct . '/sello1.txt', 'r');
                $response->signature = stream_get_contents($signature);
                $hash = $response->signature;
                fclose($signature);
            } else {
                return $this->errorResponse('Error al crear el certificado.', 500);
            }

            //Eliminacion de archivos guardados
            foreach (glob($carpeta . "/*") as $archivos_carpeta) {
                unlink($archivos_carpeta);
            }
            //rmdir($carpeta);

            if (!empty($hash)) {
                $data = array(
                    'hash' => $hash,
                    'cadena' => $params['cadena'],
                    'numero_serie' => $datos_serial,
                    'nombre' => $nombre,
                    'cargo' => '',
                    'curp' => $curp,
                    'rfc' => $rfc,
                    'correo' => $datos_email,
                    'vigencia' => $datos_vigencia,
                    'fecha' => date('Y-m-d')
                );

                $firmaConstruccion = new FirmaConstruccion();
                $firmaConstruccion->id_tramite = $dataRequest['id_tramite'];
                $firmaConstruccion->id_usuario = $id_user;
                $firmaConstruccion->rol = $role;
                $firmaConstruccion->hash_a_firmar = $dataRequest['cadena'];
                $firmaConstruccion->hash_firmado = $hash;
                $firmaConstruccion->response = json_encode($data);
                $firmaConstruccion->save();
                if ($role == 1) {
                    $tramiteE = TramiteConstruccion::where('folio', $folio)->update(['firma_usuario' => $firmaConstruccion->id, 'tengo_firma' => 1]);
                }
                return $this->successResponse($data);
                // echo json_encode(array('status'=>200, 'data' => $data));
            } else {
                return $this->errorResponse('Error al crear el certificado.', 500);
            }
            /*  }else{
            return $this->errorResponse('No coinciden los datos con la firma', 500);
        } */
        } catch (Exception $e) {

            echo json_encode(array('status' => $e->getCode(), 'message' => $e->getMessage(), 'line' => $e->getLine()));
        }
    }
}
