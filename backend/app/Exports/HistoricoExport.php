<?php

namespace App\Exports;

use App\Models\HistoricoLicenciaExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class HistoricoExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $id = Auth::user()->id_municipio;
        return  DB::table('historico_licencias_giro')->select(
            'folio_licencia',
            'fecha_emision',
            'giro',
            'descripcion_detallada',
            'codigo_giro',
            'superficie_giro',
            'calle',
            'numero_ext',
            'numero_int',
            'colonia',
            'clave_catastral',
            'referencia',
            'coordonadas_x',
            'coordonadas_y',
            'nombre_titular',
            'apellido_p',
            'apellido_m',
            'rfc',
            'curp',
            'telefono',
            'razon_social',
            'email',
            'calle_titular',
            'numero_ext_titular',
            'numero_int_titular',
            'colonia_titular',
            'venta_alcohol',
            'horario',
            'calle_predio',
            'colonia_predio',
            'num_int_predio',
            'num_ext_predio',
            'cp_predio',
            'tipo_inmueble',
            'nombre_negocio',
            'inversion',
            'numero_empleado',
            'numero_cajones',
            'anio_licencia',
            'status_licencia',
            'motivo',
            'nombre_solicitante',
            'apellido_solicitante_p',
            'apellido_solicitante_m',
            'curp_solicitante',
            'rfc_solicitante',
            'telefono_solicitante',
            'calle_solicitante',
            'email_solicitante',
            'cp_solicitante',
            'hora_a',
            'hora_c',
            'tipo_licencia',
            'numero_lic',
            'anio_licencia',
            'status_baja',
            'status_pago'
        )->where('deleted_at', null)
        ->where('id_municipio', $id)->get();

    }
    public function headings(): array
    {
        return [

            'Folio licencia',
            'Fecha emision',
            'Giro',
            'Descripcion detallada',
            'Codigo giro',
            'Superficie giro',
            'Calle',
            'Numero ext',
            'Numero int',
            'Colonia',
            'Clave catastral',
            'Referencia',
            'Coordonadas x',
            'Coordonadas y',
            'Nombre titular',
            'Apellido p',
            'Apellido m',
            'RFC',
            'CURP',
            'Telefono',
            'Razon social',
            'Email',
            'Calle titular',
            'Numero ext titular',
            'Numero int titular',
            'Colonia titular',
            'Venta alcohol',
            'Horario',
            'Calle predio',
            'Colonia predio',
            'Num int predio',
            'Num ext predio',
            'Cp predio',
            'Tipo inmueble',
            'Nombre negocio',
            'Inversion',
            'Numero empleado',
            'Numero cajones',
            'Anio licencia',
            'Estatus licencia',
            'Motivo',
            'Propietario Nombre',
            'Propietario Apellido paterno',
            'Propietario Apellido materno',
            'Propietario Curp',
            'Propietario RFC',
            'Propietario Telefono',
            'Propietario Calle',
            'Propietario Email',
            'Propietario CC',
            'Hora a',
            'Hora c',
            'Tipo licencia',
            'Numero lic',
            'Anio licencia',
            'Estatus baja',
            'Estatus pago'

        ];
    }
}
