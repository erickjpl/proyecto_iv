@extends('layouts.header')
@include('modals.modals')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Mis Cursos</div>
                <div class="panel-body">
                    <div id="body_courses_student"></div>
            </div>
        </div>
    </div>
    </div>
</div>
<script src="js/my_courses.js"></script>
@endsection