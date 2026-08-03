<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGirosApagadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('giro_apagados', function (Blueprint $table) {
            $table->id();
            $table->integer('giros_id');
            $table->integer('municipios_id');
            $table->timestamps();
            $table->foreign('giros_id')->references('id')->on('giros');
            $table->foreign('municipios_id')->references('id')->on('municipios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('giros_apagados');
    }
}
