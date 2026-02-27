@include('partials.head')
<link rel="stylesheet" href="{{ asset('css/busqueda.css') }}">

<title>Busqueda</title>

@include('partials.header')





<div class="contenedor-general">

    <H1 class="vista-titulo">Busqueda de Cliente</H1>
    <form action="{{route('clientes.buscar')}}" method="post" autocomplete="off">
        @csrf
        <div class="contenedor-buscador">
            <div>
                <input type="text" name="numeroDocumento" placeholder="Numero de Documento" class="buscador-input" minlength="5"maxlength="15" pattern="[0-9]+" required autocomplete="off">
            </div>
            <button type="submit">
                <img src="{{asset('img/lupa.png')}}" alt="">
                <p>buscar</p>
            </button>
        </div>
    </form>
@include('partials.mensaje')

</div>

@include('partials.footer')
