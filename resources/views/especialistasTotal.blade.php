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
                @if ($sede->id == session('sede.id'))
                    <div class="contenedor-totales">
                        <h3 class="nombre-sede">{{ $sede->nombre }}</h3>

                        @foreach ($sede->especialistas as $especialista)
                            @if ($especialista->facturas->count() > 0)
                                <div class="datos-especialista">
                                    <button type="button" class="nombre-especialista"
                                        data-nombre="{{ $especialista->nombre }}"
                                        data-facturas='@json($especialista->facturas->values())'>
                                        {{ $especialista->nombre }}
                                    </button>
                                    <p>$</p>
                                    <p class="total-especialista">
                                        {{ number_format($especialista->facturas->sum('abono'), 0, ',', '.') }}
                                    </p>
                                </div>
                            @endif
                        @endforeach

                        <div class="datos-sede-total">
                            <p>Total {{ $sede->nombre }}: </p>
                            <p>$ {{ number_format($sede->especialistas->sum(fn($e) => $e->facturas->sum('abono')), 0, ',', '.') }}
                            </p>
                        </div>

                        {{-- --------------------Totales por metodo de pago------------------- --}}

                        <div class="contenedor-metodos-pago">
                            <h4 class="titulo-metodos-pago">Por metodo de pago</h4>

                            @forelse ($totalesMetodoPago as $metodoPago)
                                <div class="datos-metodo-pago">
                                    <p class="nombre-metodo-pago">{{ $metodoPago['nombre'] }}</p>
                                    <p>$</p>
                                    <p class="total-metodo-pago">
                                        {{ number_format($metodoPago['total'], 0, ',', '.') }}
                                    </p>
                                </div>
                            @empty
                                <p class="sin-metodos-pago">Sin facturas para este dia</p>
                            @endforelse
                        </div>
                    </div>
                @endif
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
    <div class="popup-contenido-total-factura" id="popup-contenido"></div>
</div>

<script src="{{ asset('js/especialistasTotal.js') }}"></script>

@include('partials.footer')
