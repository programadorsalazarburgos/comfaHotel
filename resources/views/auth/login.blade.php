@extends('layouts.app')

@section('content')

<form  method="POST" action="{{ route('login') }}" name="login-form" id="user-login-form">
    {{ csrf_field() }}
    <!-- <img id="logologin" src="{{ asset('Images/logo-nanaimo.svg') }}" > -->
  <h2 class="title">Bienvenido</h2>
        <div class="input-div one">
           <div class="i">
              <i class="fas fa-user"></i>
           </div>
           <div class="div{{$errors->has('usuario' ? 'is-invalid' : '')}}">
              <h5>Usuario</h5>
              <input type="text" value="{{old('usuario')}}" name="usuario" id="usuario" class="input">
           </div>
        </div>
        <div class="input-div pass">
           <div class="i">
              <i class="fas fa-lock"></i>
           </div>
           <div class="div">
              <h5>Password</h5>
              <input class="input" type="password" name="password">
           </div>
        </div>
      <br>
      {!!$errors->first('usuario','<span class="invalid-feedback">:message</span>')!!}
        <input type="submit" class="btn" value="INICIAR">
      </form>

@endsection
