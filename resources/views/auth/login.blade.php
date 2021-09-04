@extends('layouts.app')

@section('content')


<form  method="POST" action="{{ route('login') }}" name="login-form" id="user-login-form">
    {{ csrf_field() }}
    <!-- <img id="logologin" src="{{ asset('Images/logo-nanaimo.svg') }}" > -->
  <h2 class="title">Login</h2>
        <div class="input-div one">        
           <div class="div{{$errors->has('usuario' ? 'is-invalid' : '')}}">
              <input type="text"  class="form-control" value="{{old('usuario')}}" name="usuario" id="usuario" class="input">
           </div>
        </div>
        <div class="input-div pass">         
           <div class="div">
              <input class="input" type="password" name="password">
           </div>
        </div>
      <br>
      {!!$errors->first('usuario','<span class="invalid-feedback">:message</span>')!!} <br>
      <button type="submit">Iniciar Sesión</button>
      </form>

@endsection
