@include('partials.head')
<link rel="stylesheet" href="{{ asset('css/crearCliente.css') }}">

<title>Agregar Cliente</title>

@include('partials.header')

<div class="contenedor-general">

    <h1 class="vista-titulo">Editar Cliente</h1>

    <form action="{{ route('clientes.update', $cliente->id) }}" method="post" autocomplete="off">
        @method('PATCH')
        @csrf

        <div class="contenedor-agregar-cliente">

            <div class="linea-agregar-cliente">
                <div class="elemento-formulario">
                    <label for="tipo-doc">Tipo Documento</label>
                    <select name="tipoDocumentoId" id="tipo-doc"  >
                        <option value="{{$cliente->tipo_documento_id}}" selected>{{$cliente->tipoDocumento->nombre}}</option>
                        @foreach ($tipoDocumentos as $tipoDocumento)
                            <option value="{{ $tipoDocumento->id }}">{{ $tipoDocumento->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="elemento-formulario">
                    <label for="num-doc">Número Documento</label>
                    <input type="text" name="numeroDocumento" id="num-doc" class="input" minlength="5"
                        maxlength="45" pattern="[0-9]+" value="{{ $cliente->numero_documento }}" autocomplete="off">
                </div>

                <div class="elemento-formulario">
                    <label for="eps">EPS</label>
                    <select name="epsId" id="eps">
                        <option value="{{ $cliente->eps_id }}" selected >{{ $cliente->eps->nombre }}</option>
                        @foreach ($eps as $ep)
                            <option value="{{ $ep->id }}">{{ $ep->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="elemento-formulario">
                    <label for="fecha-nacimiento">Fecha Nacimiento</label>
                    <input type="date" name="fechaNacimiento" id="fecha-nacimiento" value="{{ \Carbon\Carbon::parse($cliente->fecha_nacimiento)->format('Y-m-d') }}">
                </div>
            </div>

            <div class="linea-agregar-cliente">

                <div class="elemento-formulario">
                    <label for="nombre">Nombre</label>
                    <input type="text" name="nombre" id="nombre" maxlength="45" value="{{ $cliente->nombre }}" autocomplete="off">
                </div>

                <div class="elemento-formulario">
                    <label for="primer-apellido">Primer Apellido</label>
                    <input type="text" name="primerApellido" id="primer-apellido" maxlength="45" value="{{ $cliente->primer_apellido }}" autocomplete="off">
                </div>

                <div class="elemento-formulario">
                    <label for="segundo-apellido">Segundo Apellido</label>
                    <input type="text" name="segundoApellido" id="segundo-apellido" maxlength="45" value="{{ $cliente->segundo_apellido }}" autocomplete="off">
                </div>
            </div>

            <div class="linea-agregar-cliente">
                <div class="elemento-formulario">
                    <label for="direccion">Dirección</label>
                    <input type="text" name="direccion" id="direccion" maxlength="80" value="{{ $cliente->direccion }}" autocomplete="off">
                </div>

                <div class="elemento-formulario">
                    <label for="correo">Correo</label>
                    <input type="email" name="correo" id="correo" maxlength="150" value="{{ $cliente->correo }}" autocomplete="off">
                </div>

                <div class="elemento-formulario">
                    <label for="celular">Celular</label>
                    <input type="text" name="telefono" id="celular" maxlength="45" value="{{ $cliente->telefono }}" autocomplete="off">
                </div>

                <button class="boton-guardar" type="submit">
                    <img src="{{ asset('img/logoAgregarUsuarioN.png') }}" alt="">
                    <p>Actualizar</p>
                </button>
            </div>

        </div>
    </form>

</div>

@include('partials.footer')
