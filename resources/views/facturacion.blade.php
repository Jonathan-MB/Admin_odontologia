@include('partials.head')
<link rel="stylesheet" href="{{ asset('css/busqueda.css') }}">
<link rel="stylesheet" href="{{ asset('css/facturacion.css') }}">

<title>Facturacion</title>

@include('partials.header')





<div class="contenedor-general">

    <H1 class="vista-titulo">Facturación Cliente</H1>
    <form action="{{ route('facturas.buscar') }}" method="post" autocomplete="off">
        @csrf
        <div class="contenedor-buscador">
            <div>
                <input autocomplete="off" type="text" name="numeroDocumento" placeholder="Documento o nombre"
                    class="buscador-input" minlength="3" maxlength="60" required>
            </div>
            <button type="submit">
                <img src="{{ asset('img/lupa.png') }}" alt="">
                <p>buscar</p>
            </button>
        </div>
    </form>
    @include('partials.mensaje')
    @if (auth()->user()->rol_id == 1)
        <a type="button" class="tarjeta-inicio" href= "{{ route('facturas.totalDia') }}">
            <div class="contenedor-inicio-img">
                <img class="inicio-img" src="{{ asset('img/cuenta.png') }}" alt="">
            </div>
            <div class="contenedor-titulo-tarjeta">
                <p class="titulo-tarjeta">Total Diario</p>
            </div>
        </a>
    @endif

</div>

@include('partials.footer')
