<?php
/** @var \Laravel\Lumen\Routing\Router $router */
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
use App\Models\Municipio;
use App\Models\FichaTecnica;
// use Barryvdh\DomPDF\Facade as PDF;

use App\Mail\Notificacion;
use App\Mail\Planes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

$router->get('/', function () use ($router) {
	return 'Visor Urbano Cuernavaca';
});

$router->group(['middleware' => 'auth', 'prefix' => 'roles'], function () use ($router) {
    $router->get('/', 'RoleController@index');
    $router->get('/getByRoleType/{value}', 'RoleController@index');
    $router->get('/role_user', 'RoleController@indexRole');
    $router->get('/role_user/{value}', 'RoleController@indexRole');
    $router->get('/role_user2', 'RoleController@indexRole2');
    $router->get('/roleMunicipio', 'RoleController@getMyRoleMunicipaly');
    $router->get('/{id}', 'RoleController@show');
    $router->post('/', 'RoleController@store');
    $router->post('/storeByRoleType/{value}', 'RoleController@store');
    $router->put('/{id}', 'RoleController@update');
    $router->delete('/{id}', 'RoleController@destroy');
});
$router->group(['middleware' => 'auth', 'prefix' => 'sub_roles'], function () use ($router) {
    $router->get('/', 'SubRoleController@index');
    $router->get('/municipio', 'SubRoleController@indexbyMunucipio');
    $router->get('/{id}', 'SubRoleController@show');
    $router->post('/', 'SubRoleController@store');
    $router->put('/{id}', 'SubRoleController@update');
    $router->delete('/{id}', 'SubRoleController@destroy');
});

$router->group(['middleware' => 'auth', 'prefix' => 'usuarios'], function () use ($router) {
    $router->get('/', 'UsuarioController@index');
    $router->get('/get', 'UsuarioController@index2');
    $router->get('/getByTypeUser', 'UsuarioController@index3');
    $router->get('/getByRole', 'UsuarioController@index4');
    $router->get('/{id}', 'UsuarioController@show');
    $router->post('/', 'UsuarioController@store');
    $router->post('/storeByUserType', 'UsuarioController@storeConstruccion');
    $router->put('/{id}', 'UsuarioController@update');
    $router->put('/{id}/municipio', 'UsuarioController@updateMunicipio');
    $router->delete('/{id}', 'UsuarioController@destroy');
    $router->get('role/{id}', 'UsuarioController@roleAsiganado');
    $router->get('role/{id}', 'UsuarioController@asignarRole');
    $router->get('role/{id}', 'UsuarioController@quitarRole');
});

