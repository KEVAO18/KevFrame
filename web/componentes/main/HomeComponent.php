@extends('main')

@section('content')
<style>
    /* Estilos mínimos, Bootstrap se encarga de la mayoría */
    .hero-section {
        background-color: #f8f9fa;
        /* Color de fondo claro */
        padding: 4rem 2rem;
        border-radius: .5rem;
        /* Bordes ligeramente redondeados */
        text-align: center;
    }

    .hero-section h1 {
        font-size: 3.5rem;
        /* Tamaño de fuente para el título principal */
        font-weight: 300;
    }

    .hero-section p.lead {
        font-size: 1.25rem;
        font-weight: 300;
    }

    .card-custom {
        height: 100%;
        /* Asegura que todas las tarjetas tengan la misma altura */
    }
</style>

<main class="container py-5">

    <div class="hero-section my-2">
        <h1 class="display-4">Bienvenido a KevFrame</h1>
        <p class="lead">¡Has iniciado tu primer proyecto con éxito!</p>
        <p class="text-muted"><small>Versión del Framework: {{ $version }}</small></p>
    </div>

    <div class="row">
        @i()
        @foreach($Commands as $command => $explanation)
        <div class="col-md-3 my-2">
            <div class="card card-custom">
                <div class="card-body text-center btn btn-outline-primary copy-command-btn" data-command="{{ $command }}">
                    <h5 class="card-title">{{ $command }}</h5>
                    <p class="card-text">{{ $explanation }}</p>
                </div>
            </div>
        </div>
        @i($i)
        @endforeach
    </div>

</main>

<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1080">
    <div id="copyToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
        <div class="toast-header bg-success">
            <strong class="me-auto text-white">Copiado</strong>
            <small class="text-white">¡Listo!</small>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body text-black" id="toast-message">
            Comando copiado al portapapeles.
        </div>
    </div>
</div>

@endsection