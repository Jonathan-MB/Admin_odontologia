@include('partials.head')
<link rel="stylesheet" href="{{ asset('css/crearCliente.css') }}">
<link rel="stylesheet" href="{{ asset('css/listasEditarCrear.css') }}">

<title>Lista Eps</title>

@include('partials.header')

<div class="contenedor-general">

    <h1 class="vista-titulo">Eps</h1>
    <form action="{{ route('eps.store') }}" method="post" autocomplete="off">
        @csrf



        <div class="contenedor-agregar-cliente">
            <p class="titulo-tarjeta-agregar">Agregar Eps</p>
            <div class="contenedor-agregar">
                <div>
                    <div class="elemento-formulario">
                        <label for="nombre">Nombre</label>
                        <input autocomplete="off" type="text" list="lista-eps" name="nombre" id="nombre"
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


    <div class="contenedor-listas">
        <div class="titulo-listas">
            <p>Lista de Eps</p>
        </div>
        <div class="linea-listas encabezado-lista">
            <p class="listas-titulos">Nombre</p>

            <p class="listas-titulos">Accion</p>
        </div>
        <div class="todos-lista">
            @foreach ($eps as $ep)
                <div class="linea-listas">
                    <p>{{ $ep->nombre }}</p>
                    <a class="editar-listas" href="{{ route('eps.edit', $ep->id) }}">
                        <p>Editar</p>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>







@include('partials.footer')
