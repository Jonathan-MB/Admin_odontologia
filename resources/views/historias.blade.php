@include('partials.head')

<link rel="stylesheet" href="{{ asset('css/historias.css') }}">

<title> Historias - {{ $cliente->nombre_completo }}</title>

@include('partials.header')





<div class="contenedor-general">

    <H1 class="vista-titulo">Datos Cliente</H1>

    @include('partials.mensaje')

    <div class="contenedor-agregar-cliente">
        <div class="linea-agregar-cliente">
            <div class="elemento-formulario">
                <p class="titulo-elemento">No. Historia</p>
                <p class="dato-elemento">{{ $cliente->numero_documento }}</p>
            </div>

            <div class="elemento-formulario">
                <p class="titulo-elemento-p">Tipo Documento</p>
                <p class="dato-cliente">{{ $cliente->tipoDocumento->nombre }}</p>


            </div>
            <div class="elemento-formulario">
                <p class="titulo-elemento-p">Numero Documento</p>
                <p class="dato-cliente">{{ $cliente->numero_documento }}</p>
            </div>



            <div class="elemento-formulario">
                <p class="titulo-elemento-p">Fecha Nacimiento</p>
                <p class="dato-cliente">
                    {{ \Carbon\Carbon::parse($cliente->fecha_nacimiento)->format('d/m/Y') }}
                </p>
            </div>

            <div class="elemento-formulario">
                <p class="titulo-elemento-p">Edad</p>
                <p id="dato-edad">{{ \Carbon\Carbon::parse($cliente->fecha_nacimiento)->age }}</p>
            </div>

        </div>

        <div class="linea-agregar-cliente">

            <div class="elemento-formulario">
                <p class="titulo-elemento-p">Eps</p>
                <p class="dato-cliente"> {{ $cliente->eps->nombre }}</p>
            </div>



            <div class="elemento-formulario">
                <p class="titulo-elemento-p">Nombre</p>
                <p class="dato-cliente"> {{ $cliente->nombre }}</p>
            </div>
            <div class="elemento-formulario">
                <p class="titulo-elemento-p">Primer Apellido</p>
                <p class="dato-cliente"> {{ $cliente->primer_apellido }}</p>
            </div>
            <div class="elemento-formulario">
                <p class="titulo-elemento-p">Segundo Apellido</p>
                <p class="dato-cliente"> {{ $cliente->segundo_apellido }}</p>
            </div>
        </div>

        <div class="linea-agregar-cliente">


            <div class="elemento-formulario">
                <p class="titulo-elemento-p">Direccion</p>
                <p class="dato-cliente"> {{ $cliente->direccion }}</p>
            </div>
            <div class="elemento-formulario">
                <p class="titulo-elemento-p">Correo</p>
                <p class="dato-cliente"> {{ $cliente->correo }}</p>
            </div>
            <div class="elemento-formulario">
                <p class="titulo-elemento-p">Celular</p>
                <p class="dato-cliente"> {{ $cliente->telefono }}</p>

            </div>
            <a href="{{ route('clientes.edit', $cliente->id) }}">
                <button class="boton-guardar" type="button">
                    <img src="{{ asset('img/editar.png') }}" alt="">
                    <p>Editar</p>
                </button>
            </a>


        </div>

        <form action="{{ route('facturas.buscar') }}" method="post" autocomplete="off">
            @csrf
            <input autocomplete="off" type="hidden" name="numeroDocumento" value="{{ $cliente->numero_documento }}">
            <button id="boton-saldo-cliente-historia" type="submit" href="{{ route('facturacion') }}"
                class="linea-agregar-cliente linea-saldo">
                <p>Saldo: $ {{ number_format($cliente->saldo, 0, ',', '.') }}</p>
            </button>
        </form>
        <form action="{{ route('clientes.update', $cliente->id) }}" method="POST" autocomplete="off">
            @method('PATCH')
            @csrf
            <div class="linea-agregar-cliente linea-cita" ">

                <p class="titulo-elemento-p">Proxima Cita:</p>

                <div class="cita-contenedor">
                    <input autocomplete="off" type="datetime-local" name="fecha_cita" 
                        value="{{ $cliente->fecha_cita }}" 
                        min="{{ now()->format('Y-m-d\TH:i') }}">
                    <input type="hidden" name="sede_id" value="{{session('sede.id')}}">
                </div>
                <button class="boton-guardar" id="boton-agendar" value="" type="submit">
                    <p>Agendar</p>
                </button>


            </div>

        </form>
        </div>



    <h2 class="vista-titulo">Historial de evolucion</h2>

        <div class="mensaje hidden" id="mensajeOdontograma">
            <p>Odontograma actualizado correctamente</p>
        </div>


    <div class="historias-contenedor">



        <button class="boton-historial boton-nueva-historia" type="button" id="boton-guardar-odontograma"> + Guardar Odontograma</button>
        <div class="veribular-grupo">
            <div class="datos-historia">
                    <div class=" F-A-Odontograma">
                        <label class="datos-historias-titulo " for="fecha">Fecha</label>
                        <input autocomplete="off" type="date" id="fechaOdontograma"  value="{{ request('fecha', now()->toDateString()) }}" max="{{ now()->toDateString() }}">
                        
                    </div>
                    <div class="F-A-Odontograma-data">
                        <label class="datos-historias-titulo  " for="especialista-odontograma">Atendido por </label>
                        <select name="especialista-odontograma" id="especialistaId">

                        <option value="" selected disabled>Seleccionar</option>
                        @foreach ($especialistas as $especialista)
                @if ($especialista->sede_id == session('sede.id'))
                    <option value="{{ $especialista->id }}">{{ $especialista->nombre }}</option>
                @endif
                @endforeach
                </select>
            </div>
    </div>

    <h3 class="tipo-diente">VESTIBULARES</h3>
    <div class="titulos-grupos">
        <p>Vestibular Arriba Derecha</p>
        <p>Vestibular Arriba Izquierda</p>
    </div>
    <div class="dientes-fila">
        <div class="dientes-grupo">
            @foreach ($dientes as $diente)
                @if ($diente->nombre <= 18 && $diente->nombre >= 11)
                    <div class="contenedor-info-diente">
                        <button type="button" class="diente" data-diente="{{ $diente->nombre }}">
                            {{ $diente->nombre }}
                        </button>
                        <textarea class="diente-textarea">{{ $ultimasHistorias[$diente->id]->observacion ?? '' }}</textarea>
                    </div>
                @endif
            @endforeach
        </div>

        <div class="dientes-grupo">
            @foreach ($dientes as $diente)
                @if ($diente->nombre <= 28 && $diente->nombre >= 21)
                    <div class="contenedor-info-diente">
                        <button type="button" class="diente" data-diente="{{ $diente->nombre }}">
                            {{ $diente->nombre }}
                        </button>
                        <textarea class="diente-textarea">{{ $ultimasHistorias[$diente->id]->observacion ?? '' }}</textarea>
                    </div>
                @endif
            @endforeach

        </div>
    </div>
    <div class="titulos-grupos">

        <p>Vestibular Abajo Derecha</p>
        <p>Vestibular Abajo Izquierda</p>
    </div>
    <div class="dientes-fila">
        <div class="dientes-grupo">
            @foreach ($dientes as $diente)
                @if ($diente->nombre <= 48 && $diente->nombre >= 41)
                    <div class="contenedor-info-diente">
                        <button type="button" class="diente" data-diente="{{ $diente->nombre }}">
                            {{ $diente->nombre }}
                        </button>
                        <textarea class="diente-textarea">{{ $ultimasHistorias[$diente->id]->observacion ?? '' }}</textarea>
                    </div>
                @endif
            @endforeach

        </div>

        <div class="dientes-grupo">
            @foreach ($dientes as $diente)
                @if ($diente->nombre <= 38 && $diente->nombre >= 31)
                    <div class="contenedor-info-diente">
                        <button type="button" class="diente" data-diente="{{ $diente->nombre }}">
                            {{ $diente->nombre }}
                        </button>
                        <textarea class="diente-textarea">{{ $ultimasHistorias[$diente->id]->observacion ?? '' }}</textarea>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>


