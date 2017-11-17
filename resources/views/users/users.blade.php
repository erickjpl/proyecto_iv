@extends('layouts.header')
@include('modals.modals')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 appdashboard">
            <div class="panel panel-default">
                <div class="panel-heading relative">Usuarios
                    <button type="button" onclick="window.history.go(-1); return false;" class="btn btn-default btn-atras" ><i class="glyphicon glyphicon-arrow-left"></i></button>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <button type="button" class="btn btn-success btn-md" data-toggle="modal" id="btnRegistry" >Registrar Usuario</button>
                        </div>
                    </div>
                    <br>
                    <table id="tblusers" class="display" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Email</th>
                                <th>Estatus</th>
                                <th>Activo</th>
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
<script src="js/users.js"></script>
@endsection
