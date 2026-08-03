<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NotificacionesConstruccion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('notificaciones_construccion', function (Blueprint $table) {
            $table->id();
            $table->string('folio',255);
            $table->integer('id_usuario')->nullable();
            $table->string('email_solicitante', 100);
            $table->string('comentario', 300)->nullable();
            $table->longText('archivo')->nullable(true);
            $table->dateTime('fecha_creacion');
            $table->dateTime('fecha_visto')->nullable();
            $table->longText('archivo_dependencia')->nullable(true);
            $table->integer('notificado')->nullable();
            $table->integer('dependencia_notifica')->nullable();
            $table->integer('type')->nullable();
            $table->integer('id_solventacion')->nullable()->default(0);
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
