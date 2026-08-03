<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FirmasMunicipiosConstruccion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        
        Schema::create('firmas_municipios_construccion', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_firmante', 255);
            $table->string('dependencia', 255);
            $table->integer('id_municipio');
            $table->string('firma',255)->nullable();
            $table->foreign('id_municipio')->references('id')->on('municipios_construccion');
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
