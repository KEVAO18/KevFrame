@extends('main')

@section('content')
<div class="container">
    <h1>Pruebas</h1>
    <p>Lista de usuarios</p>
    <ul>
        @foreach ($all as $usuario)
        <li>{{ $usuario['nombre'] }}</li>
        @endforeach
    </ul>
</div>
@endsection