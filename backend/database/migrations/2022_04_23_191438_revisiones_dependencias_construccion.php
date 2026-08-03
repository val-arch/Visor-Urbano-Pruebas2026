<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RevisionesDependenciasConstruccion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revisiones_dependencias_construccion', function (Blueprint $table) {
            $table->id();
            $table->integer('id_tramite');
            $table->integer('id_municipio');
            $table->string('folio',255);
            $table->integer('rol');
            $table->dateTime('fecha_inicio')->nullable();
            $table->dateTime('fecha_actualizacion')->nullable();
            $table->integer('status_actual')->nullable();
            $table->longText('archivo_actual')->nullable();
            $table->string('firma',255)->nullable();
            $table->integer('id_usuario')->nullable();
            $table->boolean('resolver');
            $table->integer('id_rev')->nullable();
            $table->integer('campo_dependencia')->nullable();
            $table->timestamps();
        });

        Schema::create('resolucion_dependencias_construccion', function (Blueprint $table) {
            $table->id();
            $table->integer('id_tramite');
            $table->integer('rol')->nullable();
            $table->integer('id_usuario')->nullable();
            $table->integer('resolucion_status')->nullable();
            $table->longText('resolucion_text')->nullable();
            $table->longText('resolucion_archivo')->nullable();
            $table->integer('id_licencias_construccion_visor')->nullable();
            $table->string('firma',255)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('solventacion_construccion', function (Blueprint $table) {
            $table->id();
            $table->integer('id_tramite');
            $table->integer('rol')->nullable();
            $table->integer('id_usuario')->nullable();
            $table->longText('comentario')->nullable();
            $table->longText('comentario_usuario')->nullable();
            $table->longText('archivos')->nullable();
            $table->dateTime('fecha_maxima_solventacion')->nullable();
            $table->dateTime('fecha_ingreso_documentos')->nullable();
            $table->dateTime('fecha_visto')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('chat_revisores_construccion', function (Blueprint $table) {
            $table->id();
            $table->integer('id_tramite');
            $table->integer('ir_usuario');
            $table->integer('rol')->nullable();
            $table->longText('comentario')->nullable();
            $table->longText('imagen')->nullable();
            $table->string('archivo_adjunto',255)->nullable();
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
