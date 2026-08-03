<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RequisitosContruccion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('requisitos_construccion', function (Blueprint $table) {
            $table->id();
            $table->integer('municipios_id');
            $table->integer('campos_id');
            $table->string('id_requisitos',300)->nullable();
            $table->timestamps();
            $table->foreign('municipios_id')->references('id')->on('municipios_construccion');
            $table->foreign('campos_id')->references('id')->on('campos_construccion');
          
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