<div class="ungular-grupo">
    <h3 class="tipo-diente">UNGUALES</h3>
    <div class="titulos-grupos">

        <p>Unguales Arriba Derecha</p>
        <p>Unguales Arriba Izquierda</p>
    </div>
    <div class="dientes-fila">
        <div class="dientes-grupo">
            @foreach ($dientes as $diente)
                @if ($diente->nombre <= 55 && $diente->nombre >= 51)
                    <div class="contenedor-info-diente">
                        <button type="button" class="diente" data-diente="{{ $diente->nombre }}">
                            {{ $diente->nombre }}
                        </button>
                        <textarea class="diente-textarea">{{ $ultimasHistorias[$diente->id]->observacion ?? '' }}</textarea>
                    </div>
                @endif
            @endforeach

        </div>

        <div class="dientes-grupo">
            @foreach ($dientes as $diente)
                @if ($diente->nombre <= 65 && $diente->nombre >= 61)
                    <div class="contenedor-info-diente">
                        <button type="button" class="diente" data-diente="{{ $diente->nombre }}">
                            {{ $diente->nombre }}
                        </button>
                        <textarea class="diente-textarea">{{ $ultimasHistorias[$diente->id]->observacion ?? '' }}</textarea>
                    </div>
                @endif
            @endforeach

        </div>
    </div>
    <div class="titulos-grupos">
        <p>Unguales Abajo Derecha</p>
        <p>Unguales Abajo Izquierda</p>
    </div>
    <div class="dientes-fila">
        <div class="dientes-grupo">
            @foreach ($dientes as $diente)
                @if ($diente->nombre <= 85 && $diente->nombre >= 81)
                    <div class="contenedor-info-diente">
                        <button type="button" class="diente" data-diente="{{ $diente->nombre }}">
                            {{ $diente->nombre }}
                        </button>
                        <textarea class="diente-textarea">{{ $ultimasHistorias[$diente->id]->observacion ?? '' }}</textarea>
                    </div>
                @endif
            @endforeach

        </div>

        <div class="dientes-grupo">
            @foreach ($dientes as $diente)
                @if ($diente->nombre <= 75 && $diente->nombre >= 71)
                    <div class="contenedor-info-diente">
                        <button type="button" class="diente" data-diente="{{ $diente->nombre }}">
                            {{ $diente->nombre }}
                        </button>
                        <textarea class="diente-textarea">{{ $ultimasHistorias[$diente->id]->observacion ?? '' }}</textarea>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

