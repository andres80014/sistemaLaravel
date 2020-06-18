<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',20)->unique();
            $table->string('descripcion',60)->nullable();
            $table->boolean('condicion')->default(1);

        });
        \Illuminate\Support\Facades\DB::table('roles')->insert(array('id'=>1,'nombre'=>'Administrador','descripcion'=>'Admin del sistema'));
        \Illuminate\Support\Facades\DB::table('roles')->insert(array('id'=>2,'nombre'=>'Vendedor','descripcion'=>'Vendedor area ventas'));
        \Illuminate\Support\Facades\DB::table('roles')->insert(array('id'=>3,'nombre'=>'Almacenero','descripcion'=>'Almacenero area compras'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles_tables');
    }
}
