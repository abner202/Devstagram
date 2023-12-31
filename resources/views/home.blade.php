@extends("layouts.app")

@section("titulo")
Página princial
@endsection

@section('contenido')
    <x-listar-post :posts="$posts"/>
     
    

@endsection