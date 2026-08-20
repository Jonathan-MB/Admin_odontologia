@include('partials.head')
<link rel="stylesheet" href="{{ asset('css/crearCliente.css') }}">
<link rel="stylesheet" href="{{ asset('css/especialistasTotal.css') }}">
<link rel="stylesheet" href="{{ asset('css/citas.css') }}">

<title>Facturación por Dia</title>

@include('partials.header')

<div class="contenedor-general">

    <h1 class="vista-titulo">Agenda</h1>

    @php
        $esHoy = $dia->isToday();

        $proximaId = $esHoy
            ? optional($citas->first(fn($c) => $c->fecha_hora->gte(now())))->id
            : null;

        // Agrupadas por doctor. Las citas migradas no tienen doctor:
        // esas caen en "Sin doctor asignado".
        $porDoctor = $citas->groupBy(fn($c) => $c->especialista->nombre ?? 'Sin doctor asignado');
    @endphp

    @include('partials.mensaje')

    <div class="agenda">

        <div class="agenda-lateral">
            @include('partials.calendario')

            <div class="atajos-fecha">
                <a class="atajo-fecha @if ($esHoy) atajo-activo @endif"
                    href="{{ route('clientes.citas', ['sedeId' => session('sede.id'), 'fecha' => now()->toDateString()]) }}">Hoy</a>
                <a class="atajo-fecha"
                    href="{{ route('clientes.citas', ['sedeId' => session('sede.id'), 'fecha' => now()->addDay()->toDateString()]) }}">Mañana</a>
            </div>

            @if ($pendientes->count() > 0)
                <button type="button" class="atajo-fecha atajo-pendientes" id="boton-pendientes">
                    Sin reagendar ({{ $pendientes->count() }})
                </button>

                <div class="panel-pendientes hidden" id="panel-pendientes">
                    <p class="aviso-pendientes">
                        Pacientes con cita vencida sin fecha nueva.
                    </p>
                    @foreach ($pendientes as $cita)
                        @include('partials.citaFila', ['pasada' => true, 'mostrarFecha' => true])
                    @endforeach
                </div>
            @endif
        </div>

        <div class="agenda-dia">
            <p class="fecha-busqueda">
                {{ $dia->translatedFormat('l d \d\e F') }}
                <span class="contador-citas">{{ $citas->count() }} citas</span>
            </p>

            <div class="citas">
                @forelse ($porDoctor as $doctor => $citasDoctor)
                    <p class="doctor-titulo">
                        {{ $doctor }}
                        <span class="doctor-conteo">{{ $citasDoctor->count() }}</span>
                    </p>

                    @foreach ($citasDoctor as $cita)
                        @include('partials.citaFila')
                    @endforeach
                @empty
                    <p class="sin-citas">No hay citas para este día</p>
                @endforelse

                <button type="button" class="boton-nueva-cita" id="boton-nueva-cita">
                    + Agendar cita este día
                </button>
            </div>
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
            <div class="acciones-cliente">
                <a class="accion-cliente" id="popup-historia" href="#">Ver historia</a>
                <a class="accion-cliente" id="popup-ir-facturas" href="#">Ver facturas</a>
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

    {{-- -------------------------POP UP NUEVA CITA------------------- --}}

    <div class="contenedor-pop-up hidden" id="popup-nueva-cita">
        <div class="cerrar-pop-up">
            <button class="boton-cerrar-pop-up" type="button" id="cerrar-nueva-cita">
                <img src="{{ asset('img/iconoCerrar.png') }}" alt="">
            </button>
        </div>

        <div class="contenedor-datos-reagendar">
            <p>Agendar cita</p>

            <div class="datos-reagendar">
                <div class="buscar-paciente-linea">
                    <input autocomplete="off" type="text" id="buscar-paciente"
                        placeholder="Nombre o documento del paciente" minlength="3">
                </div>

                <div class="resultados-paciente" id="resultados-paciente"></div>

                <p class="paciente-elegido hidden" id="paciente-elegido"></p>
            </div>

            <form action="{{ route('citas.store') }}" method="POST" id="form-nueva-cita">
                @csrf
                <input type="hidden" name="cliente_id" id="nueva-cita-cliente">
                <input type="hidden" name="fecha_hora" id="nueva-cita-fecha-hora">

                <div class="fecha-reagendar">
                    <select name="especialista_id" id="nueva-cita-especialista" required>
                        <option value="" selected disabled>Doctor</option>
                        @foreach ($especialistas as $especialista)
                            <option value="{{ $especialista->id }}">{{ $especialista->nombre }}</option>
                        @endforeach
                    </select>

                    <input type="date" id="nueva-cita-dia" value="{{ $fecha }}"
                        min="{{ now()->format('Y-m-d') }}" required>
                    <input type="time" id="nueva-cita-hora" step="60" required>

                    <button type="submit">Agendar</button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/citas.js') }}"></script>

</div>

@include('partials.footer')
