@extends('plantilla.plantilla2')
@section('contenido')

@if(Auth::check())
        <template v-if="menu==0">
            <estados></estados>
        </template>

       
        @include('contenido.menu_camilo')
        @include('contenido.menu_johan')

        <template v-if="menu==7">
            <user></user>
        </template>

        <template v-if="menu==8">
            <rol></rol>
        </template>
       
        <template v-if="menu==21">
           <div class="card-body" id="app">
                    <chat-app :user="{{ auth()->user() }}"></chat-app>
                </div>
        </template>
@endif
@endsection
