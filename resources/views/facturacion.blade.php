@include('partials.head')
<link rel="stylesheet" href="{{ asset('css/busqueda.css') }}">
<link rel="stylesheet" href="{{ asset('css/facturacion.css') }}">

<title>Facturacion</title>

@include('partials.header')





<div class="contenedor-general">

    <H1 class="vista-titulo">Facturación Cliente</H1>
    <form action="" method="post">
        <div class="contenedor-buscador">
            <div>
                <input type="text" name="numero-documento" placeholder="Numero de Documento" class="buscador-input"
                    minlength="5"maxlength="15" pattern="[0-9]+" required>
            </div>
            <button type="submit">
                <img src="{{ asset('img/lupa.png') }}" alt="">
                <p>buscar</p>
            </button>
        </div>
    </form>

    <button type="button" class="tarjeta-inicio"href="{{ route('busqueda') }}">
        <div class="contenedor-inicio-img">
            <img class="inicio-img" src="{{ asset('img/cuenta.png') }}" alt="">
        </div>
        <div class="contenedor-titulo-tarjeta">
            <p class="titulo-tarjeta">Total Diario</p>
        </div>
    </button>

    {{-- --------------------POP UP------------------- --}}

    <div class="contenedor-pop-up hidden" >
        <div class="cerrar-pop-up">
            <button class="boton-cerrar-pop-up ">
                <img src="{{ asset('img/iconoCerrar.png') }}" alt="">
            </button>
        </div>
        <div class="historias-contenedor">

            <div class="historia-encabezado">
                <p>Nueva Factura</p>
            </div>
            <div class="cuerpo-historias">
                <div>
                    <h3 class="sede-total">Peñadent $ 49949900</h3>
                    <div class="tarjeta-total-especialista">
                        <p class="titulo-tarjeta-total columna-tarjeta">Nombre</p>
                        <p class="titulo-tarjeta-total columna-tarjeta"> Total dia</p>
                    </div>
                    {{-- @foreach ($especialistas as $specialista) --}}
                    <div class="tarjeta-total-especialista">
                        <p class="columna-tarjeta">Nombre Especialisata</p>
                        <p class="columna-tarjeta"> $ 99999999999</p>
                    </div>
                    {{-- @endforeach --}}
                    
                </div>
                <div>
                    <h3 class="sede-total">Republica de Israel $ 49949900</h3>
                    <div class="tarjeta-total-especialista">
                        <p class="titulo-tarjeta-total columna-tarjeta">Nombre</p>
                        <p class="titulo-tarjeta-total columna-tarjeta"> Total dia</p>
                    </div>


                    {{-- @foreach ($especialistas as $specialista) --}}
                    <div class="tarjeta-total-especialista">
                        <p class="columna-tarjeta">Nombre Especialisata</p>
                        <p class="columna-tarjeta"> $ 99999999999</p>
                    </div>
                    {{-- @endforeach --}}

                </div>

            </div>
        </div>
    </div>

</div>

@include('partials.footer')
