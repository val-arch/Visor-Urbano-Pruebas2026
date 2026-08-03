<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ConsultaRequisitosConstruccion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('consulta_requisitos_construccion', function (Blueprint $table) {
            $table->id();
            $table->string('folio',255)->nullable()->unique();
            $table->string('folio_interno',100)->nullable();
            $table->integer('tramite_relacionado');
            $table->string('calle',100);
            $table->string('colonia',100);
            $table->string('municipio',50);
            $table->integer('id_municipio');
            $table->integer('niveles_nuevos_construir')->nullable();
            $table->integer('numero_viviendas')->nullable();
            $table->integer('sotano')->nullable();
            $table->string('nombre_solicitante',100)->nullable();
            $table->decimal('superficie_propiedad',8,2)->nullable()->default(0.0);
            $table->integer('mdemolicion')->nullable();
            $table->longText('url_minimapa')->nullable();
            $table->json('restricciones')->nullable();
            $table->year('anio_folio')->default();
            $table->integer('status')->default(1);
            $table->integer('id_usuario')->nullable()->default(0);
            $table->decimal('superficie_habitacional',8,2)->nullable()->default(0.0);
            $table->decimal('superficie_comercial_servicios',8,2)->nullable()->default(0.0);
            $table->decimal('superficie_industrial',8,2)->nullable()->default(0.0);
            $table->decimal('superficie_turistico',8,2)->nullable()->default(0.0);
            $table->decimal('superficie_equipamiento',8,2)->nullable()->default(0.0);
            $table->decimal('superficie_espacios_verdes',8,2)->nullable()->default(0.0);
            $table->decimal('superficie_otro',8,2)->nullable()->default(0.0);
            $table->string('concepto_otro',50)->nullable();
            $table->longText('coords');
            $table->string('uuid',200);
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
        Schema::dropIfExists('consulta_requisitos_construccion');
    }
}
