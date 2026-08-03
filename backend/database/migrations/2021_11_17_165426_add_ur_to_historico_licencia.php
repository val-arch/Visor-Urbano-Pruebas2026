<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUrToHistoricoLicencia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('historico_licencias_giro', function (Blueprint $table) {
            //
           $table->longText('url_minimapa')->nullable();
        });
    }

    public function down()
    {
        Schema::table('historico_licencias_giro', function (Blueprint $table) {
            //
        });
    }
}
