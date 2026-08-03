<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGirosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('giros', function (Blueprint $table) {
            $table->id();
            $table->string('codigo',255)->nullable();
            $table->string('SCIAN',255)->nullable();
            $table->text('palabras_relacion')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

      Schema::create('giros_impacto', function (Blueprint $table) {
            $table->id();
            $table->integer('giro_id');
            $table->integer('impacto');
            $table->integer('municipio_id');
            $table->timestamps();
            $table->foreign('giro_id')->references('id')->on('giros');
            $table->foreign('municipio_id')->references('id')->on('municipios');
        });
        
        Schema::create('giros_cedula', function (Blueprint $table) {
            $table->id();
            $table->integer('giro_id');
            $table->integer('municipio_id');
            $table->timestamps();
            $table->foreign('giro_id')->references('id')->on('giros');
            $table->foreign('municipio_id')->references('id')->on('municipios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('giros');
    }
}
