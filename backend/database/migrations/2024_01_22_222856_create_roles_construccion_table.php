<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesConstruccionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles_construccion', function (Blueprint $table) {
            $table->id();
            $table->string('name', 20);
            $table->string('descripcion', 200)->nullable();;
            $table->integer('id_municipio')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('user_roles_construccion', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::table('user_roles_construccion', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('role_id');
            $table->foreign('role_id')->references('id')->on('roles_construccion');
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
        Schema::dropIfExists('roles_construccion');
    }
}
