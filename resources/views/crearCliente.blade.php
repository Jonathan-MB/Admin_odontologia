@include('partials.head')
<link rel="stylesheet" href="{{ asset('css/crearCliente.css') }}">

<title>Agregar Cliente</title>

@include('partials.header')

<div class="contenedor-general">

    <h1 class="vista-titulo">Agregar Cliente</h1>

    <form id="form-crear-cliente"  action="{{ route('clientes.store') }}" method="post" autocomplete="off">
        @csrf



        <div class="contenedor-agregar-cliente">

            <div class="linea-agregar-cliente">
                <div class="elemento-formulario">
                    <label for="tipo-doc-input">Tipo Documento</label>
                    <input type="text" id="tipo-doc-input" list="tipo-doc-list"
                        placeholder="Buscar tipo documento..." required>
                    <input type="hidden" name="tipoDocumentoId" id="tipo-doc-hidden">
                    <datalist id="tipo-doc-list">
                        @foreach ($tipoDocumentos as $tipoDocumento)
                            <option value="{{ $tipoDocumento->nombre }}" data-id="{{ $tipoDocumento->id }}">
                        @endforeach
                    </datalist>
                </div>

                <div class="elemento-formulario">
                    <label for="num-doc">Número Documento</label>
                    <input type="text" name="numeroDocumento" id="num-doc" class="input" minlength="5"
                        maxlength="45" pattern="[0-9]+" required autocomplete="off">
                </div>

                <div class="elemento-formulario">
                    <label for="eps-input">EPS</label>
                    <input type="text" id="eps-input" list="eps-list" placeholder="Buscar EPS..." required>
                    <input type="hidden" name="epsId" id="eps-id-hidden">
                    <datalist id="eps-list">
                        @foreach ($eps as $ep)
                            <option value="{{ $ep->nombre }}" data-id="{{ $ep->id }}">
                        @endforeach
                    </datalist>
                </div>

                <div class="elemento-formulario">
                    <label for="fecha-nacimiento">Fecha Nacimiento</label>
                    <input type="date" name="fechaNacimiento" id="fecha-nacimiento" required autocomplete="off">
                </div>
            </div>

            <div class="linea-agregar-cliente">

                <div class="elemento-formulario">
                    <label for="nombre">Nombre</label>
                    <input autocomplete="off" type="text" name="nombre" id="nombre" maxlength="45" required>
                </div>

                <div class="elemento-formulario">
                    <label for="primer-apellido">Primer Apellido</label>
                    <input autocomplete="off" type="text" name="primerApellido" id="primer-apellido" maxlength="45"
                        required>
                </div>

                <div class="elemento-formulario">
                    <label for="segundo-apellido">Segundo Apellido</label>
                    <input autocomplete="off" type="text" name="segundoApellido" id="segundo-apellido" maxlength="45"
                        required>
                </div>
            </div>

            <div class="linea-agregar-cliente">
                <div class="elemento-formulario">
                    <label for="direccion">Dirección</label>
                    <input autocomplete="off" type="text" name="direccion" id="direccion" maxlength="80" required>
                </div>

                <div class="elemento-formulario">
                    <label for="correo">Correo</label>
                    <input autocomplete="off" type="email" name="correo" id="correo" maxlength="150">
                </div>

                <div class="elemento-formulario">
                    <label for="celular">Celular</label>
                    <input autocomplete="off" type="text" name="telefono" id="celular" maxlength="45" required>
                </div>

                <button class="boton-guardar" type="submit">
                    <img src="{{ asset('img/logoAgregarUsuarioN.png') }}" alt="">
                    <p>Guardar</p>
                </button>
            </div>

        </div>
    </form>

</div>

<script>
    const tipoDocumentos = @json($tipoDocumentos);
    const epsData = @json($eps);
</script>

<script src="{{ asset('js/crearCliente.js') }}"></script>


@include('partials.footer')
