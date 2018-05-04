<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFichasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fichas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('coleccion');
            $table->string('genero');
            $table->string('marca');
            $table->string('horma');
            $table->string('color');
            $table->string('modelista');
            $table->string('fecha');
            $table->integer('cliente_id')->unsigned();
            $table->foreign('cliente_id')->references('cliId')->on('cliente');
            $table->integer('modelo_id')->unsigned();
            $table->foreign('modelo_id')->references('modId')->on('modelo');
            $table->string('talla');
            $table->string('piezas_cuero');
            $table->string('piezas_forro');
            $table->string('modelaje');
            $table->string('produccion');
            $table->string('gerencia');
            $table->longText('observacion')->nullable();
            $table->string('imagen_derecha');
            $table->string('imagen_izquierda');
            $table->string('imagen_arriba');
            $table->string('imagen_atras');
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
        Schema::drop('fichas');
    }
}
