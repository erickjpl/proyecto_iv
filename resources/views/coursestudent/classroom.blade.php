@extends('layouts.header')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading" id="panel-clasroom" data-course="{{$id}}">Aula Virtual <span id="title_course" class="bold"></span></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-5">
                             <div class="" id="scroll-files">
                                <table class="table table-striped table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Archivos</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbfiles"></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-xs-7">
                            <div class="panel-heading bold">Descripción</div>
                            <div id="descripcionfile"></div>
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
                            <div class="alert alert-info">A continuacion se listan los proximos eventos <b><i>"Streaming"</i></b>del Curso&nbsp;&nbsp;
                            <button id="refresh" class="btn btn-primary"><i class="glyphicon glyphicon-refresh" title="Refrescar"></i></button></div>
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