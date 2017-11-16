@extends('layouts.header')
@include('modals.modals')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 appdashboard">
            <div class="panel panel-default">
                <div class="panel-heading" id="title_aula" data-user='{{ $user }}'>Aula Virtual</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-12 text-right">
                            <button type="button" class="btn btn-success btn-md" data-toggle="modal" id="btnRegistry" >Registrar Evento</button>
                        </div>
                    </div>
                    <br>
                    <table id="tblstreamings" class="display" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Curso</th>
                                <th>Descripcion</th>
                                <th>Url</th>
                                <th>Fecha de Inicio</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="../js/liststreaming.js"></script>
@endsection
