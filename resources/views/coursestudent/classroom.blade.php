@extends('layouts.header')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading" id="panel-clasroom" data-course="{{$id}}">Aula Virtual <span id="title_course" class="bold"></span></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-6">
                             <div class="panel-heading">Lista de Archivos</div>
                        </div>
                        <div class="col-xs-6">
                            <div class="panel-heading">Examenes</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Clases en Vivo</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="alert alert-info">A continuacion se listan los proximos eventos <b><i>"Streaming"</i></b> del Curso</div>
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Url</th>
                                        <th>Estatus</th>
                                    </tr>
                                </thead>
                                <tbody id="tbstreamings"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="../js/classroom.js"></script>
@endsection