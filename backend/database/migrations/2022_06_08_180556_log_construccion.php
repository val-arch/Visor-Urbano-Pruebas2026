<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LogConstruccion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('log_construccion', function (Blueprint $table) {
            $table->id();
            $table->string('accion',255)->nullable();
            $table->integer('id_usuario')->nullable();
            $table->string('anterior',1000)->nullable();
            $table->integer('id_tramite')->nullable();
            $table->string('host',255)->nullable();
            $table->string('ip_user',255)->nullable();
            $table->string('post_request',1000)->nullable();
            $table->string('equipo',255)->nullable();
            $table->integer('tipo_log')->nullable();
            $table->integer('id_role')->nullable();
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