$router->group(['middleware' => 'auth', 'prefix' => 'control_roles'], function () use ($router) {
    $router->get('/{id}', 'UsuarioController@roleAsiganado');
    $router->post('/asignar/{id}', 'UsuarioController@asignarRole');
    $router->post('/asignarConstruccion/{id}', 'UsuarioController@asignarRoleConstruccion');
    $router->delete('/quitarConstruccion/{id}', 'UsuarioController@quitarRoleConstruccion');
    $router->delete('/quitar/{id}', 'UsuarioController@quitarRole');
});
$router->group(['middleware' => 'auth', 'prefix' => 'control_suboles'], function () use ($router) {
    $router->post('/asignar/{id}', 'UsuarioController@asignarSubRole');
    $router->post('/quitar', 'UsuarioController@quitarSubRole');
});
$router->group(['middleware' => 'auth', 'prefix' => 'municipios'], function () use ($router) {
    $router->get('/', 'MunicipioController@index');
    $router->get('/getByUserType/{value}', 'MunicipioController@index');
    $router->get('/auth', 'MunicipioController@showMunicipio');
    $router->get('/getGirosAdmin', 'GiroApagadoController@getGirosAdmin');
    $router->get('/firmate', 'MunicipioController@getFirmas');
    $router->get('/{id}', 'MunicipioController@show');
    $router->post('/firmate/{order}', 'MunicipioController@firmateLicencia');
    $router->delete('/firmate/{id}', 'MunicipioController@deleteFirma');
    $router->post('/', 'MunicipioController@store');
    $router->post('/{id}/image', 'MunicipioController@image');
    $router->post('/{id}/firma', 'MunicipioController@firma');
    $router->put('/{id}', 'MunicipioController@update');
    $router->delete('/{id}', 'MunicipioController@destroy');
});
$router->group(['middleware' => 'auth', 'prefix' => 'campos'], function () use ($router) {
    $router->get('/', 'CampoController@index');
    $router->get('/getCampos/{folio}', 'CampoController@getCampos');
    $router->get('/getCampos/{folio}/{id}', 'CampoController@getCampos');
    $router->get('/getCamposRefrendo/{folio}', 'CampoController@getCamposRefrendos');
    $router->get('/{id}', 'CampoController@show');
    $router->post('/', 'CampoController@store');
    $router->put('/{id}', 'CampoController@update');
    $router->delete('/{id}', 'CampoController@destroy');
    $router->post('/requisitoChange', 'CampoController@agregarRequisito');
    $router->get('/{id}/campo_requisito', 'CampoController@camposRequisitos');
});
$router->group(['middleware' => 'auth', 'prefix' => 'giros_apagados'], function () use ($router) {
    $router->post('/', 'GiroApagadoController@store');
    $router->post('/storeStatus/{status}', 'GiroApagadoController@storeStatus');
    $router->post('/cedula/{status}', 'GiroApagadoController@storeStatusCedula');
    $router->delete('/{id}', 'GiroApagadoController@destroy');
    $router->get('/getEncendidos', 'GiroApagadoController@getGirosOn');
    $router->get('/getAllGiros', 'GiroApagadoController@getGirosAll');
    $router->get('/getApagador', 'GiroApagadoController@getGirosOff');
});
$router->group(['middleware' => 'auth', 'prefix' => 'cedula_giro'], function () use ($router) {
    $router->post('/', 'GiroApagadoController@storeCedula');
    $router->delete('/{id}', 'GiroApagadoController@destroyCedula');
});
$router->group(['middleware' => 'auth', 'prefix' => 'giro_impacto'], function () use ($router) {
    $router->post('/', 'GiroApagadoController@storeImpacto');
    $router->post('/update', 'GiroApagadoController@updateImpacto');
});
$router->group(['prefix' => 'giros_public'], function () use ($router) {
    $router->get('/getEncendidos', 'GiroApagadoController@getGirosOnPublic');
    $router->get('/getAll', 'GiroApagadoController@getGirosAllMapa');

});
$router->group(['prefix' => 'boletin'], function () use ($router) {
    $router->get('/', 'LicenciasController@getBoletin');
});
$router->group(['prefix' => 'campos_public'], function () use ($router) {
    $router->get('/campo_requisito_file/{id_municipio}', 'CampoController@camposRequisitosFile');
    $router->get('/campo_requisito_tramite/{id_municipio}', 'CampoController@camposRequisitosTramite');
    $router->get('/campo_requisito_tramite_construccion/{id_municipio}', 'CampoController@camposRequisitosTramiteConstruccion');
    $router->get('/campo_requisito_tramite_construccion/{id_municipio}/{id}', 'CampoController@camposRequisitosTramiteConstruccion2');
    $router->get('/get_pregunta_si_o_no/{id_municipio}/{id}', 'CampoController@camposRequisitosTramiteConstruccion3');
});
$router->group(['prefix' => 'consulta_requisitos'], function () use ($router) {
    $router->get('/requisitos/{folio}/{id}', 'ConsultaRequisitosController@consultaRequisitosPdf');
    $router->post('/requisitos', 'ConsultaRequisitosController@consultaRequisitos2');
});
$router->group(['middleware' => 'auth', 'prefix' => 'requisitos'], function () use ($router) {
    $router->post('/encender', 'RequisitoController@store');
    $router->delete('/{id}/apagar', 'RequisitoController@destroy');
    $router->get('/', 'RequisitoController@index');
    $router->get('/{id}', 'RequisitoController@show');
});
$router->group(['middleware' => 'auth', 'prefix' => 'requisitos_validar'], function () use ($router) {
    $router->get('/folio/{folio}', 'RequisitoController@validarFolioRequisito');
    $router->put('/folio/{id}', 'RequisitoController@updateConsultaRequisito');
});
$router->group(['middleware' => 'auth', 'prefix' => 'info-tramite'], function () use ($router) {
    $router->get('/{folio}', 'ConsultaRequisitosController@getInfoTramite');
    $router->get('/tipo/{folio}', 'ConsultaRequisitosController@getInfoTramite');
    $router->get('refrendo/{folio}', 'ConsultaRequisitosController@getInfoTramiteRefrendo');
});

