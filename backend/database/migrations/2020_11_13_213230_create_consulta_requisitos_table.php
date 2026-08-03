<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsultaRequisitosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consulta_requisitos', function (Blueprint $table) {
            $table->id();
            $table->string('folio',255)->nullable()->unique();
            $table->string('calle',100);
            $table->string('colonia',100);
            $table->string('municipio',50);
            $table->integer('id_municipio');
            $table->string('codigo_scian',100);
            $table->string('nombre_scian',100);
            $table->decimal('superficie_propiedad',8,2)->nullable()->default(0.0);
            $table->decimal('superficie_actividad',8,2)->nullable()->default(0.0);
            $table->string('nombre_solicitante',100)->nullable();
            $table->string('caracter_solicitante',100)->nullable();
            $table->string('tipo_persona',100)->nullable();
            $table->longText('url_minimapa')->nullable();
            $table->json('restricciones')->nullable();
            $table->integer('status')->default(1);
            $table->integer('id_usuario')->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consulta_requisitos');
    }
}
