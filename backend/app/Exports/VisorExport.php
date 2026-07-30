<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VisorExport implements FromCollection, WithHeadings
{
    public function collection()
    {

        $id = Auth::user()->id_municipio;
        $data = DB::table('licencias_giro_visor')->select(
            'licencias_giro_visor.numero_lic',
            'licencias_giro_visor.dueno',
            'licencias_giro_visor.apellido_p',
            'licencias_giro_visor.apellido_m',
            'consulta_requisitos.municipio',
            'consulta_requisitos.colonia',
            'consulta_requisitos.calle',
            'licencias_giro_visor.created_at',
            'licencias_giro_visor.folio',
            'licencias_giro_visor.actividad_comercial',
            'licencias_giro_visor.codigo_scian',
            'licencias_giro_visor.superficie_autorizada',
            'licencias_giro_visor.hora_a',
            'licencias_giro_visor.hora_c',
            'licencias_giro_visor.curp',
            'licencias_giro_visor.anio_licencia',
            'licencias_giro_visor.fecha_pago',
            'licencias_giro_visor.motivo_baja',
            'licencias_giro_visor.tipo_licencia',
            'licencias_giro_visor.fecha_cambio_status',
            DB::raw("(select value from respuestas
            where id_tramite = consulta_requisitos.id
            and ( name = 'numero_empleados')) as numero_empleados"),
            DB::raw("(select value from respuestas
            where id_tramite = consulta_requisitos.id
            and ( name = 'inversion_estimada')) as inversion_estimada")
        )
            ->join('consulta_requisitos', 'licencias_giro_visor.folio', '=', 'consulta_requisitos.folio')
            ->where('licencias_giro_visor.tipo_licencia', 'Nueva')
            ->where('licencias_giro_visor.deleted_at', null)
            ->where('licencias_giro_visor.municipio_id', $id);


        return $data2 = DB::table('licencias_giro_visor')->select(
            'licencias_giro_visor.numero_lic',
            'licencias_giro_visor.dueno',
            'licencias_giro_visor.apellido_p',
            'licencias_giro_visor.apellido_m',
            'consulta_requisitos.municipio',
            'consulta_requisitos.colonia',
            'consulta_requisitos.calle',
            'licencias_giro_visor.created_at',
            'licencias_giro_visor.folio',
            'licencias_giro_visor.actividad_comercial',
            'licencias_giro_visor.codigo_scian',
            'licencias_giro_visor.superficie_autorizada',
            'licencias_giro_visor.hora_a',
            'licencias_giro_visor.hora_c',
            'licencias_giro_visor.curp',
            'licencias_giro_visor.anio_licencia',
            'licencias_giro_visor.fecha_pago',
            'licencias_giro_visor.motivo_baja',
            'licencias_giro_visor.tipo_licencia',
            'licencias_giro_visor.fecha_cambio_status',
            DB::raw("(select value from respuestas
            where id_tramite = consulta_requisitos.id
            and ( name = 'numero_empleados')) as numero_empleados"),
            DB::raw("(select value from respuestas
            where id_tramite = consulta_requisitos.id
            and ( name = 'inversion_estimada')) as inversion_estimada")
        )->join('consulta_requisitos', 'licencias_giro_visor.folio', '=', 'consulta_requisitos.folio')
            ->where('licencias_giro_visor.tipo_licencia', 'Refrendo')
            ->where('licencias_giro_visor.deleted_at', null)
            ->where('licencias_giro_visor.municipio_id', $id)
            ->union($data)->get();
    }
    public function headings(): array
    {
        return [
            'Folio licencia',
            'Propietario',
            'Primer Apellido',
            'Segundo Apellido',
            'Municipio',
            'Colonia',
            'Calle',
            'Fecha Emitida',
            'Folio requisitos',
            'Actividad comercial',
            'Codigo scian',
            'Superficie autorizada',
            'Hora apertura',
            'Hora cierre',
            'CURP',
            'Anio licencia',
            'Fecha pago',
            'Motivo',
            'Tipo licencia',
            'Fecha cambio status',
            'Numero Empleados',
            'Inversion Estimada'
        ];
    }
}
