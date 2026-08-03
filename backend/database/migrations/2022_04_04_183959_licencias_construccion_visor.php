<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LicenciasConstruccionVisor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('licencias_construccion_visor', function (Blueprint $table) {
            $table->id();
            $table->string('dueno',200);
            $table->string('folio',200);
            $table->string('tipo_construccion',200);
            $table->string('consecutivo',200);
            $table->string('tipo_licencia',200)->default('Nueva');
            $table->string('apellido_p',200)->nullable();
            $table->string('apellido_m',200)->nullable();
            $table->string('curp',200)->nullable();
            $table->longText('img_logo')->nullable();
            $table->longText('firma')->nullable();
            $table->longText('url_minimapa')->nullable();
            $table->longText('pdf_escaneado')->nullable();
            $table->year('anio_licencia')->default();
            $table->integer('tipo')->default(1)->nullable();
            $table->integer('id_usuario_genero')->default();
            $table->longText('archivo_motivo')->nullable();
            $table->dateTime('fecha_cambio_status')->nullable();
            $table->text('archivo_compobante_pago')->nullable();
            $table->dateTime('fecha_pago', 0)->nullable();
            $table->dateTime('fecha_baja', 0)->nullable();
            $table->string('folio_secundario',200)->default('')->nullable();
            $table->longText('motivo_baja')->nullable();
            $table->integer('id_usuario_baja')->default(0)->nullable();
            $table->string('precio_lic',255)->nullable();
            $table->integer('status_pago')->default(0)->nullable();
            $table->integer('id_usuario_pago')->default(0)->nullable();
            $table->integer('status_baja')->default(0)->nullable();
            $table->integer('numero_lic')->nullable();
            $table->integer('municipio_id')->nullable();
            $table->longText('observaciones')->nullable();
            $table->string('nombre_firmante_1', 255)->nullable();
            $table->string('dependencia_1', 255)->nullable();
            $table->string('firma_1', 255)->nullable();
            $table->string('nombre_firmante_2', 255)->nullable();
            $table->string('dependencia_2', 255)->nullable();
            $table->string('firma_2', 255)->nullable();
            $table->string('nombre_firmante_3', 255)->nullable();
            $table->string('dependencia_3', 255)->nullable();
            $table->string('firma_3', 255)->nullable();
            $table->string('nombre_firmante_4', 255)->nullable();
            $table->string('dependencia_4', 255)->nullable();
            $table->string('firma_4', 255)->nullable();
            $table->string('dependencia_adicional', 255)->nullable();
            $table->string('firma_adicional', 255)->nullable();
            $table->string('status_licencia')->after('status')->nullable();
            $table->string('motivo')->after('status')->nullable();
            $table->string('urlLicenciaPdf', 255)->nullable();
            $table->json('nombres_archivos_prorroga')->nullable();
            $table->json('rutas_archivos_prorroga')->nullable();
            $table->json('firmantes')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