$router->group(['middleware' => 'auth', 'prefix' => 'tramites'], function () use ($router) {
    $router->post('/ingreso', 'TramiteController@ingreso');
    $router->post('/ingresoRefrendo', 'TramiteController@ingresoRefrendo');
    $router->post('/ordenPago/{id}', 'RevisionController@uploadOrdenPago');
});
$router->group(['middleware' => 'auth', 'prefix' => 'solventacion'], function () use ($router) {
    $router->post('/subir_archivo/{id}', 'NotificacionController@uploadFile');
    $router->post('/comentario/{id}', 'NotificacionController@update');
    $router->get('/getNotificacion/{id}', 'NotificacionController@getNotificacion');
    $router->get('/getFilesSolventacion/{id}', 'NotificacionController@getFilesSolventacion');
    $router->post('/updateFilesSolventacion/{id}', 'NotificacionController@updateFile');
});
$router->group(['middleware' => 'auth', 'prefix' => 'licencias_giro'], function () use ($router) {
    $router->get('/listado', 'TramiteController@getListado');
    $router->get('/listadoDirRev', 'TramiteController@getListadoDirRev');
    $router->get('/getListadoVentanilla', 'TramiteController@getListadoVentanilla');
    $router->get('/getListadoDesechados', 'TramiteController@getListadoDesechados');
    $router->get('/getListadoSolv', 'TramiteController@getListadoDirSolv');
    $router->get('/listado-licencias', 'TramiteController@getListadoLicenciasVisor');
    $router->post('/noFirmaElectronica/{folio}', 'TramiteController@noFirmaElectronica');
    $router->post('/ingresoTramiteContinuar/{folio}', 'TramiteController@ingresoTramiteContinuar');
    $router->post('/upload_file/{folio}', 'UploadController@uploadFile');
    $router->post('/folio_tramite/{name}', 'UploadController@upload');
    $router->post('/folio_tramite_text', 'TextController@guardarText');
    $router->post('/folio_tramite_text_refrendo', 'TextController@guardarTextRefrendo');
    $router->post('/folio_tramite_historico_refrendo', 'TextController@guardarHistoricoRefrendo');
    $router->post('/update-licencias-status/{id}', 'LicenciasController@updateLicenciaStatus');
    $router->post('/update-licencias-status-his/{id}', 'LicenciasController@updateLicenciaStatusHis');
    $router->post('/copy-tramite', 'LicenciasController@copyTramite');
    $router->post('/copy-tramite-historico', 'LicenciasController@copyTramiteHist');
    $router->get('/getInfo/{id}', 'LicenciasController@getInfoLicencia');
    $router->put('/updateHorasSuperficie/{folio}', 'LicenciasController@updateHorasSuperficie');
});

$router->group(['middleware' => 'auth', 'prefix' => 'firmaElectronica'], function () use ($router) {
    $router->post('/', 'FirmaElectronicaController@index');
});

$router->group(['middleware' => 'auth', 'prefix' => 'firmaElectronicaConstruccion'], function () use ($router) {
    $router->post('/', 'FirmaElectronicaController@firmaConstruccion');
});

$router->group(['middleware' => 'auth', 'prefix' => 'revisores'], function () use ($router) {
    $router->post('/insertChat/{folio}', 'ChatRevisoresGController@insertChat');
    $router->get('/{folio}', 'ChatRevisoresGController@getChat');
});
$router->group(['prefix' => 'revision', 'middleware' =>  ['auth']],  function () use ($router) {
    $router->get('/{folio}', 'RevisionController@getResolucionInfo');
    $router->get('refrendo/{folio}', 'RevisionController@getResolucionInfoRefrendo');
    $router->post('update/{folio}', 'RevisionController@update');
    $router->post('updateDir/{folio}', 'RevisionController@updateDir');
    $router->post('uploadFiles/{folio}', 'RevisionController@uploadFiles');
    $router->post('uploadFilesHist/{folio}', 'RevisionController@uploadFilesHis');
    $router->post('emitir/{folio}', 'RevisionController@emitirLicencia');
});
$router->group(['prefix' => 'task', 'middleware' =>  ['auth','role:admin|director']],  function () use ($router) {
    $router->post('/adminUser', 'UsuarioController@asignarRoleMunicipio');
    $router->post('/test', 'UsuarioController@test');
});
$router->group(['middleware' => 'auth'],  function () use ($router) {
   $router->post('/buscarUsuario', 'UsuarioController@buscar');
});
$router->post('identity/signin', 'IdentityController@signin');
$router->get('identity/test', 'IdentityController@test');
$router->post('identity/agregarUsuario', 'IdentityController@register');

// $router->get('/notificacionPrueba/{mail}', function ($mail){
//     // for ($i=0; $i < 5; $i++) {
//         $d =  Mail::to($mail.'.com')->send(new Planes(1));

//         if( count(Mail::failures()) > 0 ) {

//             echo "There was one or more failures. They were: <br />";

//             foreach(Mail::failures() as $email_address) {
//                 echo " - $email_address <br />";
//              }

//          } else {
//              echo "No errors, all sent successfully!";
//          }
//          sleep(3);

//     // }
// });

// $router->get('notificacion-planes','MailPlanController@index');
$router->get('/comercial/{url}/{imagen}/{municipio}/{area}/{comercios}', 'ComercialController@index');

