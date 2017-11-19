@extends('layouts.header')
@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12 appdashboard">
            <div class="panel panel-default">
                <div class="panel-heading relative" >Estudiantes
                	<button type="button" onclick="window.history.go(-1); return false;" class="btn btn-default btn-atras" ><i class="glyphicon glyphicon-arrow-left"></i></button>
                </div>
                <div class="panel-body">
					<table id="tblstudents" class="display" cellspacing="0" width="100%">
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
@include('modals.modals')
<script src="js/students.js"></script>
@endsection