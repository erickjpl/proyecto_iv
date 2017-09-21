@extends('layouts.header')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Estudiantes</div>
                <div class="panel-body">
					<table id="tblstudents" class="display" cellspacing="0" width="100%">
				        <thead>
				            <tr>
				                <th>Nombre</th>
				                <th>Apellido</th>
				                <th>Email</th>
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
<script src="js/students.js"></script>
@endsection