$router->get('/acuseNoFirmado/{folio}', 'FormatosGiroController@acuseNoFirmado');
$router->get('/acuseNoFirmadoConstruccion/{folio}', 'FormatosConstruccionController@acuseNoFirmado');
$router->get('/acuseFirmado/{folio}', 'FormatosGiroController@acuseFirmado');
$router->get('/acuseFirmadoConstruccion/{folio}', 'FormatosConstruccionController@acuseFirmado');
$router->get('/cartaResponsiva/{folio}', 'FormatosGiroController@cartaResponsiva');
$router->get('/cartaResponsivaConstruccion/{folio}', 'FormatosConstruccionController@cartaResponsiva');
$router->get('/aperturaProvisional/{folio}', 'FormatosGiroController@aperturaProvisional');
$router->get('/acuseVentanilla/{folio}', 'FormatosGiroController@acuseVentanilla');
$router->get('/acuseVentanillaConstruccion/{folio}', 'FormatosConstruccionController@acuseVentanilla');
$router->get('licenciaGiro/{folio}/{anio}/{numero}', 'FormatosGiroController@licenciaGiroRefrendo');
$router->get('licenciaGiros/{folio}/{anio}/{numero}/{id}/{idLic}', 'FormatosGiroController@licenciaGiroRef');
$router->get('licenciaGiro/{folio}/{anio}/{numero}/{id}', 'FormatosGiroController@licenciaGiroRefrendo');
$router->get('licenciaGiro/{folio}/{anio}/{numero}/{id}/{idLic}', 'FormatosGiroController@licenciaGiroRef');
$router->get('licenciaGiro/{folio}/{anio}', 'FormatosGiroController@licenciaGiro');
$router->get('licenciaGiroHistorico/{folio}/{anio}/{numero}', 'FormatosGiroController@licenciaGiroHistorico');
$router->get('licenciaGiroTest/{id_municipio}', 'FormatosGiroController@licenciaGiroTest');
$router->get('/licencias', 'LicenciasController@getLicencias');
$router->get('detalle/licencia/{folio}', 'LicenciasController@getLicencia');

$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->post('/cambiarContrasena', 'PasswordController@cambiarContrasena');
});

$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->get('/listadoNotificaciones', 'NotificacionController@getNotificaciones');
    $router->get('/updateNotificacion/{id}', 'NotificacionController@updateNotificacion');
    $router->get('getFileTipo/{id}', 'HistoricoLicenciaController@getFileTipo');
});

$router->group(['middleware' => ['auth','role:director|revisor']],  function () use ($router) {
    $router->post('import-historico-licencia', 'HistoricoLicenciaController@importExcel');
    $router->post('export-historico-licencia', 'HistoricoLicenciaController@export');
    $router->post('export-licencia', 'HistoricoLicenciaController@exportVisor');
    $router->post('import-historico-licencia2', 'HistoricoLicenciaController@importapForr');
    $router->delete('eliminar', 'HistoricoLicenciaController@eliminarRegistros');
    $router->delete('eliminarFile/{id}', 'HistoricoLicenciaController@eliminarFiles');
    $router->put('editar/{id}', 'HistoricoLicenciaController@update');
    $router->delete('eliminar/{id}', 'HistoricoLicenciaController@eliminarRegistro');
    $router->get('get-historico-licencia', 'HistoricoLicenciaController@index');
    $router->get('get-historico-licencia/{id}', 'HistoricoLicenciaController@historico');
    $router->get('get-historico-licencia-info/{id}', 'HistoricoLicenciaController@historicoInfo');
    $router->get('get-historico-files/{id}', 'HistoricoLicenciaController@historicoFiles');
    $router->get('getFile/{id}', 'HistoricoLicenciaController@getFile');
});

$router->post('password/reset-password-request', 'PasswordController@sendPasswordResetEmail');
$router->post('password/change-password', 'PasswordController@passwordResetProcess');

$router->post('correoRole', 'UsuarioController@enviarRoleCorreo');
$router->get('validarRole/{token}/{id}', 'UsuarioController@validarToken');
$router->get('validarRoleConstruccion/{token}/{id}', 'UsuarioController@validarTokenConstruccion');

$router->group(['prefix' => 'generarLicencia', 'middleware' =>  ['auth','role:ventanilla|revisor|director|admin']],  function () use ($router) {
    $router->post('/{folio}', 'FormatosGiroController@generarLicencia');

    $router->post('pagar/{id}', 'LicenciasController@licenciaPagada');
    $router->post('pagarHis/{id}', 'LicenciasController@licenciaPagadaHis');
    $router->post('baja/{id}', 'LicenciasController@licenciaBaja');
    $router->post('pdf_firmado/{id}', 'LicenciasController@subirPdfFirmado');
    $router->post('pdf_firmado_hist/{id}', 'LicenciasController@subirPdfFirmadoHis');
});

