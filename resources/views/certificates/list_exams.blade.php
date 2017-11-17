@extends('layouts.header')
@include('modals.modals')
@section('content')
<style>
.btn span.glyphicon {               
    opacity: 0;             
}
.btn.active span.glyphicon {                
    opacity: 1;             
}
</style>
<div class="container">
    <div class="row">
        <div class="col-md-12 appdashboard">
            <div class="panel panel-default">
                <div class="panel-heading relative">Certificados
                    <button type="button" onclick="window.history.go(-1); return false;" class="btn btn-default btn-atras" ><i class="glyphicon glyphicon-arrow-left"></i></button>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="alert alert-success">A continuacion se listan los alumnos que aprobarón el examen final del curso seleccionado, debe seleccionar cuales alumnos se le emitira el certificado de aprobación </div>
                        </div>
                        <div class="col-xs-6 form-group">
                                <label for="">Curso</label>
                                <select name="list_courses_eva" id="list_courses_eva" class="form-control chosen-select">
                                    <option value="">Seleccione</option>
                                </select>
                                <br><br>
                            <buttonn class="btn btn-primary" id="btncertif"><i  class="glyphicon glyphicon-certificate"></i>&nbsp;Emitir Certificado</button>

                        </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 appdashboard">
            <div class="panel panel-default">
                <div class="panel-heading">Lista de Estudiantes</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <table id="tblstudents" class="display" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Calificación</th>
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
    </div>
</div>
<script src="../js/list_exams.js"></script>
@endsection
