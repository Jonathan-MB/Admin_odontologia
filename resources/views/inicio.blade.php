@include('partials.head')

<link rel="stylesheet" href="{{ asset('css/inicio.css') }}">
<title>Inicio</title>

@include('partials.header')

<div class="contenedor-tarjetas">



    @if (auth()->user()->rol_id == 1)
        <a class="tarjeta-inicio" href="*" id="agregar-usuario">
            <div class="contenedor-inicio-img">
                <img class="inicio-img" src="{{asset('img/logoAgregarUsuario.png')}}" alt="">
            </div>
            <div class="contenedor-titulo-tarjeta">
                <p class="titulo-tarjeta">Agregar Usuario</p>
            </div>
        </a>
    @endif


    <a class="tarjeta-inicio" href="{{ route('clientes.create') }}">
        <div class="contenedor-inicio-img">
            <img class="inicio-img" src="{{ asset('img/logoAgregarUC.png') }}" alt="">
        </div>
        <div class="contenedor-titulo-tarjeta">
            <p class="titulo-tarjeta">Agregar Cliente</p>
        </div>
    </a>


    <a class="tarjeta-inicio"href="{{ route('busqueda') }}">
        <div class="contenedor-inicio-img">
            <img class="inicio-img" src="{{ asset('img/lupa.png') }}" alt="">
        </div>
        <div class="contenedor-titulo-tarjeta">
            <p class="titulo-tarjeta">Buscar Cliente</p>
        </div>
    </a>


    <a class="tarjeta-inicio" href="{{ route('facturacion') }}">
        <div class="contenedor-inicio-img">
            <img class="inicio-img" src="{{ asset('img/facturaDiente.png') }}" alt="">
        </div>
        <div class="contenedor-titulo-tarjeta">
            <p class="titulo-tarjeta">Facturar</p>
        </div>
    </a>

</div>



@include('partials.footer')
