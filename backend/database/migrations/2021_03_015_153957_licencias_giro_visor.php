<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LicenciasGiroVisor extends Migration{
    
    public function up(){
        Schema::create('licencias_giro_visor', function (Blueprint $table) {
            $table->id();
            $table->string('dueno',200);
            $table->string('folio',200);
            $table->string('actividad_comercial',200);
            $table->string('codigo_scian',200);
            $table->string('superficie_autorizada',200);
            $table->string('hora_a',200);
            $table->string('hora_c',200);
            $table->string('apellido_p',200)->nullable();
            $table->string('apellido_m',200)->nullable();
            $table->string('curp',200)->nullable();
            $table->string('caracter',200)->nullable();
            $table->longText('img_logo')->nullable();
            $table->longText('firma')->nullable();
            $table->longText('url_minimapa')->nullable();
            $table->longText('pdf_escaneado')->nullable();
            $table->year('anio_licencia')->default();
            $table->integer('tipo')->default(1)->nullable();
            $table->integer('id_usuario_genero')->default();
          //  $table->text('archivo_compobante_pago')->nullable();;
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(){
        Schema::dropIfExists('licencias_giro_visor');
    }
}