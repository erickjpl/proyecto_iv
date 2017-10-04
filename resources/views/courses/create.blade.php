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
				          <div class="form-group">
				          		<label for="nombre">Hora de Inicio</label>
				          		<div class="input-group date" data-provide="datepicker">
								    	<input type="text" class="form-control clockpicker col-xs-12">
										<span class="input-group-addon">
									        <span class="glyphicon glyphicon-time"></span>
									    </span>
								</div>
				          </div>
				          <div class="form-group">
				          		<label for="nombre">Fecha de Inicio</label>
				          		<div class="input-group date" data-provide="datepicker">
							    	<input type="text" class="form-control datepicker col-xs-12">
							 		<div class="input-group-addon">
								        <span class="glyphicon glyphicon-th"></span>
								    </div>
								</div>
				          </div>
						   <div class="form-group">
				          		<label for="nombre">Temario</label>
				          		<textarea id="" class="summernote"></textarea>
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
<script type="text/javascript">
	 $(document).ready(function(){
	    $('.clockpicker').clockpicker({donetext:"Seleccionar",twelvehour:true,default:'now'});
	    $('.datepicker').datepicker({
		    format: 'mm/dd/yyyy',
		    startDate: '-3d'
		});
		$('.summernote').summernote({
		  height: 150,
		  lang: 'es-ES',   //set editable area's height
		  codemirror: { // codemirror options
		    theme: 'monokai',

		  }
		});
	  });
</script>
@endsection