$router->group(['prefix' => 'generarRefrendo', 'middleware' =>  ['auth','role:ventanilla|revisor|director|admin']],  function () use ($router) {
    $router->post('/{folio}', 'FormatosGiroController@generarLicenciaRefrendo');
    $router->post('/historico/{folio}', 'FormatosGiroController@generarLicenciaRefrendoHistorico');


});
$router->group(['prefix' => 'graficas', 'middleware' =>  ['auth','role:director|admin']],  function () use ($router) {
    $router->get('advancedpie', 'ReporteController@lineTimeLic');
    $router->get('advancedpie-admin', 'ReporteController@lineTimeLicAdmin');
    $router->get('bar', 'ReporteController@barraAnual');
    $router->post('bar-list', 'ReporteController@barraList');
    $router->get('pie-rev', 'ReporteController@pieRev');
    $router->get('pie2', 'ReporteController@pieBarMunicipiosAll');
    $router->get('bar/{id}', 'ReporteController@pieBarMunicipiosById');
    $router->get('getMostUsedScian', 'ReporteController@getMostUsedScian');
    $router->get('getMostUsedScianbyMunicipio', 'ReporteController@getMostUsedScianbyMunicipio');
    $router->get('getReporteHistorico', 'ReporteController@obtenerRefrendos');
    $router->get('getReporteLicencias', 'ReporteController@obtenerLicenciasVisor');
    $router->get('download-data', 'ReporteController@exportToExcel');

});
$router->group(['prefix' => 'generarLicenciaContinuar', 'middleware' =>  ['auth','role:ventanilla|revisor|director|admin']],  function () use ($router) {
    $router->post('/{folio}', 'FormatosGiroController@generarLicenciaContinuar');
});

$router->get('reporteCompleto', 'ReporteController@reporteCompleto');
$router->get('reporteFichasDescargas', 'ReporteController@reporteCompletoFichas');
// $router->post('ficha_tecnica/{direccion}/{metros}/{coordenadas}/{img}', 'FormatosGiroController@fichaTecnica');
$router->post('ficha_tecnica', 'FormatosGiroController@fichaTecnica');
$router->post('ficha_tecnica_consulta', 'FormatosGiroController@consultaFichaTecnica');
$router->get('ficha_tecnica/{uuid}', 'FormatosGiroController@fichaTecnicaGet');

// Ruta de logs 29/07/2021

$router->group(['middleware' => 'auth', 'prefix' => 'logs','middleware' =>  ['auth','role:director|admin']], function () use ($router) {
    $router->get('/consultar/{tipo}', 'LogController@index');
});
// ruta blog

$router->group(['prefix'=>'blog'],function () use ($router){
    $router->get('','BlogController@index');
    $router->post('','BlogController@store');
    $router->get('{id}','BlogController@show');
    $router->post('{id}','BlogController@update');
    $router->delete('{id}/{password}','BlogController@delete');
    $router->get('user/{key}','BlogController@all');
});

$router->get('/fichas-tecnicas-adm', function() {
    $porDia = \Illuminate\Support\Facades\DB::table('ficha_tecnica')
        ->select(DB::raw('count(*) as fichas,extract(month from created_at) as mes,extract(day from created_at) as dia, extract(year from created_at) as anio'))
        ->where('created_at', '>', '2021-09-13 00:00:00')
        ->groupByRaw('4,2,3')
        ->orderByRaw('anio desc, mes desc, dia desc')
        ->get();
    $total = \Illuminate\Support\Facades\DB::table('ficha_tecnica')
            ->where('created_at', '>', '2021-09-13 00:00:00')
            ->count();

    return ['total'=>$total,'dias'=>$porDia];
});

$router->get('/fichas-tecnicas-municipios',function() {


    $porMunicipio = \Illuminate\Support\Facades\DB::table('ficha_tecnica')->join('municipios','ficha_tecnica.municipio_id','municipios.id')
        ->select(DB::raw('count(*) as fichas, municipios.nombre as municipio'))
        ->where('ficha_tecnica.created_at', '>', '2021-09-13 00:00:00')
        ->groupByRaw('2')
        ->orderBy('fichas','desc')
        ->get();
        $total = 0 ;
        foreach ($porMunicipio as $key) {
            $total += $key->fichas;
        }
        return ['fichas'=>$porMunicipio,'total'=>$total];
});

$router->get('/fichas-tecnicas-adm/{id}', function($id) {
    $porDia = \Illuminate\Support\Facades\DB::table('ficha_tecnica')
        ->select(DB::raw('count(*) as fichas,extract(month from created_at) as mes,extract(day from created_at) as dia, extract(year from created_at) as anio'))
        ->where('created_at', '>', '2021-09-13 00:00:00')
        ->where('municipio_id', '=', $id)
        ->groupByRaw('4,2,3')
        ->orderByRaw('anio desc, mes desc, dia desc')
        ->get();
    $total = \Illuminate\Support\Facades\DB::table('ficha_tecnica')
            ->where('created_at', '>', '2021-09-13 00:00:00')
            ->where('municipio_id', '=', $id)
            ->count();

    return ['total'=>$total,'dias'=>$porDia];
});


