<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFichaSemadetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ficha_semadet', function (Blueprint $table) {
            $table->id();
            $table->text('img');
            $table->string('uuid');
            $table->string('clave');
            $table->string('clave_1');
            $table->string('id_uga');
            $table->longtext('coordenadas');
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
        Schema::dropIfExists('ficha_semadet');
    }
}
