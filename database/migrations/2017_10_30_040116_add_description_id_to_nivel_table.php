<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDescriptionIdToNivelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nivel',function (Blueprint $table){
            $table->integer('description_id')->unsigned()->after('nivId')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nivel',function (Blueprint $table){
            $table->dropColumn('description_id');
        });
    }
}
