<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoricoLicenciasGiro extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historico_licencias_giro', function (Blueprint $table) {
            $table->id();
            $table->string('folio_licencia',255)->nullable();
            $table->string('fecha_emision')->nullable();
            $table->string('giro',255)->nullable();
            $table->string('descripcion_detallada',255)->nullable();
            $table->string('codigo_giro',100)->nullable();
            $table->string('superficie_giro',255)->nullable();
            $table->string('calle',100)->nullable();
            $table->string('numero_ext',100)->nullable();
            $table->string('numero_int',50)->nullable();
            $table->string('colonia',50)->nullable();
            $table->string('clave_catastral',100)->nullable();
            $table->string('referencia',100)->nullable();
            $table->string('coordonadas_x',30)->nullable();
            $table->string('coordonadas_y',30)->nullable();
            $table->string('nombre_titular',100)->nullable();
            $table->string('apellido_p',100)->nullable();
            $table->string('apellido_m',100)->nullable();
            $table->string('rfc', 25)->nullable();
            $table->string('curp', 25)->nullable();
            $table->string('telefono', 25)->nullable();
            $table->string('razon_social', 50)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('calle_titular',100)->nullable();
            $table->string('numero_ext_titular',100)->nullable();
            $table->string('numero_int_titular',50)->nullable();
            $table->string('colonia_titular',50)->nullable();
            $table->string('venta_alcohol',50)->nullable();
            $table->string('horario',100)->nullable();
            $table->integer('id_municipio')->nullable();
            $table->integer('status')->nullable();
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
        Schema::dropIfExists('historico_licencias_giro');
    }
}