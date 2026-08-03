<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableFirmasMunicipios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('firmas_municipios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_firmante', 255);
            $table->string('dependencia', 255);
            $table->integer('orden');
            $table->integer('id_municipio');
            $table->foreign('id_municipio')->references('id')->on('municipios');
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
        Schema::dropIfExists('firmas_municipios');
    }
}
