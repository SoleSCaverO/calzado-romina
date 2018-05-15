<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddDatesUpdatesFichasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fichas', function (Blueprint $table){
            $table->dateTime('fecha_modelaje')->after('gerencia')->nullable();
            $table->dateTime('fecha_produccion')->after('fecha_modelaje')->nullable();
            $table->dateTime('fecha_gerencia')->after('fecha_produccion')->nullable();
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
