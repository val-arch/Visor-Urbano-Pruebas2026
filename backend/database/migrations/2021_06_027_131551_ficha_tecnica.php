<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FichaTecnica extends Migration{
    //{direccion}/{metros}/{coordenadas}/{img}
    public function up(){
        Schema::create('ficha_tecnica', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->longtext('direccion');
            $table->longtext('metros');
            $table->longtext('coordenadas');
            $table->text('img');
            $table->integer('municipio_id');
            $table->timestamps();
        });
    }

    public function down(){
        Schema::dropIfExists('ficha_tecnica');
    }
}