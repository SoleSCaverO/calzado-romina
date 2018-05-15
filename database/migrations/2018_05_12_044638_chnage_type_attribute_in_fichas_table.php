<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChnageTypeAttributeInFichasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::Statement("ALTER TABLE `fichas` 
                      CHANGE COLUMN `coleccion` `coleccion` VARCHAR(255) NULL ,
                      CHANGE COLUMN `horma` `horma` VARCHAR(255) NULL ,
                      CHANGE COLUMN `piezas_cuero` `piezas_cuero` VARCHAR(255) NULL ,
                      CHANGE COLUMN `piezas_forro` `piezas_forro` VARCHAR(255) NULL ,
                      CHANGE COLUMN `modelaje` `modelaje` INT NULL ,
                      CHANGE COLUMN `produccion` `produccion` INT NULL ,
                      CHANGE COLUMN `gerencia` `gerencia` INT NULL ;"
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::Statement("ALTER TABLE `fichas` 
                      CHANGE COLUMN `coleccion` `coleccion` VARCHAR(255) NOT NULL ,
                      CHANGE COLUMN `horma` `horma` VARCHAR(255) NOT NULL ,
                      CHANGE COLUMN `piezas_cuero` `piezas_cuero` VARCHAR(255) NOT NULL ,
                      CHANGE COLUMN `piezas_forro` `piezas_forro` VARCHAR(255) NOT NULL ,
                      CHANGE COLUMN `modelaje` `modelaje` VARCHAR(255) NOT NULL ,
                      CHANGE COLUMN `produccion` `produccion` VARCHAR(255) NOT NULL ,
                      CHANGE COLUMN `gerencia` `gerencia` VARCHAR(255) NOT NULL ;"
        );
    }
}
