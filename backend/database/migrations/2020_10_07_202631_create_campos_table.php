<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCamposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campos', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('type', 100);
            $table->longText('description')->nullable(true);
            $table->longText('description_rec')->nullable();
            $table->string('fundamento',255)->nullable();
            $table->string('opciones',255)->nullable();
            $table->string('opciones_desc',255)->nullable();
            $table->integer('step')->nullable();
            $table->integer('secuencia')->nullable();
            $table->integer('requerido')->nullable();
            $table->string('condicion_visible',255)->nullable();
            $table->string('campo_afectado',100)->nullable();
            $table->string('tipo_tramite',100)->nullable();
            $table->string('condicion_dependencia',255)->nullable();
            $table->string('condicion_giro',255)->nullable();
            $table->integer('status')->nullable();
            $table->integer('id_municipio')->nullable();
            $table->integer('editable')->nullable()->default(0);
            $table->integer('campo_estatico')->nullable()->default(0);
            // $table->integer('requerido_funcionario')->nullable()->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campos');
    }
}
