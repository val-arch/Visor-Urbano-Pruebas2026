<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFichaTecnicaDescargasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ficha_tecnica_descargas', function (Blueprint $table) {
            $table->id();
            $table->string('ciudad')->nullable();;
            $table->string('correo')->nullable();;
            $table->string('edad')->nullable();;
            $table->string('nombre')->nullable();;
            $table->string('sector')->nullable();
            $table->string('usos')->nullable();
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
        Schema::dropIfExists('ficha_tecnica_descargas');
    }
}
