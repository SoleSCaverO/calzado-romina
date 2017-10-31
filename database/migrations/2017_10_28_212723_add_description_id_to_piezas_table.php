<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDescriptionIdToPiezasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pieza',function (Blueprint $table){
            $table->integer('description_id')->unsigned()->after('pieId')->nullable();

            # Noermite crear clave foránea porque Pieza ya está creada y descripciones recién
            #$table->foreign('description_id')->references('id')->on('descriptions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pieza',function (Blueprint $table){
            # $table->dropForeign('pieza_description_id_foreign');
            $table->dropColumn('description_id');
        });
    }
}
