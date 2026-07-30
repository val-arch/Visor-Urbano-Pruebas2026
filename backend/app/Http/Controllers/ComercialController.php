<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;


class ComercialController extends Controller{

    public function index($url,$imagen,$municipio,$area,$comercios){
       // return file_get_contents(base64_decode($url));
        $urlDecoded  =   str_replace ( ' ', '%20', \base64_decode($url));
       // return;
       // var_dump(base64_decode($comercios));
      //  return;
       // $url_d = 'https://api-visorurbano.jalisco.gob.mx/geoserver/ows?service=WFS&request=GetFeature&version=2.0.0&typename=VUJ:denue&outputFormat=application/json&srsName=EPSG:4326&cql_filter='.base64_decode($url);;
        $urlDecoded = str_replace('https://api-visorurbano.jalisco.gob.mx/geoserver/',env('URL_REPLACE'),$urlDecoded);
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
    $data = (json_decode($response,true));
    $data = $data["features"];
  //  return;
            $logo = "https://cuernavaca.visorurbano.com/assets/images/logo_cuernavaca.svg";
        $dataCon      = array('data'=>$data,'municipio'=>utf8_encode(base64_decode($municipio)),'logo'=>$logo,'area'=>base64_decode($area),'image'=> base64_decode($imagen),'comercios'=>utf8_encode(base64_decode($comercios)),'url_minimapa'=>'asd');
        $pdf          = PDF::loadView('comercial',(array)$dataCon)->setWarnings(false);
        $pdf->setOptions([
                            'isPhpEnabled' => true,
                            'isRemoteEnabled'=>true,
                            'isHtml5ParserEnabled'=>true]
                        );

        return $pdf->stream('comercial.pdf');
    }

}
