<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReferentialPriceToDetalleModeloDatos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detalle_modelo_datos', function ( Blueprint $table ){
            $table->decimal('referential_price',6,2)->after('modId')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('detalle_modelo_datos', function ( Blueprint $table ){
            $table->dropColumn('referential_price');
        });
    }
}
