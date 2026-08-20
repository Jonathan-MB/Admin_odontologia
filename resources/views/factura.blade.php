@include('partials.head')

<link rel="stylesheet" href="{{ asset('css/historias.css') }}">
<link rel="stylesheet" href="{{ asset('css/factura.css') }}">
<link rel="stylesheet" href="{{ asset('css/facturaImprimir.css') }}">

<title> Factura - {{ $cliente->nombre_completo }}</title>

@include('partials.header')





<div class="contenedor-general">

    <H1 class="vista-titulo">Facturas Cliente</H1>


    <div class="contenedor-agregar-cliente">
        <div class="linea-agregar-cliente">
            <div class="elemento-formulario">
                <p class="titulo-elemento">No. Historia</p>
                <p class="dato-elemento">888888888888</p>
            </div>

            <div class="elemento-formulario">
                <p class="titulo-elemento-p">Tipo Documento</p>
                <p class="dato-traido">{{ $cliente->tipoDocumento->nombre }}</p>
            </div>
            <div class="elemento-formulario">
                <p class="titulo-elemento-p">Numero Documento</p>
                <p class="dato-traido">{{ $cliente->numero_documento }}</p>
            </div>


            <div class="elemento-formulario">
                <p class="titulo-elemento-p">Fecha Nacimiento</p>
                <p class="dato-traido">
                    {{ \Carbon\Carbon::parse($cliente->fecha_nacimiento)->format('d/m/Y') }}
                </p>
            </div>



        </div>

        <div class="linea-agregar-cliente">

            <div class="elemento-formulario">
                <p class="titulo-elemento-p">Eps</p>
                <p class="dato-traido">{{ $cliente->eps->nombre }}</p>
            </div>



            <div class="elemento-formulario">
                <p class="titulo-elemento-p">Nombre</p>
                <p class="dato-traido">{{ $cliente->nombre }}</p>
            </div>
            <div class="elemento-formulario">
                <p class="titulo-elemento-p">Primer Apellido</p>
                <p class="dato-traido">{{ $cliente->primer_apellido }}</p>
            </div>
            <div class="elemento-formulario">
                <p class="titulo-elemento-p">Segundo Apellido</p>
                <p class="dato-traido">{{ $cliente->segundo_apellido }}</p>
            </div>
        </div>

        <div class="linea-agregar-cliente">


            <div class="elemento-formulario">
                <p class="titulo-elemento-p">Direccion</p>
                <p class="dato-traido">{{ $cliente->direccion }}</p>
            </div>
            <div class="elemento-formulario">
                <p class="titulo-elemento-p">Correo</p>
                <p class="dato-traido">{{ $cliente->correo }}</p>
            </div>
            <div class="elemento-formulario">
                <p class="titulo-elemento-p">Celular</p>
                <p class="dato-traido">{{ $cliente->telefono }}</p>
            </div>

            <a href="{{ route('clientes.edit', $cliente->id) }}">
                <button class="boton-guardar" type="button">
                    <img src="{{ asset('img/editar.png') }}" alt="">
                    <p>Editar</p>
                </button>
            </a>

        </div>

        <div class="linea-agregar-cliente saldo">
            <p>Saldo: $ {{ number_format($cliente->saldo, 0, ',', '.') }}</p>

        </div>
    </div>


    <h2 class="vista-titulo">Facturas</h2>

    <div class="historias-contenedor">



        <button class="boton-historial boton-nueva-historia" id="boton-nueva-factura"> + Nueva Factura</button>






        @foreach ($cliente->facturas as $factura)
            <div class="factura-tarjeta">

                <div class="columna-factura">
                    <p class="tarjeta-encabezado">Fecha - Hora</p>
                    <p class="tarjeta-obsevacion ">{{ $factura->created_at }}</p>
                </div>


                <div class="columna-factura">
                    <p class="tarjeta-encabezado">nombre</p>
                    <p class="tarjeta-obsevacion ">{{ $factura->nombre }}</p>
                </div>
                <div class="columna-factura">
                    <p class="tarjeta-encabezado">Documento</p>
                    <p class="tarjeta-obsevacion ">{{ $cliente->numero_documento }}</p>
                </div>
                <div class="columna-factura">
                    <p class="tarjeta-encabezado">Tipo de pago</p>
                    <p class="tarjeta-obsevacion ">{{ $factura->metodoPago->nombre ?? 'Sin registrar' }}</p>
                </div>
                <div class="columna-factura">
                    <p class="tarjeta-encabezado">abono</p>
                    <p class="tarjeta-obsevacion ">$ {{ number_format($factura->abono, 0, ',', '.') }}</p>
                </div>
                <div class="columna-factura">
                    <p class="tarjeta-encabezado">Saldo</p>
                    <p class="tarjeta-obsevacion ">$ {{ number_format($factura->saldo, 0, ',', '.') }}</p>
                </div>
                <div class="columna-factura">
                    <p class="tarjeta-encabezado">No. Factura</p>
                    <p class="tarjeta-obsevacion ">{{ str_pad($factura->no_factura, 4, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div class="columna-factura columna-boton">
                    <button type="button" class="Factura-antigua-imprimir imprimir-anterior"
                        data-numero="{{ str_pad($factura->no_factura, 4, '0', STR_PAD_LEFT) }}"
                        data-nombre="{{ $factura->nombre }}" data-abono="{{ $factura->abono }}"
                        data-saldo="{{ $factura->saldo }}" data-fecha="{{ $factura->created_at }}"
                        data-sede-id="{{ $factura->sede_id }}"
                        data-especialista="{{ $factura->especialista->nombre ?? 'N/A' }}"
                        data-cita="{{ $factura->proxima_cita ? \Carbon\Carbon::parse($factura->proxima_cita)->format('d/m/Y H:i') : 'Sin cita' }}">
                        <img class="imagen-imprimir" src="{{ asset('img/imprimir.png') }}" title="Imprimir factura antigüa">
                    </button>
                </div>

            </div>

            <p class="tarjeta-separador">
                -------------------------------------------------------------------------------------------</p>
        @endforeach
    </div>



    {{-- --------------------POP UP------------------- --}}

    <div class="contenedor-pop-up  hidden" id="pop-up-nueva-factura">
        <div class="cerrar-pop-up">
            <button class="boton-cerrar-pop-up " id="cerrar-pop-up">
                <img src="{{ asset('img/iconoCerrar.png') }}" alt="">
            </button>
        </div>
        <div class="historias-contenedor historias-pop-up">

            <div class="historia-encabezado">
                <p>Nueva Factura</p>
            </div>
            <div class="cuerpo-historias">


                <div>

                    <div class="factura-linea">
                        <label for="nombre">Nombre</label>
                        <input autocomplete="off" type="text" name="nombre" id="nombre-input"
                            value="{{ $cliente->nombre_completo }}">
                    </div>
                    <div class="factura-linea">
                        <label for="saldo-input">Saldo</label>
                        <input autocomplete="off" type="number" placeholder="Déjelo en blanco si es solo abono"
                            name="saldo-input" id="saldo-input" min="0" step="any"
                            oninput="this.value = this.value.replace(/[^0-9.]/g, ''); if(this.value < 0 || this.value === '') this.value = '';"
                            onkeydown="if(event.key === '-' || event.key === 'e') event.preventDefault();">
                        <p><- SUMA a saldo pendiente</p>
                    </div>
                    <div class="factura-linea">
                        <label for="abono-input">Abono</label>
                        <input autocomplete="off" name="abono" type="number" id="abono-input" min="0"
                            step="any"
                            oninput="this.value = this.value.replace(/[^0-9.]/g, ''); if(this.value < 0 || this.value === '') this.value = '';"
                            onkeydown="if(event.key === '-' || event.key === 'e') event.preventDefault();">
                        <p><- RESTA a saldo pendiente</p>
                    </div>
                    <div class="factura-linea">
                        <label for="especialistaId">Atendido por</label>
                        <select name="especialistaId" id="especialista-input">
                            <option value="" selected disabled> seleccionar </option>
                            @foreach ($especialistas as $especialista)
                                <option value="{{ $especialista->id }}">{{ $especialista->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="factura-linea">
                        <label for="metodoPagoId">Metodo de pago</label>
                        <select name="metodoPagoId" id="metodo-pago-input">
                            <option value="" selected disabled> seleccionar </option>
                            @foreach ($metodoPagos as $metodoPago)
                                <option value="{{ $metodoPago->id }}">{{ $metodoPago->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="factura-linea">
                        <label for="mostrar-fecha-cita">Agendar proxima Cita</label>
                        <select name="mostrar-fecha-cita" id="mostrar-fecha-cita-id">

                            <option value="1" selected>NO</option>
                            <option value="2">Si</option>
                        </select>
                    </div>

                    <div class="factura-linea  hidden" id="proxima-cita-factura">
                        <label for="">Próxima cita</label>
                        <input autocomplete="off" type="datetime-local" min="{{ now()->format('Y-m-d\TH:i') }}">
                    </div>

                    <button class="boton-guardar" id="boton-guardar-factura" type="button">
                        <p>Guardar / Imprimir</p>
                    </button>


                </div>
            </div>
        </div>
    </div>
</div>


@include('partials.facturaImprimir')




<script src="{{ asset('js/factura.js') }}"></script>
<script src="{{ asset('js/guardarImprimirFactura.js') }}"></script>
@include('partials.footer')
