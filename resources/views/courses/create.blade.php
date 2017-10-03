@extends('layouts.header')
@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Registrar Cursos</div>
                <div class="panel-body">
					<form id="form-create-course">
				          <div class="form-group">
				            <label for="nombre">Nombre del Curso</label>
				            <input type="text" class="form-control input-modal" id="nombre" name="nombre_user" placeholder="Nombre">
				          </div>
				      </div>
				      <div class="modal-footer">
				        <button type="button" class="btn btn-danger" data-dismiss="modal" >Cancelar</button>
				        <button type="button" class="btn btn-primary" id="savecourse">Guardar</button>
				      </div>
				      </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection