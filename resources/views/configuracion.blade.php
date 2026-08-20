@include('partials.head')

<link rel="stylesheet" href="{{ asset('css/inicio.css') }}">
<link rel="stylesheet" href="{{ asset('css/configuracion.css') }}">
<title>Configuración</title>

@include('partials.header')


<h3 class="vista-titulo">Configuración</h3>
<div class="contenedor-tarjetas">



    @if (auth()->user()->rol_id == 1)
        <a class="tarjeta-inicio" href="{{route('usuarios.index')}}" id="agregar-usuario">
            <div class="contenedor-inicio-img">
                <img class="inicio-img" src="{{asset('img/logoAgregarUC.png')}}" alt="">
            </div>
            <div class="contenedor-titulo-tarjeta">
                <p class="titulo-tarjeta">Usuarios</p>
            </div>
        </a>
        <a class="tarjeta-inicio" href="{{route('especialistas.index')}}" id="agregar-especialista">
            <div class="contenedor-inicio-img">
                <img class="inicio-img" src="{{asset('img/especialista.png')}}" alt="">
            </div>
            <div class="contenedor-titulo-tarjeta">
                <p class="titulo-tarjeta">Especialistas</p>
            </div>
        </a>
    @endif


    <a class="tarjeta-inicio" href="{{ route('eps.index') }}">
        <div class="contenedor-inicio-img">
            <img class="inicio-img" src="{{ asset('img/eps.png') }}" alt="">
        </div>
        <div class="contenedor-titulo-tarjeta">
            <p class="titulo-tarjeta">EPS</p>
        </div>
    </a>


    <a class="tarjeta-inicio" href="{{ route('tipoDocumentos.index')}}">
        <div class="contenedor-inicio-img">
            <img class="inicio-img" src="{{ asset('img/documento.png') }}" alt="">
        </div>
        <div class="contenedor-titulo-tarjeta">
            <p class="titulo-tarjeta">Tipos Documentos</p>
        </div>
    </a>


    <a class="tarjeta-inicio" href="{{ route('metodoPagos.index') }}">
        <div class="contenedor-inicio-img">
            <img class="inicio-img" src="{{ asset('img/img_tarjetacredito.png') }}" alt="">
        </div>
        <div class="contenedor-titulo-tarjeta">
            <p class="titulo-tarjeta">Metodos de Pago</p>
        </div>
    </a>




</div>



@include('partials.footer')
