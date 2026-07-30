<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeocodeController extends Controller
{
    public function geocode(Request $request)
    {
        $domicilio = $request->input('domicilio');
        $municipio = $request->input('municipio');

        if (strlen($domicilio) > 6) {
            $url = 'https://maps.googleapis.com/maps/api/geocode/json?' . http_build_query([
                'address' => "$domicilio, $municipio, Morelos, México",
                'key' => env('KEY_GOOGLE_MAPS'),// 'AIzaSyBnl1kjb925wEou2ms-dwh39H2_FZZzTxQ' //'AIzaSyCt8iQcvCc8xq50xvR-NXRA2oHWToaiKmo'
            ]);

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $response = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($response, true);

            // Si el error es de exceder el límite
            if (isset($data['status']) && $data['status'] === 'OVER_QUERY_LIMIT') {
                $userIp = $request->ip();
                $userAgent = $request->header('User-Agent');

                // Registrar la información en el log
                Log::warning('Google Maps API rate limit exceeded.', [
                    'ip' => $userIp,
                    'user_agent' => $userAgent
                ]);

                return response()->json(['error' => 'Has excedido el límite de solicitudes para el API. IP...'.$userIp.' Almacenando....']);
            }

            if (count($data['results']) > 0) {
                // Devuelves el resultado
                return response()->json($data['results'][0]);
            }
        }

        return response()->json(['error' => 'No se encontraron resultados']);
    }

    public function reverseGeocode(Request $request)
    {
        $lat = $request->input('lat');
        $lng = $request->input('lng');

        $url = 'https://maps.googleapis.com/maps/api/geocode/json?' . http_build_query([
            'latlng' => "$lat,$lng",
            'key' => env('KEY_GOOGLE_MAPS'),// 'AIzaSyBnl1kjb925wEou2ms-dwh39H2_FZZzTxQ' //'AIzaSyCt8iQcvCc8xq50xvR-NXRA2oHWToaiKmo'
        ]);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        curl_close($ch);

        $data = json_decode($response, true);

        if (count($data['results']) > 0) {
            // Aquí puedes hacer lo que necesites con los resultados
            // Por ejemplo, puedes devolverlos como respuesta a la solicitud
            return response()->json($data['results'][0]);
        }

        return response()->json(['error' => 'No se encontraron resultados']);
    }
}
