<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGenerateLicenciaVentanillaToMunicipiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('municipios', function (Blueprint $table) {
            $table->integer('generar_licencia_ventanilla')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('municipios', function (Blueprint $table) {
            $table->dropColumn('generar_licencia_ventanilla');
        });
    }
}
