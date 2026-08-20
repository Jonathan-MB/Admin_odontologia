@include('partials.head')
<link rel="stylesheet" href="{{ asset('css/crearCliente.css') }}">
<link rel="stylesheet" href="{{ asset('css/listasEditarCrear.css') }}">

<title>Lista Metodos de Pago</title>

@include('partials.header')

<div class="contenedor-general">

    <h1 class="vista-titulo">Metodos de Pago</h1>
    <form action="{{ route('metodoPagos.store') }}" method="post" autocomplete="off">
        @csrf



        <div class="contenedor-agregar-cliente">
            <p class="titulo-tarjeta-agregar">Agregar Metodo de Pago</p>
            <div class="contenedor-agregar">
                <div>
                    <div class="elemento-formulario">
                        <label for="nombre">Nombre</label>
                        <input autocomplete="off" type="text" name="nombre" id="nombre"
                            maxlength="45" required>
                    </div>

                </div>


                <button class="boton-guardar" type="submit">
                    <img src="{{ asset('img/guardar.png') }}" alt="">
                    <p>Guardar</p>
                </button>
            </div>
        </div>
    </form>

    @include('partials.mensaje')

    @if ($errors->any())
        <div class="mensaje mensaje-error">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif


    <div class="contenedor-listas">
        <div class="titulo-listas">
            <p>Lista de Metodos de Pago</p>
        </div>
        <div class="linea-listas encabezado-lista">
            <p class="listas-titulos">Nombre</p>

            <p class="listas-titulos">Accion</p>
        </div>
        <div class="todos-lista">
            @foreach ($metodoPagos as $metodoPago)
                <div class="linea-listas">
                    <p>{{ $metodoPago->nombre }}</p>
                    <a class="editar-listas" href="{{ route('metodoPagos.edit', $metodoPago->id) }}">
                        <p>Editar</p>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>







@include('partials.footer')
