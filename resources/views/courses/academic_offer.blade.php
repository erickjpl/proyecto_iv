@extends('layouts.header')
@include('modals.modals')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Oferta de Cursos</div>
                <div class="panel-body">
                		<div id="body_courses"></div>
            </div>
        </div>
    </div>
    <script src="js/academic_offer.js"></script>
@endsection