$router->get('/key', function() {
    return \Illuminate\Support\Str::random(32);
});
$router->get('/news', function() {
    $data = array("data"=>[
        'href'=>"https://visorurbano.com",
        "src"=>"https://visorurbano.jalisco.gob.mx/assets/images/planes.png",
        "src_xs"=>"https://visorurbano.jalisco.gob.mx/assets/images/planes.png",
    ],"status"=>true);
    return $data;
});


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//                                           APis de licencia de construccion
//                                           Aqui se pondran  todas las api relacionadas con el modulo de contruccion
//
//
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$router->group(['middleware' => 'auth', 'prefix' => 'resolucion_construccion'], function () use ($router) {
    $router->get('/getOrdenResolucion/{id_municipio}/{tipo_tramite}', 'ResolucionConstruccionController@getOrdenResolucion');
    $router->post('/updateOrdenResolucion/{id}/{value}/{tipo_tramite}', 'ResolucionConstruccionController@updateOrdenResolucion');

});



$router->group(['prefix' => 'consulta_requisitosConstruccion'], function () use ($router) {
    $router->get('/requisitos/{folio}', 'ConsultaRequisitosConstruccionController@getConsultaRequisitosConstruccion');
    $router->get('/requisitos/{folio}/{id}', 'ConsultaRequisitosConstruccionController@consultaRequisitosPdfConstruccion');
    $router->post('/requisitos', 'ConsultaRequisitosConstruccionController@consultaRequisitosConstruccion');
    $router->put('/requisitos/{folio}', 'ConsultaRequisitosConstruccionController@updateConsultaRequisitosConstruccion');
});

$router->group(['middleware' => 'auth', 'prefix' => 'requisitos_validar_construccion'], function () use ($router) {
    $router->get('/folio/{folio}', 'RequisitoConstruccionController@validarFolioRequisito');
    $router->put('/folio/{id}', 'RequisitoConstruccionController@updateConsultaRequisito');
});

$router->group(['middleware' => 'auth', 'prefix' => 'campos_construccion'], function () use ($router) {
    $router->get('/', 'CampoConstruccionController@index');
    $router->get('/getCampos2/{folio}', 'CampoConstruccionController@getCampos2');
    $router->get('/getCampos/{folio}', 'CampoConstruccionController@getCampos');
    $router->get('/getCampos/{folio}/{id}', 'CampoConstruccionController@getCampos');

    $router->get('/{id}', 'CampoConstruccionController@show');
    $router->post('/', 'CampoConstruccionController@store');
    $router->post('/actualizarNombreInteresado/{folio}', 'CampoConstruccionController@actualizarNombreInteresado');
    $router->post('/actualizarDireccion/{folio}', 'CampoConstruccionController@actualizarDireccion');
    $router->put('/{id}', 'CampoConstruccionController@update');
    $router->put('/hideRequisito/{id}', 'CampoConstruccionController@update2');
    $router->delete('/{id}', 'CampoConstruccionController@destroy');
    $router->post('/requisitoChange', 'CampoConstruccionController@agregarRequisito');
    $router->get('/{id}/campo_requisito', 'CampoConstruccionController@camposRequisitos');
});


