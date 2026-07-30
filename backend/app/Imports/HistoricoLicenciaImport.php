<?php

namespace App\Imports;

use App\Models\HistoricoLicencias;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class HistoricoLicenciaImport implements ToModel,WithBatchInserts,WithCustomCsvSettings
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function model(array $row)
    {
         if($row[0] !='folio_licencia'){


        return new HistoricoLicencias([
            'folio_licencia'        => $row[0],
            'fecha_emision'         => $row[1],
            'giro'                  => $row[2],
            'descripcion_detallada' => $row[3],
            'codigo_giro'           => $row[4],
            'superficie_giro'       => $row[5],
            'calle_predio'          => $row[6],
            'num_int_predio'        => $row[8],
            'num_ext_predio'        => $row[7],
            'colonia_predio'        => $row[9],
            'clave_catastral'       => $row[10],
            'referencia'            => $row[11],
            'coordonadas_x'         => $row[12],
            'coordonadas_y'         => $row[13],
            'nombre_titular'        => $row[14],
            'apellido_p'            => $row[15],
            'apellido_m'            => $row[16],
            'rfc'                   => $row[17],
            'curp'                  => $row[18],
            'telefono'              => $row[19],
            'razon_social'          => $row[20],
            'email'                 => $row[21],
            'calle_titular'         => $row[22],
            'numero_ext_titular'    => $row[23],
            'numero_int_titular'    => $row[24],
            'colonia_titular'       => $row[25],
            'venta_alcohol'         => $row[26],
            'horario'               => $row[27],
            'anuncio'               => $row[28] ?? "-",
            'id_municipio'          => Auth::user()->id_municipio,
            'status'                => 1,
            'status_pago'           => 1,
            'tipo_licencia'           => 'Nueva',
            'status_licencia'       =>'Vigente'
        ]);
    }
    }

    public function onError(\Throwable $e)
    {
        // Handle the exception how you'd like.

    }
    public function getCsvSettings(): array
    {
        return [
            'input_encoding' => 'ISO-8859-1'
        ];
    }

    public function batchSize(): int
    {
        return 80;
    }

}


