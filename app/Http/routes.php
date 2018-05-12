<?php

Route::auth();

Route::get('login','LoginController@login')->name('login');
Route::get('logout','LoginController@logout')->name('logout');

Route::group(['middleware'=>'auth'], function () {
    Route::get('/', 'HomeController@index')->name('/');
    Route::get('home', 'HomeController@home')->name('home');
    Route::group(['prefix' => 'modelos'], function () {
        Route::get('/', 'ModeloController@index')->name('models');
        Route::post('/crear', 'ModeloController@create')->name('models.create');
        Route::post('/editar', 'ModeloController@edit')->name('models.edit');
        Route::post('/eliminar', 'ModeloController@delete')->name('models.delete');
        Route::get('/{model_description}', 'ModeloController@model_description')->name('models.model');

        Route::group(['prefix' => 'imagenes'], function () {
            Route::get('/{modId}', 'ModeloController@images')->name('models.images.model');
            Route::post('/crear', 'ModeloController@uploadImage')->name('models.images.create');
            Route::post('/editar', 'ModeloController@editImage')->name('models.images.edit');
            Route::post('/eliminar', 'ModeloController@deleteImage')->name('models.images.delete');
        });
    });

    Route::group(['prefix' => 'areas'], function () {
        Route::get('/', 'AreaController@index')->name('areas');
        Route::get('/list/{position}', 'AreaController@areas')->name('areas.list');
        Route::post('/crear', 'AreaController@create')->name('areas.create');
        Route::post('/editar', 'AreaController@edit')->name('areas.edit');
        Route::post('/eliminar', 'AreaController@delete')->name('areas.delete');

        Route::group(['prefix' => 'subareas'], function () {
            Route::get('/{areId}', 'AreaController@subareas')->name('areas.subareas.area');
            Route::post('/crear', 'SubareaController@create')->name('areas.subareas.create');
            Route::post('/editar', 'SubareaController@edit')->name('areas.subareas.edit');
            Route::post('/eliminar', 'SubareaController@delete')->name('areas.subareas.delete');

            Route::group(['prefix' => 'subareas-menores'], function () {
                Route::get('/areas-activas', 'SubAreaMenorController@areas')->name('areas.subareas.subareas_menores.areas');
                Route::get('/subareas-activas/{areId}', 'SubAreaMenorController@subareas_activas')->name('areas.subareas.subareas_menores.subareas');
                Route::get('/subarea/{subaId}', 'SubAreaMenorController@subareas_menores')->name('areas.subareas.subareas_menores');
                Route::get('/tipo-calculos', 'SubAreaMenorController@tipo_calculos_activos')->name('areas.subareas.subareas_menores.tipo_calculos_activos');
                Route::post('/crear', 'SubAreaMenorController@create')->name('areas.subareas.subareas_menores.create');
                Route::post('/editar', 'SubAreaMenorController@edit')->name('areas.subareas.subareas_menores.edit');
                Route::post('/eliminar', 'SubAreaMenorController@delete')->name('areas.subareas.subareas_menores.delete');
            });
        });
    });

    Route::group(['prefix' => 'trabajadores'], function () {
        Route::get('/', 'TrabajadorController@index')->name('trabajadores');
        Route::post('/crear', 'TrabajadorController@create')->name('trabajadores.create');
        Route::post('/editar', 'TrabajadorController@edit')->name('trabajadores.edit');
        Route::post('/eliminar', 'TrabajadorController@delete')->name('trabajadores.delete');
        Route::get('/search-dni/{trabajador_dni}/{subarea_id}', 'TrabajadorController@search_dni')->name('trabajadores.search_dni');
        Route::get('/tipo-trabajos', 'TrabajadorController@type_works')->name('trabajadores.type_works');
        Route::get('/subarea/{subaId}', 'TrabajadorController@workers')->name('trabajadores.subarea.workers');
        Route::get('/excel', 'TrabajadorController@workers_excel')->name('trabajadores.workers_excel');
    });

    Route::group(['prefix' => 'tipo_calculos'], function () {
        Route::get('/', 'TipoCalculoController@index')->name('tipo_calculos');
        Route::get('/list/{position}', 'TipoCalculoController@tipo_calculos')->name('tipo_calculos.tipo_calculos');
        Route::post('/crear', 'TipoCalculoController@create')->name('tipo_calculos.create');
        Route::post('/editar', 'TipoCalculoController@edit')->name('tipo_calculos.edit');
        Route::post('/eliminar', 'TipoCalculoController@delete')->name('tipo_calculos.delete');
    });

    Route::group(['prefix' => 'precio_area'], function () {
        Route::get('/', 'PrecioAreaController@index')->name('precio_area');
        Route::get('/subareas/{areId}', 'PrecioAreaController@subareas')->name('precio_area.subareas');
        Route::get('/areas-menores/{subaId}', 'PrecioAreaController@subareas_menores')->name('precio_area.subareas_menores');

        Route::get('/datos/{subamId}/{tipocalId}/{nivId?}', 'PrecioAreaController@data')->name('precio_area.data');
        Route::get('/niveles/{subamId}/{tipocalId}/{description_id}', 'PrecioAreaController@levels')->name('precio_area.levels');
        Route::post('/datos/crear', 'PrecioAreaController@create')->name('precio_area.create');
        Route::post('/datos/editar', 'PrecioAreaController@edit')->name('precio_area.edit');
        Route::post('/datos/eliminar', 'PrecioAreaController@delete')->name('precio_area.delete');
        Route::get('/piezas/{description_id}', 'PrecioAreaController@piezas')->name('precio_area.piezas');

        Route::group(['prefix' => 'niveles'], function () {
            Route::get('/list/{subamId}/{tipocalId}/{description_id}', 'PrecioAreaController@levels_list')->name('precio_area.nivel.list');
            Route::post('/crear', 'PrecioAreaController@level_create')->name('precio_area.nivel.create');
            Route::post('/editar', 'PrecioAreaController@level_edit')->name('precio_area.nivel.edit');
            Route::post('/eliminar', 'PrecioAreaController@level_delete')->name('precio_area.nivel.delete');
        });
    });

    Route::group(['prefix' => 'piezas'], function () {
        Route::get('/', 'PiezaController@index')->name('piezas');
        Route::get('/list/{description_id}', 'PiezaController@pieza_list')->name('piezas.list');
        Route::post('/create', 'PiezaController@create')->name('piezas.create');
        Route::post('/edit', 'PiezaController@edit')->name('piezas.edit');
        Route::post('/delete', 'PiezaController@delete')->name('piezas.delete');

        Route::get('/piezas-validate-name', 'PiezaController@validate_name')->name('piezas.validate_name');
        Route::get('/piezas-validate-start', 'PiezaController@validate_start')->name('piezas.validate_start');
        Route::get('/piezas-validate-end', 'PiezaController@validate_end')->name('piezas.validate_end');
    });

    Route::group(['prefix' => 'modelo-tipo'], function () {
        Route::get('/{perfilado?}', 'ModeloTipoController@index')->name('modelo_tipo');
        Route::get('/modelos/{model_description}', 'ModeloTipoController@model_description')->name('modelo_tipo.modelos');
        Route::post('/create', 'ModeloTipoController@create')->name('modelo_tipo.create');
    });

    Route::group(['prefix' => 'descripcion'], function () {
        Route::get('/data', 'DescriptionController@data')->name('description.data');
        Route::post('/create', 'DescriptionController@create')->name('description.create');
        Route::post('/edit', 'DescriptionController@edit')->name('description.edit');
        Route::post('/delete', 'DescriptionController@delete')->name('description.delete');
    });

    Route::group(['prefix' => 'planillas'], function () {
        Route::get('/', 'PlanillaController@index')->name('planillas');
        Route::get('/list', 'PlanillaController@planillas')->name('planillas.list');
        Route::get('/filter/{fecha_inicio}/{fecha_fin}', 'PlanillaController@filter')->name('planillas.filter');
        Route::post('/create', 'PlanillaController@create')->name('planillas.create');
        Route::post('/edit', 'PlanillaController@edit')->name('planillas.edit');
        Route::post('/delete', 'PlanillaController@delete')->name('planillas.delete');

        Route::get('/subareas-menores/{planilla_id}', 'PlanillaController@subareas_menores')->name('planillas.subareas.menores');
        Route::get('/trabajadores/{planilla_id}/{subarea_menor_id}', 'PlanillaController@trabajadores')->name('planillas.trabajadores');
        Route::get('/pagos/{planilla_id}/{subarea_menor_id}/{trabajador_id}', 'PlanillaController@pago')->name('planillas.pago');
    });

    Route::group(['prefix' => 'programacion'], function () {
        Route::get('/', 'ProgramacionController@index')->name('programacion');
        Route::get('/detalles/{programacion_id}', 'ProgramacionController@details')->name('programacion.details');
        Route::get('/{cliId}/{ordId}/{pedId}', 'ProgramacionController@programaciones')->name('programacion.list');
        Route::post('/create', 'ProgramacionController@create')->name('programacion.create');
        Route::post('/order/create', 'ProgramacionController@order_create')->name('programacion.order_create');
        Route::post('/order/delete', 'ProgramacionController@delete_order')->name('programacion.delete_order');

        //MATERIALS
        Route::post('/material/create', 'ProgramacionController@create_material')->name('programacion.create_material');
        Route::post('/material/delete', 'ProgramacionController@delete_material')->name('programacion.delete_material');

    });

    Route::group(['prefix' => 'inicio_trabajo'], function () {
        Route::get('/', 'InicioTrabajoController@index')->name('inicio_trabajo');
        Route::get('/create', 'InicioTrabajoController@create')->name('inicio_trabajo.create');
        Route::post('/store', 'InicioTrabajoController@store')->name('inicio_trabajo.store');
        Route::get('/update/{inicio_id}', 'InicioTrabajoController@update')->name('inicio_trabajo.update');
        Route::post('/edit', 'InicioTrabajoController@edit')->name('inicio_trabajo.edit');
        Route::post('/delete', 'InicioTrabajoController@delete')->name('inicio_trabajo.delete');

        Route::get('/data/worker/{worker_code}', 'InicioTrabajoController@area_subarea_type_work')->name('inicio_trabajo.trabajador.data');
        Route::get('/change/type-wor/{worker_code}/{type_work}', 'InicioTrabajoController@change_type_work')->name('inicio_trabajo.trabajador.change_type_work');
        Route::get('/search/order/{order_code}', 'InicioTrabajoController@search_order')->name('inicio_trabajo.orden.search_order');
    });

    Route::group(['prefix' => 'termino_trabajo'], function () {
        Route::get('/', 'TerminoTrabajoController@index')->name('termino_trabajo');
        Route::get('/create', 'TerminoTrabajoController@create')->name('termino_trabajo.create');
        Route::post('/store', 'TerminoTrabajoController@store')->name('termino_trabajo.store');
        Route::get('/update/{inicio_id}', 'TerminoTrabajoController@update')->name('termino_trabajo.update');
        Route::post('/edit', 'TerminoTrabajoController@edit')->name('termino_trabajo.edit');
        Route::post('/delete', 'TerminoTrabajoController@delete')->name('termino_trabajo.delete');

        Route::get('/data/worker/{worker_code}', 'TerminoTrabajoController@area_subarea_type_work')->name('termino_trabajo.trabajador.data');
        Route::get('/change/type-wor/{worker_code}/{type_work}', 'TerminoTrabajoController@change_type_work')->name('termino_trabajo.trabajador.change_type_work');
        Route::get('/search/order/{worker_code}/{order_code}', 'TerminoTrabajoController@search_order')->name('termino_trabajo.orden.search_order');
    });

    Route::group(['prefix' => 'reporte'], function () {
        Route::get('op_general', 'ProgramacionController@op_general')->name('reporte.op_general');
        Route::get('op_grande/{produccion_id}', 'ProgramacionController@op_grande')->name('reporte.op_grande');
        Route::get('op_chica/{produccion_id}', 'ProgramacionController@op_chica')->name('reporte.op_chica');
    });

    Route::get('precios', function () {
        $modelos = \App\Models\Modelo::all();

        $precios = \App\Models\Subarea::
        join('subaream as sm', 'subarea.subaId', '=', 'sm.subaId')->
        join('nivel as n', 'sm.subamId', '=', 'n.subamId')->
        join('ddatoscalculo as d', 'n.nivId', '=', 'd.nivId')->
        select('d.ddatcNombre')->
        distinct('d.ddatcNombre')->
        where(['n.nivFlag' => 0])->
        whereNull('n.description_id')->
        orderBy('d.ddatcNombre')->get();

        $lista_precios = [];
        $lista_descripciones = [];
        foreach ($precios as $precio) {
            array_push($lista_precios, $precio['ddatcNombre']);
            array_push($lista_descripciones, $precio['ddatcNombre']);
        }

        $descriptions = Illuminate\Support\Facades\DB::table('subarea')->
        join('subaream as sm', 'subarea.subaId', '=', 'sm.subaId')->
        join('nivel as n', 'sm.subamId', '=', 'n.subamId')->
        join('ddatoscalculo as d', 'n.nivId', '=', 'd.nivId')->
        join('descriptions', 'descriptions.id', '=', 'n.description_id')->
        select('descriptions.description')->
        distinct('descriptions.description')->get();

        if (count($descriptions)) {
            foreach ($descriptions as $description) {
                if (!in_array($description->description, $lista_descripciones))
                    array_push($lista_precios, $description->description);
            }
        }

        $lista_precios = array_unique($lista_precios);

        return view('pruebas')->with(compact('modelos', 'lista_precios'));
    })->name('precios');

    Route::get('precios/{modelo}/{descripcion}/{pares}', function ($modelo, $descripcion, $pares) {
        $subareas_menores = \App\Models\SubareaMenor::
        join('subarea', 'subaream.subaId', '=', 'subarea.subaId')->
        join('area', 'subarea.areId', '=', 'area.areId')->
        where('subamEstado', 1)->where('subaEstado', 1)->where('areEstado', 1)->
        select('subaream.subamId', 'area.areNombre as area', 'subarea.subaDescripcion as subarea', 'subamDescripcion as subarea_menor')->get();

        foreach ($subareas_menores as $subareas_menor) {
            $subareas_menor->monto = $subareas_menor->monto($subareas_menor->subamId, $modelo, $descripcion, $pares);
        }

        return ['data' => $subareas_menores];
    })->name('url-to-prices');

    Route::group(['prefix' => 'precio-referencial'], function () {
        Route::get('/', 'ReferentialPriceController@index')->name('precio.referencial');
        Route::post('/store', 'ReferentialPriceController@store')->name('precio.referencial.store');
    });


    Route::group(['prefix' => 'ficha-tecnica'], function () {
        Route::get('/', 'RecordController@index')->name('ficha.tecnica');
        Route::get('/create', 'RecordController@create')->name('ficha.tecnica.create');
        Route::post('/store', 'RecordController@store')->name('ficha.tecnica.store');
        Route::get('/show/{id}', 'RecordController@show')->name('ficha.tecnica.show');
    });

    Route::group(['prefix' => 'ficha-ventas'], function () {
        Route::get('/', 'RecordController@indexSales')->name('ficha.ventas');
        Route::get('/create', 'RecordController@createSales')->name('ficha.ventas.create');
        Route::post('/store', 'RecordController@storeSales')->name('ficha.ventas.store');
        Route::get('/show/{id}', 'RecordController@showSales')->name('ficha.ventas.show');

    });
});