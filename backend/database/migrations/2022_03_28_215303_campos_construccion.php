<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CamposConstruccion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('campos_construccion', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('type', 100);
            $table->longText('description')->nullable(true);
            $table->longText('description_rec')->nullable();
            $table->string('fundamento', 255)->nullable();
            $table->string('opciones', 255)->nullable();
            $table->string('opciones_desc', 255)->nullable();
            $table->integer('step')->nullable();
            $table->integer('secuencia')->nullable();
            $table->integer('requerido')->nullable();
            $table->string('tipo_tramite', 100)->nullable();
            $table->integer('status')->nullable();
            $table->integer('id_municipio')->nullable();
            $table->integer('editable')->nullable()->default(0);
            $table->integer('campo_estatico')->nullable()->default(0);
            $table->string('campo_afectado', 255)->nullable();
            $table->string('condicion_giro', 255)->nullable();
            $table->string('condicion_visible', 255)->nullable();
            $table->string('condicion_dependencia',255)->nullable();
            $table->string('actividad_condicion',255)->nullable();
            $table->integer('actividad_metros')->nullable();
            $table->string('construccion_total_metros_condicion',255)->nullable();
            $table->integer('construccion_total_metros_value')->nullable();
            $table->json('construccion_uso',255)->nullable();
            $table->json('construccion_uso_destino',255)->nullable();
            $table->json('construccion_uso_metros')->nullable();
            $table->string('demolicion_metros_condicion',255)->nullable();
            $table->integer('demolicion_metros_value')->nullable();
            $table->json('destino_construccion')->nullable();
            $table->string('nivel_nuevo_construccion_condicion',255)->nullable();
            $table->integer('nivel_nuevo_construccion_value')->nullable();
            $table->string('sotano_nuevo_construccion_condicion',255)->nullable();
            $table->integer('sotano_nuevo_construccion_value')->nullable();
            $table->string('todasCondiciones',255)->nullable();
            $table->string('viviendas_construccion_condicion',255)->nullable();
            $table->integer('viviendas_construccion_value')->nullable();
            $table->string('pregunta_si_o_no_condicion',255)->nullable();
            $table->integer('pregunta_si_o_no_value')->nullable();
            $table->boolean('mostrar')->default(true)->nullable();
            $table->boolean('info_licencia')->default(false)->nullable();
            $table->integer('tramite_relacionado')->nullable();
            $table->boolean('active')->default(true)->nullable();

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
        //
    }
}
