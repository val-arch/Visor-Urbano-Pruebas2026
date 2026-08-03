<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFirmaToFirmasMunicipios extends Migration{
    
    public function up(){
        Schema::table('firmas_municipios', function (Blueprint $table) {
            $table->string('firma',255)->nullable();
        });
    }

    public function down(){
      //  $table->dropColumn('firma');
    }
}