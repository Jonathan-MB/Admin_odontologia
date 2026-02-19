@include('partials.head')
<link rel="stylesheet" href="{{ asset('css/crearCliente.css') }}">

<title>Agregar Cliente</title>

@include('partials.header')

<div class="contenedor-general">

    <h1 class="vista-titulo">Agregar Cliente</h1>

    <form action="{{ route('clientes.store') }}" method="post">
        @csrf



        <div class="contenedor-agregar-cliente">

            <div class="linea-agregar-cliente">
                <div class="elemento-formulario">
                    <label for="tipo-doc">Tipo Documento</label>
                    <select name="tipoDocumentoId" id="tipo-doc" required>
                        <option value="" selected disabled></option>
                        @foreach ($tipoDocumentos as $tipoDocumento)
                            <option value="{{ $tipoDocumento->id }}">{{ $tipoDocumento->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="elemento-formulario">
                    <label for="num-doc">Número Documento</label>
                    <input type="text" name="numeroDocumento" id="num-doc" class="input" minlength="5"
                        maxlength="45" pattern="[0-9]+" required>
                </div>

                <div class="elemento-formulario">
                    <label for="eps">EPS</label>
                    <select name="epsId" id="eps" required>
                        <option value="" selected disabled></option>
                        @foreach ($eps as $ep)
                            <option value="{{ $ep->id }}">{{ $ep->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="elemento-formulario">
                    <label for="fecha-nacimiento">Fecha Nacimiento</label>
                    <input type="date" name="fechaNacimiento" id="fecha-nacimiento" required>
                </div>
            </div>

            <div class="linea-agregar-cliente">

                <div class="elemento-formulario">
                    <label for="nombre">Nombre</label>
                    <input type="text" name="nombre" id="nombre" maxlength="45" required>
                </div>

                <div class="elemento-formulario">
                    <label for="primer-apellido">Primer Apellido</label>
                    <input type="text" name="primerApellido" id="primer-apellido" maxlength="45" required>
                </div>

                <div class="elemento-formulario">
                    <label for="segundo-apellido">Segundo Apellido</label>
                    <input type="text" name="segundoApellido" id="segundo-apellido" maxlength="45" required>
                </div>
            </div>

            <div class="linea-agregar-cliente">
                <div class="elemento-formulario">
                    <label for="direccion">Dirección</label>
                    <input type="text" name="direccion" id="direccion" maxlength="80" required>
                </div>

                <div class="elemento-formulario">
                    <label for="correo">Correo</label>
                    <input type="email" name="correo" id="correo" maxlength="150">
                </div>

                <div class="elemento-formulario">
                    <label for="celular">Celular</label>
                    <input type="text" name="telefono" id="celular" maxlength="45" required>
                </div>

                <button class="boton-guardar" type="submit">
                    <img src="{{ asset('img/logoAgregarUsuarioN.png') }}" alt="">
                    <p>Guardar</p>
                </button>
            </div>

        </div>
    </form>

</div>

@include('partials.footer')
