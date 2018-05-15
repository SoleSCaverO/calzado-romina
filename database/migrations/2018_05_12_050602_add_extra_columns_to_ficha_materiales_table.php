<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddExtraColumnsToFichaMaterialesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ficha_materiales', function (Blueprint $table){
            $table->string('columna')->after('area_id')->nullable();
            $table->string('color')->after('nombre')->nullable();
            $table->string('cantidad')->after('color')->nullable();
            $table->string('extra_perfilado')->after('piezas')->nullable();
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
