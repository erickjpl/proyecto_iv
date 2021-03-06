@extends('layouts.header')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 appdashboard">
            <div class="panel panel-default">
                <div class="panel-heading relative">Mis Cursos
                    <button type="button" onclick="window.history.go(-1); return false;" class="btn btn-default btn-atras" ><i class="glyphicon glyphicon-arrow-left"></i></button>
                </div>
                <div class="panel-body">
                    <div id="body_courses_student"></div>
            </div>
        </div>
    </div>
    </div>
</div>
@include('modals.modals')
<script src="js/my_courses.js"></script>
@endsection