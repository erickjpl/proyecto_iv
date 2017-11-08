@extends('layouts.header')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Examenes</div>
                <div class="panel-body">
                    <form class="data-render">
                        <div class="form-group">
                            <label for="course">Curso</label>
                            <select name="course" id="course" class="form-control chosen-select"></select>
                        </div>
                        <div class="form-group">
                            <label for="type-exam">Tipo de Examen</label>
                            <select name="type-exam" id="type-exam" class="form-control chosen-select">
                                <option value="p">Parcial</option>
                                <option value="f">Final</option>
                            </select>
                        </div>    
                        <div class="form-group">
                            <label for="nombre">Fecha de Inicio: <span class="requerido">(*)</span></label>
                            <div class="input-group date" data-provide="datepicker">
                                <input type="text" class="form-control datepicker col-xs-12"
                                    id="fecha_inicio" name="fecha_inicio" placeholder="dd/mm/yyyy">
                                <div class="input-group-addon">
                                    <span class="glyphicon glyphicon-th"></span>
                               </div>
                            </div>
                        </div>
                        <div class="form-group">
                                <label for="nombre">Hora de Inicio: <span class="requerido">(*)</span></label>
                                <div class="input-group date" data-provide="datepicker">
                                        <input type="text" class="form-control clockpicker col-xs-12"
                                        id="hora_inicio" name="hora_inicio" placeholder="00:00:00">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                </div>
                          </div>
                          <div class="form-group">
                                <label for="nombre">Fecha de Finalizacion: <span class="requerido">(*)</span></label>
                                <div class="input-group date" data-provide="datepicker">
                                    <input type="text" class="form-control datepicker col-xs-12"
                                    id="fecha_fin" name="fecha_fin" placeholder="dd/mm/yyyy">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>
                          </div>
                          <div class="form-group">
                                <label for="nombre">Hora de Finalizacion: <span class="requerido">(*)</span></label>
                                <div class="input-group date" data-provide="datepicker">
                                        <input type="text" class="form-control clockpicker col-xs-12"
                                        id="hora_final" name="hora_final" placeholder="00:00:00">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </span>
                                </div>
                          </div>
                    </form>
                    <div class="row">
                        <div class="col-xs-12 text-center">
                             <button type="button" class="btn btn-danger" id='exit-exam' >Cancelar</button>
                            <button id="send-exam" class="btn btn-success">Enviar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/examscreate.js') }}"></script>
@endsection