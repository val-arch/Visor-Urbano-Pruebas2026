<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableApoyosEconomicos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apoyos_economicos', function (Blueprint $table) {
            $table->id();
            $table->string('dependencia',200)->default(0)->nullable();
            $table->integer('scian')->default(0)->nullable();
            $table->string('nombre_programa',200)->default('')->nullable();
            $table->string('url',255)->default('')->nullable();
            $table->longText('descripcion_programa')->nul;
            $table->softDeletes();
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
        Schema::dropIfExists('apoyos_economicos');
    }
}
