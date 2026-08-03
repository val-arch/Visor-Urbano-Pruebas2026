<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdenResolucionesConstruccion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orden_resoluciones_construccion', function (Blueprint $table) {
            $table->id();
            $table->integer('id_campo');
            $table->integer('id_municipio');
            $table->integer('id_rol');
            $table->integer('orden')->nullable();
            $table->boolean('mostrar')->default(true)->nullable();
            $table->integer('tipo_tramite')->nullable();
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
        Schema::dropIfExists('table_orden_resoluciones_construccion');
    }
}
