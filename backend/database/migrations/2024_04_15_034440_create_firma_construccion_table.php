<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFirmaConstruccionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('firma_construccion', function (Blueprint $table) {
            $table->id();
            $table->integer('id_tramite');
            $table->integer('id_usuario');
            $table->integer('rol');
            $table->longText('hash_a_firmar')->nullable();
            $table->longText('hash_firmado')->nullable();
            $table->json('response')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('firma_construccion');
    }
}
