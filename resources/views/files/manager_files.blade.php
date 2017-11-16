@extends('layouts.header')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 appdashboard">
            <div class="panel panel-default">
                <div class="panel-heading">Gestor de Archivos</div>
                <div class="panel-body" id="body-files" data-user={{$user}} >
                    <form id="form-files" enctype="multipart/form-data" >
                    <div class="alert alert-success">Debe seleccionar el curso e ingresar la descripción de la documentación del mismo</div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="curso">Curso: <span class="requerido">(*)</span></label>
                                <select name="courses_files" id="courses_files" class="form-control chosen-select" data-placeholder='Seleccione'></select>
                            </div>
                            <div class="form-group">
                                <label for="descripcion">Descripción: <span class="requerido">(*)</span></label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row hide" id="file-manager">
                        <div class="col-sm-12">
                            <div class="removeInput">
                                <input id="input-fa" name="input-fa[]" type="file" multiple>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="csrf_token" name="_token" value="{{ csrf_token() }}">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="../js/manager_files.js"></script>
@endsection