@extends('layouts.header')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 appdashboard">
            <div class="panel panel-default">
                <div class="panel-heading relative">Examenes
                     <button  type="button" onclick="window.history.go(-1); return false;" class="btn btn-default btn-atras" ><i class="glyphicon glyphicon-arrow-left"></i></button>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <button type="button" class="btn btn-success btn-md"  id="btnRegistry" >Crear Examen</button>
                        </div>
                    </div>
                    <br>
                    <table id="tblexamsn" class="display" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Curso</th>
                                <th>Fecha de Inicio</th>
                                <th>Fecha de Finalizacion</th>
                                <th>Estatus</th>
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
@include('modals.modals')
<script src="../js/listexams.js"></script>
@endsection
