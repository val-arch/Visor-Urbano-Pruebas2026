<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDataToHistoricoLicencia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('historico_licencias_giro', function (Blueprint $table) {
           
           $table->string('nombre_solicitante', 255)->nullable();
            $table->string('apellido_solicitante_p', 255)->nullable();
            $table->string('apellido_solicitante_m', 255)->nullable();
            $table->string('curp_solicitante', 255)->nullable();
            $table->string('rfc_solicitante', 255)->nullable();
            $table->string('telefono_solicitante', 255)->nullable();
            $table->string('calle_solicitante', 255)->nullable();
            $table->string('email_solicitante', 255)->nullable();
            $table->string('cp_solicitante', 255)->nullable();
            $table->string('cp_titular', 255)->nullable();
            $table->string('calle_predio', 255)->nullable();
            $table->string('colonia_predio', 255)->nullable();
            $table->string('num_int_predio', 255)->nullable();
            $table->string('num_ext_predio', 255)->nullable();
            $table->string('cp_predio', 255)->nullable();
            $table->string('tipo_inmueble', 255)->nullable();
            $table->string('nombre_negocio', 255)->nullable();
            $table->string('inversion', 255)->nullable();
            $table->string('numero_empleado', 255)->nullable();
            $table->string('numero_cajones', 255)->nullable();
            $table->string('anio_licencia', 255)->nullable();
            $table->string('tipo_licencia', 255)->nullable();
            $table->string('status_licencia', 255)->nullable();
            $table->string('motivo', 255)->nullable();
            $table->string('status_baja', 255)->nullable();
            $table->string('status_pago', 255)->nullable();
            $table->string('hora_a', 255)->nullable();
            $table->string('hora_c', 255)->nullable();
            $table->string('anio_lic', 255)->nullable();
            $table->integer('id_usuario_pago')->default(0)->nullable();
            $table->dateTime('fecha_pago', 0)->nullable();
            $table->string('pdf_escaneado', 2000)->nullable();
            $table->integer('step_1')->nullable();
            $table->integer('step_2')->nullable();
            $table->integer('step_3')->nullable();
            $table->integer('step_4')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('historico_licencias_giro', function (Blueprint $table) {
            //
        });
    }
}
