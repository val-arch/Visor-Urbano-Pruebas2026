<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefrendoArchivosHistoricoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refrendo_archivos_historico', function (Blueprint $table) {
            $table->id();
            $table->longText('archivo')->nullable();
            $table->longText('description')->nullable();
            $table->integer('id_historico_licencia')->nullable();
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
        Schema::dropIfExists('refrendo_archivos_historico');
    }
}
