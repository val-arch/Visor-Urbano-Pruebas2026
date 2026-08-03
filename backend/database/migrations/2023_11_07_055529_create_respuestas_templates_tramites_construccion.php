<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRespuestasTemplatesTramitesConstruccion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('respuestas_templates_tramites_construccion', function (Blueprint $table) {
            $table->id();
            $table->integer('id_tramite');
            $table->integer('id_tramite_construccion');
            $table->integer('id_municipio');
            $table->integer('id_campo');
            $table->string('campo');
            $table->string('respuesta');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('respuestas_templates_tramites_construccion');
    }
}
