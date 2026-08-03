<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFirmasLicencia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('licencias_giro_visor', function (Blueprint $table) {
            $table->string('nombre_firmante_1', 255)->nullable();
            $table->string('dependencia_1', 255)->nullable();
            $table->string('firma_1', 255)->nullable();
            $table->string('nombre_firmante_2', 255)->nullable();
            $table->string('dependencia_2', 255)->nullable();
            $table->string('firma_2', 255)->nullable();
            $table->string('nombre_firmante_3', 255)->nullable();
            $table->string('dependencia_3', 255)->nullable();
            $table->string('firma_3', 255)->nullable();
            $table->string('nombre_firmante_4', 255)->nullable();
            $table->string('dependencia_4', 255)->nullable();
            $table->string('firma_4', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('licencias_giro_visor', function (Blueprint $table) {
            //
        });
    }
}
