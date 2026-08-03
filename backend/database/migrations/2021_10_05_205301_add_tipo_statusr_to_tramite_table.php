<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTipoStatusrToTramiteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tramite', function (Blueprint $table) {
            $table->string('tipo')->after('status')->nullable();;
            $table->string('status_licencia')->after('status')->nullable();
            $table->string('motivo')->after('status')->nullable();;
            //
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tramite', function (Blueprint $table) {
            //
        });
    }
}
