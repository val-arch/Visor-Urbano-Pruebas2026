<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NotariosPublicos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('notarios_publicos', function (Blueprint $table) {
            $table->id();
            $table->string('idFedatario',255);
            $table->string('nombre',255);
            $table->string('estado',255);
            $table->string('direccion',255);
            $table->string('cp',255);
            $table->string('telefono',255);
            $table->string('descripcionFedatario',255);
            $table->string('numNotaria',255);
            $table->string('delMunicipio',255);

         
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
        //
    }
}
