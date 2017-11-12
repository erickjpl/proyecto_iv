@extends('layouts.header')
@include('modals.modals')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Examen <strong>{{strtoupper($type)}} {{strtoupper($namecourse)}} </strong>  </div>
                <div class="panel-body" id="body-exam" data-exam='{{base64_encode($exam)}}' data-fecha='{{$f_fin}}'>
                    <div class="alert alert-info">
                        <b>Instrucciones:</b>
                        <ul>
                            <li>El examen finalizará <span id="f_enddate" class="bold"></span>.</li>
                        </ul>
                        <ul>
                            <li>Las preguntas de selección pueden ser una opción o varias opciones segun el contexto de la pregunta.</li>
                        </ul>
                        <ul>
                            <li>Las preguntas abiertas se contestaran libremente segun el contexto de la pregunta.</li>
                        </ul>
                    </div>
                    <form action="" id="form_questions">
                        <div id="body_questions"></div>
                        <div class="row text-center">
                            <div class="col-xs-12">
                                <button type="button" class="btn btn-danger" id='salirexam' >Salir</button>
                                <button type="button" class="btn btn-primary btn-createcourse" id="saveexam">Enviar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/viewstudent.js') }}"></script>
@endsection