<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Blog extends Migration{
    
    public function up(){
        Schema::create('blog', function (Blueprint $table) {
            $table->id();
            $table->text('titulo');
            $table->text('img');
            $table->text('link',255);
            $table->text('resumen',255);
            $table->date('fecha_noticia');
            //1-noticia url externo, 2-blog dentro de visor
            $table->integer('type')->nullable();
            $table->longText('body')->nullable();
            //0 - off, 1 - On
            $table->integer('publicado')->default(0)->nullable();
            $table->timestamps();
        });
    }

    public function down(){
        Schema::dropIfExists('blog');
    }
}