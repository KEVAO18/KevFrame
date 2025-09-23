@extends('main')

@section('content')
        <main>
        <div class="container py-5">
            <h1 class="text-center display-2"><?=$ErrorCode?></h1>
            <h2 class="text-center display-3"><?=$msg?></h2>
            <div class="d-grid pt-4">
                <a href="/" class="btn btn-outline-dark">Volver al inicio</a>
            </div>
        </div>
    </main>
@endsection