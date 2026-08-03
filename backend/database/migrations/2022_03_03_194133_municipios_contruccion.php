<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MunicipiosContruccion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    
        Schema::create('municipios_construccion', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 250);
            $table->string('image', 250)->nullable();
            $table->string('director', 250)->nullable();
            $table->string('firma_director', 250)->nullable();
            $table->integer('ficha_tramite')->nullable()->default(1);
            $table->integer('dias_solventar')->nullable();
            $table->integer('emitir_licencia')->nullable()->default(0);
            $table->string('direccion')->nullable();
            $table->string('telefono')->nullable();
            $table->json('usuarios_emision')->nullable();
            $table->string('area_encargada', 250)->nullable();
            $table->integer('generar_licencia_ventanilla')->default(0)->nullable();
            $table->longText('restricciones_licencia')->default('')->nullable();
            $table->integer('folio_init')->nullable();
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
        //
    }
}