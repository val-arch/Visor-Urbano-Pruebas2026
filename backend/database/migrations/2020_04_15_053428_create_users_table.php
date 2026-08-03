<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {    
        Schema::create('subroles', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 250);
            $table->string('descripcion', 250);
            $table->integer('id_municipio')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50); 
            $table->string('apellido_p', 50);
            $table->string('apellido_m', 50)->nullable();
            $table->string('rfc', 50)->nullable();
            $table->string('curp', 50)->nullable();
            $table->string('celular', 50);
            $table->string('email', 100)->unique();
            $table->string('password', 100);
            $table->string('api_token', 100)->nullable();
            $table->datetime('api_token_expiration')->nullable();
            $table->integer('subrole_id')->nullable();
            $table->foreign('subrole_id')->references('id')->on('subroles');
            $table->integer('id_municipio')->nullable();
            $table->timestamps();
            $table->softDeletes();
           
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 20);
            $table->string('descripcion', 200)->nullable();;
            $table->integer('id_municipio')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::table('user_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('role_id');
            $table->foreign('role_id')->references('id')->on('roles');
            $table->integer('role_id_pendiente')->nullable();
            $table->integer('role_status')->nullable();
            $table->string('token')->nullable();
        });
        
    
       

        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('user_roles');

        Schema::enableForeignKeyConstraints();
    }
}
