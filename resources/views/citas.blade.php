@include('partials.head')
<link rel="stylesheet" href="{{ asset('css/crearCliente.css') }}">
<link rel="stylesheet" href="{{ asset('css/especialistasTotal.css') }}">
<link rel="stylesheet" href="{{ asset('css/citas.css') }}">

<title>Facturación por Dia</title>

@include('partials.header')

<div class="contenedor-general">

    <h1 class="vista-titulo">Citas por dia</h1>
    <form action="{{ route('clientes.citas', session('sede.id')) }}" method="GET">
        <div class="contenedor-fecha-facturacion">
            <label class="fecha" for="fecha">Fecha</label>
            <input autocomplete="off" type="date" name="fecha" id="fecha" min="{{ now()->format('Y-m-d') }}"
                value="{{ request('fecha', now()->toDateString()) }}">

            <button type="submit">Buscar</button>
        </div>
    </form>
    @include('partials.mensaje')
    <div class="contenedor-general-totales">
        <p class="fecha-busqueda">{{ \Carbon\Carbon::parse($fecha)->translatedFormat('d \ F \ Y') }}</p>

        <div class="citas">
            @forelse ($clientes as $cliente)
                <button type="button" class="btn-cliente" data-id="{{ $cliente->id }}"
                    data-nombre="{{ $cliente->nombre_completo }}" data-fecha="{{ $cliente->fecha_cita }}"
                    data-telefono="{{ $cliente->telefono }}" data-documento="{{ $cliente->numero_documento }}"
                    data-correo="{{ $cliente->correo }}" data-edad="{{ $cliente->fecha_nacimiento }}"
                    data-url="{{ route('clientes.agendar', $cliente->id) }}">
                    <p>{{ $cliente->nombre_completo }}</p>
                    <p>{{ \Carbon\Carbon::parse($cliente->fecha_cita)->translatedFormat('h:i A') }}</p>
                </button>
                
            @empty
                <p class="sin-citas">No hay citas para este día</p>
            @endforelse 
        </div>

    </div>
    {{-- -------------------------POP UP------------------- --}}



    <div class="contenedor-pop-up hidden" id="popup-facturas">
        <div class="cerrar-pop-up">
            <button class="boton-cerrar-pop-up " id="cerrar-pop-up">
                <img src="{{ asset('img/iconoCerrar.png') }}" alt="">
            </button>
        </div>
        <div class="contenedor-datos-reagendar">
            <p>Editar Cita</p>
            <div class="datos-reagendar">

                <div class="dato-reagendar">
                    <p class="titulo-dato-reagendar">Nombre :</p>
                    <p id="popup-nombre"></p>
                </div>
                <div class="dato-reagendar">
                    <p class="titulo-dato-reagendar">Documento :</p>
                    <p id="popup-documento"></p>
                </div>
                <div class="dato-reagendar">
                    <p class="titulo-dato-reagendar">Edad :</p>
                    <p id="popup-edad"></p>
                </div>
                <div class="dato-reagendar">
                    <p class="titulo-dato-reagendar">Telefono :</p>
                    <p id="popup-telefono"></p>

                </div>
                <div class="dato-reagendar">
                    <p class="titulo-dato-reagendar">Correo :</p>
                    <p id="popup-correo"></p>
                </div>
            </div>
            <div class="fecha-reagendar">
                
                <form action="" id="form-agendar" method="POST">
                    @method('PATCH')
                    @csrf
                    <input type="date" id="popup-fecha-dia" min="{{ now()->format('Y-m-d') }}" required>
                    <input type="time" id="popup-fecha-hora" step="60" required>
                    <input type="hidden" name="fecha_cita" id="popup-fecha-hidden">
                    <input type="hidden" name="sede_id" value="{{ session('sede.id') }}">
                    <button type="submit">Agendar</button>
                </form>

                <form action="" id="form-eliminar" method="POST">
                    @method('PATCH')
                    @csrf
                    <input type="hidden" name="fecha_cita" value="">
                    <input type="hidden" name="sede_id" value="{{ session('sede.id') }}">
                    <button id="boton-eliminar-cita" type="submit">Eliminar</button>
                </form>


            </div>
        </div>
    </div>

    <script>
        const baseUrl = "{{ url('clientes') }}";
    </script>

    <script src="{{ asset('js/citas.js') }}"></script>

    @include('partials.footer')
