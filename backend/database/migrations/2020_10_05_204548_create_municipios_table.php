<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMunicipiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('municipios', function (Blueprint $table) {
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
            $table->string('area_encargada', 250)->nullable();
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
        Schema::dropIfExists('municipios');
    }
}



//ahualco de mercado 3
// bolaños 19
//chapala 30
//lagos de moreno 53
//puerto vallarta 67
//tonala 101
//zapotlan el grande 23
