@extends('layouts.header')
@include('modals.modals')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 appdashboard">
            <div class="panel panel-default">
                <div class="panel-heading relative">
                    Oferta de Cursos
                    <button type="button" onclick="window.history.go(-1); return false;" class="btn btn-default btn-atras" ><i class="glyphicon glyphicon-arrow-left"></i></button>
                </div>
                <div class="panel-body">
                		<div id="body_courses"></div>
            </div>
        </div>
    </div>
    </div>
</div>
<script src="js/academic_offer.js"></script>
@endsection