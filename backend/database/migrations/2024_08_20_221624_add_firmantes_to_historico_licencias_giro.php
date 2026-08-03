<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFirmantesToHistoricoLicenciasGiro extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('historico_licencias_giro', function (Blueprint $table) {
            $table->json('firmantes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('historico_licencias_giro', function (Blueprint $table) {
            $table->dropColumn('firmantes');
        });
    }
}
