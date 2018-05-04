<?php
use App\Models\FichaArea;
use Illuminate\Database\Seeder;

class FichaAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FichaArea::create(['nombre'=>'CUERO','tipo'=>1]);
        FichaArea::create(['nombre'=>'FORRO','tipo'=>1]);
        FichaArea::create(['nombre'=>'PLANTILLA','tipo'=>1]);
        FichaArea::create(['nombre'=>'PERFILADO']);
        FichaArea::create(['nombre'=>'COSIDO VENA']);
        FichaArea::create(['nombre'=>'PEGADO']);
        FichaArea::create(['nombre'=>'ARMADO']);
        FichaArea::create(['nombre'=>'ENCAJADO']);
        FichaArea::create(['nombre'=>'HAB. PLANTILLA']);
    }
}
