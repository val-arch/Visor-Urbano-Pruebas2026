<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusPagoToLicenciasVisorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('licencias_giro_visor', function (Blueprint $table) {
            $table->integer('status_pago')->default(0)->nullable();
            $table->integer('id_usuario_pago')->default(0)->nullable();
            $table->integer('status_baja')->default(0)->nullable();
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
            $table->dropColumn('status_pago');
            $table->dropColumn('id_usuario_pago');
            $table->dropColumn('status_baja');
        });
    }
}
