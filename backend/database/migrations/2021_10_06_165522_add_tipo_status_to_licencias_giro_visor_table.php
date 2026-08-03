<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTipoStatusToLicenciasGiroVisorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('licencias_giro_visor', function (Blueprint $table) {
            $table->string('tipo_licencia')->after('status')->nullable();
            $table->string('status_licencia')->after('status')->nullable();
            $table->string('motivo')->after('status')->nullable();
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
