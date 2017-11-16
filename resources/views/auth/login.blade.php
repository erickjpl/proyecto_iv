@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2 applogin">
            <div class="panel panel-default">
                <div class="panel-heading">Inicio de Sesion!</div>
             
                <div class="panel-body">
                    
                    @if($errors)
                       @foreach ($errors->all() as $error)
                          <div class="alert alert-danger">{{ $error }}</div>
                      @endforeach
                    @endif 
                    @if(Session::has('msg'))
                        <div class="alert alert-success">{!!Session::get('msg')!!}</div>
                    @endif

                    
                    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">Login</label>

                            <div class="col-md-6">
                                <input id="email" placeholder="correo electronico" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Contraseña</label>

                            <div class="col-md-6">
                                <input id="password" type="password" placeholder="Contraseña" class="form-control" name="password" required>
                            </div>
                        </div>
                        

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Recordar Cuenta
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Autenticar
                                </button>

                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                     ¿Olvidaste tu contraseña?
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
