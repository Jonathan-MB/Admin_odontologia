@include('partials.head')
<link rel="stylesheet" href="{{ asset('css/crearCliente.css') }}">
<link rel="stylesheet" href="{{ asset('css/listasEditarCrear.css') }}">
<link rel="stylesheet" href="{{ asset('css/busqueda.css') }}">

<title>Resultados - {{ $busqueda }}</title>

@include('partials.header')

<div class="contenedor-general">

    <H1 class="vista-titulo">Resultados de "{{ $busqueda }}"</H1>

    <form action="{{ $destino === 'facturaCliente' ? route('facturas.buscar') : route('clientes.buscar') }}"
        method="post" autocomplete="off">
        @csrf
        <div class="contenedor-buscador">
            <div>
                <input autocomplete="off" type="text" name="numeroDocumento" placeholder="Documento o nombre"
                    class="buscador-input" minlength="3" maxlength="60" value="{{ $busqueda }}" required>
            </div>
            <button type="submit">
                <img src="{{ asset('img/lupa.png') }}" alt="">
                <p>buscar</p>
            </button>
        </div>
    </form>

    @include('partials.mensaje')

    @if ($clientes->count() >= 50)
        <div class="mensaje mensaje-alerta">
            <p>Hay muchas coincidencias. Se muestran los 50 primeros, escribe el apellido para afinar.</p>
        </div>
    @endif

    <div class="contenedor-listas">
        <div class="titulo-listas">
            <p>{{ $clientes->count() }} clientes encontrados</p>
        </div>
        <div class="linea-listas encabezado-lista">
            <p class="listas-titulos">Nombre</p>
            <p class="listas-titulos">Documento</p>
            <p class="listas-titulos">Celular</p>
            <p class="listas-titulos">Accion</p>
        </div>
        <div class="todos-lista">
            @foreach ($clientes as $cliente)
                <div class="linea-listas">
                    <p>{{ $cliente->nombre_completo }}</p>
                    <p>{{ $cliente->numero_documento }}</p>
                    <p>{{ $cliente->telefono }}</p>
                    <a class="editar-listas" href="{{ route($destino, $cliente->id) }}">
                        <p>Abrir</p>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>

@include('partials.footer')
