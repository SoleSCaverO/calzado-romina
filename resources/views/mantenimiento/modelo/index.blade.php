@extends('layouts.app')
@section('title','Modelos')

@section('styles')
    <style>
        .imagen{
            width: 480px;
            height: 400px;
        }
         .carousel_image{
             width: auto;
             height: 450px;
             max-height: 450px;
         }

    </style>
@endsection

@section('content')
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>
                    Listado de modelos
                </h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="col-md-12 table-responsive">
                    <table id="dynamic-table-modelos" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Género</th>
                                <th>Línea</th>
                                <th>Imágenes</th>
                            </tr>
                        </thead>
                        <tbody id="table-modelo-imagenes">
                            @foreach( $modelos as $modelo )
                                <tr data-modelo_id="{{ $modelo->modId }}">
                                    <td>{{ $modelo->modDescripcion }}</td>
                                    <td>{{ $modelo->genero }}</td>
                                    <td>{{ $modelo->linea  }}</td>
                                    @if( count($modelo->imagenes)>0 )
                                        <td>
                                            <button class="btn btn-info btn-sm" data-galeria_modelo_id="{{ $modelo->modId }}">
                                                <i class="fa fa-picture-o"></i> Imágenes
                                            </button>
                                        </td>
                                    @else
                                        <td id="show_button_gallery{{$modelo->modId}}">

                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <input type="hidden" id="modelo-url" value="{{ route('models.images.model','') }}">
                <input type="hidden" id="images-folder" value="{{ asset('images/modelo/') }}">
                <input type="hidden" id="modelo-descripcion" value="{{ route('models.model','') }}">
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>
                    Imágenes
                </h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="col-md-12">
                    <div id="div-button-imagen-crear">

                    </div>
                </div>
                <div class="col-md-12 table-responsive">
                    <table class="table table-striped" id="table">
                        <thead>
                        <tr>
                            <th>Imagen</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>
                        </thead>
                        <tbody id="images-table">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    <div id="modal-modelo-imagen-crear" class="modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>
                        Nueva imágen
                    </h2>
                </div>
                <form id="form-modelo-imagen-crear" action="{{route('models.images.create')}}" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    {{ csrf_field() }}
                    <input type="hidden" name="modelo_id">
                    <input class="form-control" type="file" name="modelo_imagen" id="modelo-imagen" accept="image/*" required>
                    <img class="img-responsive" id="modelo-imagen-preview" alt=""  />
                    <br>
                    <div class="row">
                        <div class="col-md-1">
                            <input type="checkbox"  name="imagen_estado" class="form-control" checked>
                        </div>

                        <div class="col-md-11">
                            <label class="beside_check">Mostrar</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-diamond" aria-hidden="true"></i> Aceptar</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modal-modelo-imagen-mostrar" class="modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>
                        Imágen
                    </h2>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 text-center" id="mostrar-imagen">

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Salir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div id="modal-modelo-imagen-galeria" class="modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h2>
                        Galería de imágenes
                    </h2>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row">
                            <div id="galeria" class="carousel">
                                <ol class="carousel-indicators" id="galeria-lista">

                                </ol>
                                <div class="carousel-inner" role="listbox" id="galeria-imagenes">

                                </div>
                                <a class="left carousel-control" href="#galeria" role="button" data-slide="prev">
                                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                    <span class="sr-only">Anterior</span>
                                </a>
                                <a class="right carousel-control" href="#galeria" role="button" data-slide="next">
                                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                    <span class="sr-only">Siguiente</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Salir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modal-modelo-imagen-editar" class="modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>
                        Modificar imágen
                    </h2>
                </div>
                <form id="form-modelo-imagen-editar" action="{{route('models.images.edit')}}" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input type="hidden" name="modelo_id">
                        <input type="hidden" name="imagen_id">
                        <input class="form-control" type="file" name="modelo_imagen" id="modelo-imagen-edit" data-rule-extension="png|jpg">
                        <img class="img-responsive" name="modelo_imagen_preview_edit" id="modelo-imagen-preview-edit" alt=""  />
                        <div class="row">
                            <div class="col-md-1" id="image_estado_check">

                            </div>

                            <div class="col-md-11" id="beside_check">
                                <label class="beside_check">Mostrar</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-diamond" aria-hidden="true"></i> Aceptar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modal-modelo-imagen-eliminar" class="modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>
                        Eliminar imágen
                    </h2>
                </div>
                <form id="form-modelo-imagen-eliminar" action="{{route('models.images.delete')}}" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input type="hidden" name="modelo_id">
                        <input type="hidden" name="imagen_id">
                        <label>Seguro que desea eliminar la siguiente imagen?</label>
                        <img class="img-responsive" name="modelo_imagen_preview_delete" id="modelo-imagen-preview-delete" alt=""  />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-diamond" aria-hidden="true"></i> Aceptar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/mantenimiento/modelo/index.js?v=1') }}"></script>
@endsection
