@extends('layouts.header')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Evaluaciones</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-6 form-group">
                            <label for="">Curso</label>
                            <select name="list_courses_eva" id="list_courses_eva" class="form-control chosen-select">
                                <option value="">Seleccione</option>
                            </select>
                        </div>
                        <div class="col-xs-6">
                            <label for="">Examenes</label>
                            <select name="list_exams_eva" id="list_exams_eva" class='form-control chosen-select'>
                                <option value="">Seleccione</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Lista de Estudiantes</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <table id="tblevaluations" class="display" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Examen</th>
                                    <th>Calificación</th>
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
<script src="js/evaluations.js"></script>
@endsection