</div>
<div class="diente-general">
    @foreach ($dientes as $diente)
        @if ($diente->nombre == 'General')
            <div class="contenedor-info-diente info-diente-general">
                <button type="button" class="diente" id="diente-general-img" data-diente="{{ $diente->nombre }}">
                    {{ $diente->nombre }}
                </button>
                <textarea class="diente-textarea">{{ $ultimasHistorias[$diente->id]->observacion ?? '' }}</textarea>
            </div>
        @endif
    @endforeach

</div>
<button class="boton-historial diente" data-diente="Todas">Todas las historias</button>
</div>


{{-- --------------------POP UP Historias------------------- --}}

<div class="contenedor-pop-up hidden" id="pop-up-historias-diente">
    <div class="cerrar-pop-up">
        <button class="boton-cerrar-pop-up ">
            <img src="{{ asset('img/iconoCerrar.png') }}" alt="">
        </button>
    </div>

    <div class="historias-contenedor historias-pop-up">

        <div class="historia-encabezado">
            <p>Código Diente :</p>
            <p id="diente-Actual-nombre"></p>
        </div>
        <div class="cuerpo-historias-diente" id="cuerpo-historias">


            <template id="template-historia">
                <div class="historia-tarjeta">
                    <div>
                        <p class="tarjeta-encabezado fecha-tarjeta">fecha</p>
                        <p class="tarjeta-encabezado diente-tarjeta">diente</p>
                    </div>
                    <div>
                        <p class="tarjeta-encabezado especialista-tarjeta"> especialista</p>
                        <p class="tarjeta-obsevacion obserbacion-tarjeta">obserbacion</p>
                    </div>

                </div>
            </template>




        </div>
    </div>
</div>

{{-- --------------------POP UP Nueva Historia------------------- --}}

<div class="contenedor-pop-up  hidden" id="pop-up-historias-nuevas">
    <div class="cerrar-pop-up">
        <button class="boton-cerrar-pop-up ">
            <img src="{{ asset('img/iconoCerrar.png') }}" alt="">
        </button>
    </div>
    <div class="historias-contenedor historias-pop-up">

        <div class="historia-encabezado">
            <p>Agregar Historias</p>

        </div>
        <div class="cuerpo-historias-nuevas">
            <div class="contenedor-datos-historia">
                <div class="datos-historia">

                    <label class="datos-historias-titulo" for="fecha">Fecha</label>
                    <label class="datos-historias-titulo" for="especialistaId">Atendido por </label>
                </div>
                <div class="datos-historia">
                    <input autocomplete="off" type="date" name="fecha" id="" value="">
                    <select name="especialistaId" id="">

                        <option value="" selected disabled>Seleccionar</option>
                        @foreach ($especialistas as $especialista)
                            @if ($especialista->sede_id == session('sede.id'))
                                <option value="{{ $especialista->id }}">{{ $especialista->nombre }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="agregar-historia-tarjeta">
                <div class="datos-historia">
                    <label for="dienteId">Diente</label>
                    <label for="observacion">Observacion</label>
                </div>
                <div class="datos-historia">
                    <input type="text" class="diente-nueva-historia" list="diente-list" name="dienteId"
                        id="">
                    <datalist id="diente-list" class="diente-nueva-historia">
                        <option value="" selected disabled> seleccionar </option>
                        @foreach ($dientes as $diente)
                            <option value="{{ $diente->nombre }}"></option>
                        @endforeach
                    </datalist>
                    <textarea class="observaciones-nueva-historia" name="observacion" id=""></textarea>
                </div>

            </div>

            <button type="button" class="mas-historias">+</button>
        </div>
        <button type="button" id="boton-guardar-historias">Guardar</button>
    </div>
</div>

</div>

<script>
    const historias = @json(
        $cliente->historias->map(function ($h) {
            return array_merge($h->toArray(), [
                'fecha' => \Carbon\Carbon::parse($h->created_at)->setTimezone('America/Bogota')->format('Y-m-d'),
            ]);
        }));

    const dientes = @json($dientes);
    const especialistas = @json($especialistas);
    const clienteId = {{ $cliente->id }};
    const ultimasHistorias = @json($ultimasHistorias);
</script>

<script src="{{ asset('js/historias.js') }}"></script>

@include('partials.footer')
