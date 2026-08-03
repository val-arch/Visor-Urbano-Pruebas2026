<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFechasLicToLicengiasGiroVisorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('licencias_giro_visor', function (Blueprint $table) {
            $table->dateTime('fecha_pago', 0)->nullable();
            $table->dateTime('fecha_baja', 0)->nullable();
            $table->string('folio_secundario',200)->default('')->nullable();
            $table->longText('motivo_baja')->nullable();
            $table->integer('id_usuario_baja')->default(0)->nullable();
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
            $table->dropColumn('fecha_pago');
            $table->dropColumn('fecha_baja');
            $table->dropColumn('folio_secundario');
            $table->dropColumn('motivo_baja');
            $table->dropColumn('id_usuario_baja');
        });
    }
}