$router->group(['middleware' => 'auth', 'prefix' => 'licencias_construccion'], function () use ($router) {
    $router->get('/listado', 'TramiteConstruccionController@getListado');
    $router->get('/listadoDirRev', 'TramiteConstruccionController@getListadoDirRev');
    $router->get('/getListadoVentanilla', 'TramiteConstruccionController@getListadoVentanilla');
    $router->get('/getListadoSolv', 'TramiteConstruccionController@getListadoDirSolv');
    $router->get('/listado-licencias', 'TramiteConstruccionController@getListadoLicenciasVisor');
    $router->post('/noFirmaElectronica/{folio}', 'TramiteConstruccionController@noFirmaElectronica');
    $router->post('/ingresoTramiteContinuar/{folio}', 'TramiteConstruccionController@ingresoTramiteContinuar');
    $router->post('/upload_file/{folio}', 'UploadConstruccionController@uploadFile');
    $router->post('/folio_tramite/{name}', 'UploadConstruccionController@upload');
    $router->post('/folio_tramite_text', 'TextConstruccionController@guardarText');
    $router->post('/update-licencias-status/{id}', 'LicenciasConstruccionController@updateLicenciaStatus');
    $router->post('/update-licencias-status-his/{id}', 'LicenciasConstruccionController@updateLicenciaStatusHis');
    $router->post('/enviarLicenciaInteresados/{folio}', 'LicenciasConstruccionController@enviarLicenciaInteresados');
    $router->get('/getInfo/{id}', 'LicenciasConstruccionController@getInfoLicencia');
    $router->get('/getFilesProrroga/{folio}', 'LicenciasConstruccionController@getFilesProrroga');
    $router->put('/updateFilesNames/{folio}', 'LicenciasConstruccionController@updateLicenciaFilesProrroga');
    $router->post('/fileUpload/{folio}/{inputName}', 'LicenciasConstruccionController@uploadFileProrroga');
});
$router->group(['middleware' => 'auth', 'prefix' => 'info-tramite_construccion'], function () use ($router) {
    $router->get('/{folio}', 'ConsultaRequisitosConstruccionController@getInfoTramite');
    $router->get('/tipo/{folio}', 'ConsultaRequisitosConstruccionController@getInfoTramite');
});
$router->group(['middleware' => 'auth', 'prefix' => 'roles_construccion'], function () use ($router) {
    $router->get('/', 'RoleControllerConstruccion@index');
    $router->get('/getAllRoles/{id_municipio}', 'RoleControllerConstruccion@index2');
    $router->get('/getByRoleType/{value}', 'RoleControllerConstruccion@index');
    $router->get('/role_user', 'RoleControllerConstruccion@indexRole');
    $router->get('/getRole', 'RoleControllerConstruccion@getUserRole');
    $router->post('/', 'RoleControllerConstruccion@store');
    $router->put('/{id}', 'RoleControllerConstruccion@update');
    $router->post('/storeByRoleType/{value}', 'RoleControllerConstruccion@store');
});
$router->group(['middleware' => 'auth', 'prefix' => 'municipios_construccion'], function () use ($router) {
    $router->get('/', 'MunicipioConstruccionController@index');
    $router->get('/getByUserType/{value}', 'MunicipioConstruccionController@index');
    $router->get('/auth', 'MunicipioConstruccionController@showMunicipio');
    $router->get('/getGirosAdmin', 'GiroApagadoController@getGirosAdmin');
    $router->get('/firmate', 'MunicipioConstruccionController@getFirmas');
    $router->get('/{id}', 'MunicipioConstruccionController@show');
    $router->get('/getCamposTemplate/{id_tramite}', 'MunicipioConstruccionController@getCamposTemplate');
    $router->get('/getRespuestasTemplate/{id_tramite_relacionado}/{id_tramite_construccion}/{id_municipio}', 'MunicipioConstruccionController@getRespuestasTemplate');
    $router->delete('/removeTipoTramites/{id}', 'MunicipioConstruccionController@removeTipoTramites');
    $router->delete('/removeCampoTemplate/{id}', 'MunicipioConstruccionController@removeCampoTemplate');
    $router->post('/firmate/{order}', 'MunicipioConstruccionController@firmateLicencia');
    $router->delete('/firmate/{id}', 'MunicipioConstruccionController@deleteFirma');
    $router->post('/storeTipoTramite', 'MunicipioConstruccionController@storeTipoTramite');
    $router->post('/storeCampoTemplate', 'MunicipioConstruccionController@storeCampoTemplate');
    $router->post('/storeRespuestaTemplate', 'MunicipioConstruccionController@storeRespuestaTemplate');
    $router->post('/updateCampoTemplate', 'MunicipioConstruccionController@updateCampoTemplate');
    $router->post('/storeFirma', 'MunicipioConstruccionController@storeFirma');
    $router->post('/editFirma', 'MunicipioConstruccionController@editFirma');
    $router->post('/', 'MunicipioConstruccionController@store');
    $router->post('/{id}/image', 'MunicipioConstruccionController@image');
    $router->post('/{id}/firma', 'MunicipioConstruccionController@firma');
    $router->put('/{id}', 'MunicipioConstruccionController@update');
    $router->put('emitirResolutivo/{id}', 'MunicipioConstruccionController@emitirResolutivo');
    $router->put('/updateTipoTramite/{id}', 'MunicipioConstruccionController@updateTipoTramite');
    $router->delete('/{id}', 'MunicipioConstruccionController@destroy');
    $router->delete('removeFirma/{id}', 'MunicipioConstruccionController@removeFirma');

});

