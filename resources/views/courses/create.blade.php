@extends('layouts.header')
@include('modals.modals')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Registrar Cursos</div>
                <div class="panel-body">
					<form id="form-create-course">
				          <div class="form-group">
				            <label for="nombre">Nombre del Curso: <span class="requerido">(*)</span> </label>
				            <input type="text" class="form-control input-modal" id="nombre_curso" name="nombre_curso" placeholder="Nombre del Curso">
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
				          <div class="form-group">
				            <label for="perfil">Profesor: <span class="requerido">(*)</span></label>
				            <select class="form-control chosen-select input-modal" data-placeholder='Seleccione'  id="profesor" name="profesor"></select>
				          </div>
				          <div class="form-group">
				          	<label for="estatus_curso">Estatus Curso: <span class="requerido">(*)</span></label>
				            <label class="checkbox-inline">
						      	<input type="radio" id="curso_true" name="estatus_curso" value="true"  >Curso Activo&nbsp;						  	   
						    </label>
						    <label class="checkbox-inline">						 
						      	<input type="radio" id="curso_false" name="estatus_curso" value="false" checked>Curso Inactivo&nbsp;
						    </label>
				          </div>
				          <div class="form-group">
				            <label for="estatus_curso">Modalidad Curso: <span class="requerido">(*)</span></label>
				            <label class="checkbox-inline">
						      	<input type="checkbox" id="tipo_mat1" class="material" checked name="tipo_mat[]" value="mat_clvivo" >Clases en Vivo&nbsp;						  	   
						    </label>
						    <label class="checkbox-inline">						 
						      	<input type="checkbox" class="material" id="tipo_mat2"checked  name="tipo_mat[]" value="mat_exam">Examenes&nbsp;
						    </label>
				          </div>
						   <div class="form-group">
				          		<label for="nombre">Temario": <span class="requerido">(*)</span></label>
				          		<textarea id="temario" name="temario" class="summernote"></textarea>
				          </div>
				      </div>
				      <div class="modal-footer">
				      	<input type="hidden" name="id_course" id="id_course" value="{{ !isset($id)?'':$id }}">
				        <button type="button" class="btn btn-danger" id='salircourse' >Cancelar</button>
				        <button type="button" class="btn btn-primary btn-createcourse" id="savecourse">Guardar</button>
				      </div>
				      </form>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/createcourses.js') }}"></script>
	<!--<script src="js/createcourses.js"></script>-->
@endsection