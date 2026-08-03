<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RespuestasContruccion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('respuestas_construccion', function (Blueprint $table) {
            $table->id();
            $table->integer('id_tramite')->nullable();   
            $table->string('name',255)->nullable();
            $table->longText('value')->nullable(true);
            $table->integer('id_usuario')->nullable();
            $table->integer('status')->default(1)->nullable();
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
