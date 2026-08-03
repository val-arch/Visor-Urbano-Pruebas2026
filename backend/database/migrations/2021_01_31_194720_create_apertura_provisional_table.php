<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAperturaProvisionalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apertura_provisional', function (Blueprint $table) {
            $table->id();
            $table->string('folio',255);
            $table->integer('id_tramite')->nullable();
            $table->integer('contador')->nullable();
            $table->integer('id_usuario_otorgo')->nullable();
            $table->integer('rol_otorgo')->nullable();
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_limite');
            $table->integer('status');
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
        Schema::dropIfExists('apertura_provisional');
    }
}
