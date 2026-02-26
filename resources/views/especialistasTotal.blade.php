@include('partials.head')
<link rel="stylesheet" href="{{ asset('css/crearCliente.css') }}">
<link rel="stylesheet" href="{{ asset('css/especialistasTotal.css') }}">

<title>Facturación por Dia</title>

@include('partials.header')

<div class="contenedor-general">

    <h1 class="vista-titulo">Facturación por Dia</h1>
    <form action="{{ route('facturas.totalDia') }}" method="GET">
        <div class="contenedor-fecha-facturacion">
            <label class="fecha" for="fecha">Fecha</label>
            <input autocomplete="off" type="date" name="fecha" id="fecha"
                value="{{ request('fecha', now()->toDateString()) }}" max="{{ now()->toDateString() }}">

            <button type="submit">Buscar</button>
        </div>
    </form>

    <div class="contenedor-general-totales">
        <p class="fecha-busqueda">{{ \Carbon\Carbon::parse($fecha)->translatedFormat('d \ F \ Y') }}</p>
        <div>
            @foreach ($sedes as $sede)
                <div class="contenedor-totales">
                    <h3 class="nombre-sede">{{ $sede->nombre }}</h3>

                    @foreach ($sede->especialistas as $especialista)
                        <div class="datos-especialista">
                            <button type="button" class="nombre-especialista" data-nombre="{{ $especialista->nombre }}"
                                data-facturas="{{ $especialista->facturas->toJson() }}">
                                {{ $especialista->nombre }} </button>
                            <p>$</p>
                            <p class="total-especialista">
                                {{ number_format($especialista->facturas->sum('abono'), 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                    <div class="datos-sede-total">
                        <p>Total {{ $sede->nombre }}: </p>
                        <p> $
                            {{ number_format($sede->especialistas->sum(fn($e) => $e->facturas->sum('abono')), 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
{{-- -------------------------POP UP------------------- --}}



<div class="contenedor-pop-up hidden" id="popup-facturas">
    <div class="cerrar-pop-up">
        <button class="boton-cerrar-pop-up">
            <img src="{{ asset('img/iconoCerrar.png') }}" alt="">
        </button>
    </div>
    <h3 class="popup-nombre-especialista" id="popup-nombre-especialista"></h3>
    <div class="popup-contenido-total-factura" id="popup-contenido">a</div>
</div>

<script src="{{ asset('js/especialistasTotal.js') }}"></script>

@include('partials.footer')
