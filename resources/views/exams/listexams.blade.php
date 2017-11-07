@extends('layouts.header')
@include('modals.modals')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Examenes</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <button type="button" class="btn btn-success btn-md" data-toggle="modal" id="btnRegistry" >Crear Examen</button>
                        </div>
                    </div>
                    <br>
                    <table id="tblexamsn" class="display" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Curso</th>
                                <th>Nombre</th>
                                <th>Descripcion</th>
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
<script src="js/listexams.js"></script>
@endsection
