<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TramiteConstruccion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('tramite_construccion', function (Blueprint $table) {
            $table->id();
            $table->string('folio',255)->nullable()->unique();
            $table->integer('step_actual')->nullable();
            $table->string('firma_usuario',255)->nullable();
            $table->integer('id_usuario')->nullable();
            $table->integer('id_usuario_ventanilla')->nullable();
            $table->integer('rol_ingreso')->nullable();
            $table->dateTime('fecha_ingreso_documentos')->nullable();
            $table->dateTime('fecha_inicio_tramite')->nullable();
            $table->dateTime('fecha_visto_ventanilla')->nullable();
            $table->dateTime('fecha_licencia_entregada')->nullable();
            $table->integer('tengo_firma')->nullable();
            $table->dateTime('fecha_no_firma')->nullable();
            $table->string('nombre_solicitante_oficial',255)->nullable();
            $table->string('carta_responsiva',255)->nullable();
            $table->integer('enviado_revisores')->nullable();
            $table->dateTime('fecha_enviado_revisores')->nullable();
            $table->string('pdf_licencia',255)->nullable();
            $table->string('orden_pago',255)->nullable();
            $table->integer('status');
            $table->integer('tipo_tramite');
            $table->integer('step_uno')->nullable();
            $table->integer('step_dos')->nullable();
            $table->integer('step_tres')->nullable();
            $table->integer('step_cuatro')->nullable();
            $table->integer('step_cinco')->nullable();
            $table->integer('aprobado_director')->nullable()->default(0);
             $table->boolean('resolutivo')->default(false);
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
