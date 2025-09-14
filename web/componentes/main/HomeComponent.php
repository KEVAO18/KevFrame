@extends('main')

@section('content')
    <h2>Bienvenido, {{ $nombre }}!</h2>
    <p>Lista de tareas:</p>
    <ul>
        @foreach($tareas as $tarea)
            <li>{{ $tarea }}</li>
        @endforeach
    </ul>
@endsection