<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAlcoholToConsultaRequisitosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('consulta_requisitos', function (Blueprint $table) {
            $table->integer('venta_alcohol')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('consulta_requisitos', function (Blueprint $table) {
            $table->dropColumn('venta_alcohol');
        });
    }
}
