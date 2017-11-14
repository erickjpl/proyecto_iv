<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Escuela del Software</title>
    

    <!-- Styles -->
    <!--<link href="{{ asset('css/app.css') }}" rel="stylesheet">-->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link href="{{ asset('css/jquery.dataTables.min.css') }}" rel="stylesheet">   
    <link rel="stylesheet" href="{{ asset('css/datepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-clockpicker.min.css') }}"> 
    <link href="{{ asset('css/summernote/summernote.css') }}" rel="stylesheet">
    <link href="{{ asset('css/chosen/chosen.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/fileinput/fileinput.css') }}" rel="stylesheet">

    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    
    <!-- Scripts -->
    <!--<script src="{{ asset('js/app.js') }}"></script>-->
    <script src="{{ asset('js/lib/jquery-3.2.1.min.js') }}"></script>    
    <script src="{{ asset('js/lib/datatables.min.js') }}"></script>
    <script src="{{ asset('js/lib/bootstrap.min.js') }}"></script>

    <script src="{{ asset('js/lib/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('js/lib/bootstrap-clockpicker.min.js') }}"></script>
    <script src="{{ asset('js/lib/summernote/summernote.js') }}"></script>
    <script src="{{ asset('js/lib/summernote/summernote-es-ES.js') }}"></script>
    <script src="{{ asset('js/lib/chosen.jquery.min.js') }}"></script>

    <script src="{{ asset('js/lib/jquery.validate.js') }}"></script>
    <script src="{{ asset('js/lib/additional-methods.min.js') }}"></script>
    <script src="{{ asset('js/lib/fn_validate.js') }}"></script>
    <script src="{{ asset('js/moment.js') }}"></script>
    <script src="{{ asset('js/moment-with-locales.js') }}"></script>

    <script src="{{ asset('js/nav.js') }}"></script>

</head>
<body>
    <div id="app">
    <nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('home') }}">
               Escuela del Software
            </a>
        </div>
        <div class="collapse navbar-collapse" id="app-navbar-collapse" data-profile='{{ base64_encode(Session::get('profile_id'))  }}'>
            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @guest
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register') }}">Register</a></li>
                @else
                    <!--Menu Administrador-->
                    <li class="hide nav-app nav-adm"><a href="{{ url('/course/listcourse') }}">Modulo Cursos<span class="sr-only"></span></a></li>
                    <li class="hide nav-app nav-adm"><a href="{{ url('students') }}">Modulo de Estudiantes<span class="sr-only"></span></a></li>
                    <li class="hide nav-app nav-adm"><a href="{{ url('users') }}">Modulo de Usuarios<span class="sr-only"></span></a></li>
                    <!--Menu Estudiante-->
                    <li class="hide nav-app nav-stu"><a href="{{ url('academicoffer') }}">Oferta de Cursos<span class="sr-only"></span></a></li>
                    <li class="hide nav-app nav-stu"><a href="{{ url('mycourses') }}">Mis Cursos<span class="sr-only"></span></a></li>
                    <!--Menu profesor-->
                    <li class="dropdown hide nav-app nav-tea">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                           Evaluaciones <span class="caret"></span></a>
                          <ul class="dropdown-menu">
                            <li class="dropdown-header">Evaluaciones</li>
                            <li role="separator" class="divider"></li>
                            <li><a href="{{url('evaluations')}}">Estudiantes</a></li>
                            <li><a href="{{url('evaluations/certificates')}}">Certificados</a></li>
                          </ul>
                    </li> 

                    <li class="dropdown hide nav-app nav-tea">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            Mis Cursos <span class="caret"></span></a>
                          <ul class="dropdown-menu">
                            <li class="dropdown-header">Aula Virtual</li>
                            <li role="separator" class="divider"></li>
                            <li><a href="{{ url('aulavirtual/list') }}">Streamings</a></li>
                            <li><a href="{{ url('aulavirtual/files') }}">Gestor de Archivos</a></li>
                            <li><a href="{{ url('exams/list') }}">Examenes</a></li>                          
                          </ul>
                    </li>                
                    <li class="dropdown">
                        <a href="#" id="dLabel" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                          Bienvenido(a) <b>{{ Auth::user()->name }}</b> <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                            <li>
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                    <span class="glyphicon glyphicon-off"></span>  Cerrar Session
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
        </div>
        </nav>
        @yield('content')
    </div>
    <script>
        $('.dropdown-toggle').dropdown()
    </script>
</body>
</html>
