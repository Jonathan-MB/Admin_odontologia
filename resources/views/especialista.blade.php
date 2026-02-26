@include('partials.head')
<link rel="stylesheet" href="{{ asset('css/crearCliente.css') }}">
<link rel="stylesheet" href="{{ asset('css/listasEditarCrear.css') }}">

<title>Especialistas</title>

@include('partials.header')

<div class="contenedor-general">

    <h1 class="vista-titulo">Especialistas</h1>
    <form action="{{ route('especialistas.store') }}" method="post" autocomplete="off">
        @csrf



        <div class="contenedor-agregar-cliente">
            <p class="titulo-tarjeta-agregar">Agregar Especialista</p>
            <div class="contenedor-agregar">
                <div>
                    <div class="elemento-formulario">
                        <label for="nombre">Nombre</label>
                        <input autocomplete="off" type="text" name="nombre" id="nombre" maxlength="45" required>
                    </div>
                    
                </div>

                <div>

                    <div class="elemento-formulario">
                        <label for="sedeId">Sede</label>
                        <select name="sedeId" id="sedeId" required>
                            <option selected value="" disabled>Seleccionar</option>
                            @foreach ($sedes as $sede)
                                <option value="{{ $sede->id }}">{{ $sede->nombre }}</option>
                            @endforeach
                        </select>

                    </div>
                </div>

                <button class="boton-guardar" type="submit">
                    <img src="{{ asset('img/logoAgregarUsuarioN.png') }}" alt="">
                    <p>Guardar</p>
                </button>
            </div>
        </div>
    </form>


    @include('partials.mensaje')


    <div class="contenedor-listas">
        <div class="titulo-listas">
            <p>Lista de Usuarios</p>
        </div>
        <div class="linea-listas encabezado-lista">
            <p class="listas-titulos">Nombre</p>

            <p class="listas-titulos">Accion</p>
        </div>
        <div class="todos-lista">
            @foreach ($especialistas as $especialista)
                <div class="linea-listas">
                    <p>{{ $especialista->nombre }}</p>
                    <p>{{ $especialista->sede->nombre }}</p>
                    <a class="editar-listas" href="{{ route('especialistas.edit', $especialista->id) }}">
                        <p>Editar</p>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>







@include('partials.footer')