$router->group(['middleware' => 'auth', 'prefix' => 'tramites_construccion'], function () use ($router) {
    $router->get('/getFlujoResolucion/{folio}', 'RevisionConstruccionController@getFlujoResolucion');
    $router->post('/ingreso', 'TramiteConstruccionController@ingreso');
    $router->post('/ordenPago/{id}', 'RevisionConstruccionController@uploadOrdenPago');
});
$router->group(['middleware' => 'auth', 'prefix' => 'municipios_construccion'], function () use ($router) {
    $router->get('/', 'MunicipioConstruccionController@index');
});
$router->group(['prefix' => 'revision_construccion', 'middleware' =>  ['auth']],  function () use ($router) {
    $router->get('/{folio}', 'RevisionConstruccionController@getResolucionInfo');
    $router->get('refrendo/{folio}', 'RevisionConstruccionController@getResolucionInfoRefrendo');
    $router->post('update/{folio}', 'RevisionConstruccionController@update');
    $router->post('updateDir/{folio}', 'RevisionConstruccionController@updateDir');
    $router->post('uploadFiles/{folio}', 'RevisionConstruccionController@uploadFiles');
    $router->post('uploadFilesHist/{folio}', 'RevisionConstruccionController@uploadFilesHis');
    $router->post('emitir/{folio}', 'RevisionConstruccionController@emitirLicencia');
});

$router->group(['prefix' => 'graficas-construccion', 'middleware' =>  ['auth','role_construccion:director|admin']],  function () use ($router) {
    $router->get('advancedpie', 'ReporteConstruccionController@lineTimeLic');
    $router->get('advancedpie-admin', 'ReporteConstruccionController@lineTimeLicAdmin');
    $router->get('bar', 'ReporteConstruccionController@barraAnual');
    $router->post('bar-list', 'ReporteConstruccionController@barraList');
    $router->get('pie-rev', 'ReporteConstruccionController@pieRev');
    $router->get('pie2', 'ReporteConstruccionController@pieBarMunicipiosAll');
    $router->get('bar/{id}', 'ReporteConstruccionController@pieBarMunicipiosById');
});

$router->group(['prefix' => 'generarLicenciaConstruccion', 'middleware' =>  ['auth','role_construccion:ventanilla|revisor|director|admin']],  function () use ($router) {
    $router->post('/{folio}', 'FormatosConstruccionController@generarLicencia');
    $router->post('prorroga/{folio}', 'FormatosConstruccionController@generarProrroga');
    $router->post('pagar/{id}', 'LicenciasConstruccionController@licenciaPagada');
    $router->post('pagarHis/{id}', 'LicenciasConstruccionController@licenciaPagadaHis');
    $router->post('baja/{id}', 'LicenciasConstruccionController@licenciaBaja');
    $router->post('pdf_firmado/{id}', 'LicenciasConstruccionController@subirPdfFirmado');
    $router->post('pdf_firmado_hist/{id}', 'LicenciasConstruccionController@subirPdfFirmadoHis');
});

$router->group(['middleware' => 'auth', 'prefix' => 'notarios'], function () use ($router) {
    $router->get('/', 'NotarioPublicoController@getAll');
});

$router->group(['middleware' => 'auth', 'prefix' => 'get_validador_user'], function () use ($router) {
    $router->get('/', 'UsuarioController@getUserValidator');

});

$router->group(['middleware' => 'auth', 'prefix' => 'reportes-admin'], function () use ($router) {
    $router->get('/getLicenciasByTypeMain', 'ReporteAdminController@main');
    $router->get('/getLicenciasByTypeMainConstruccion', 'ReporteAdminController@mainConstruccion');

});

$router->get('/municipios_construccion/getTipoTramiteConstruccion/{id_municipio}', 'MunicipioConstruccionController@getTipoTramite');
$router->get('/municipios_construccion/getTipoTramiteConstruccion2/{tramite_relacionado}', 'MunicipioConstruccionController@getTipoTramite2');
$router->get('licenciaConstruccion/{folio}/{anio}', 'FormatosConstruccionController@licenciaConstruccion');
$router->get('licenciaConstruccionById/{id}', 'FormatosConstruccionController@licenciaConstruccionById');

/*Apis Google maps */
$router->group(['middleware' => 'throttle:50,1'], function() use ($router) {
    $router->get('geocode', 'GeocodeController@geocode');
    $router->get('reverse-geocode', 'GeocodeController@reverseGeocode');
});

$router->get('ficha_semadet/{clave}', 'FormatosGiroController@storeFichaSemadet');
$router->get('fichaSemdet/{uuid}', 'FormatosGiroController@fichaTecnicaSemadetGet');

$router->post('contactFormLanding', 'Controller@contactFormLanding');


$router->get('/{path:.*\.(?:jpg|jpeg|png|gif|bmp|svg|pdf)}', function ($path) {
    $pathToFile = app()->basePath('public') . '/' . $path;

    if (file_exists($pathToFile)) {
        $file = new \Symfony\Component\HttpFoundation\File\File($pathToFile);
        $response = new \Illuminate\Http\Response(file_get_contents($pathToFile));
        $response->header('Content-Type', $file->getMimeType());

        return $response;
    }

    return response()->json(['message' => 'File not found'], 404);
});
