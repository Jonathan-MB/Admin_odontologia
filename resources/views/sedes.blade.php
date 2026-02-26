@include('partials.head')
<link rel="stylesheet" href="{{ asset('css/sedes.css') }}">

<title>Sedes</title>

@include('partials.header')





<div class="contenedor-general">

    <H1 class="vista-titulo">
        Selección de Sede
    </H1>
    <div class="tarjeta-sedes">
        <div class="tarjeta-titulo">
            <p>Seleccione una Sede</p>
        </div>

        <div class="contenedor-sedes">

            @foreach ($sedes as $sede)
                <button class="seleccion-sede" data-id="{{ $sede->id }}" data-nombre="{{ $sede->nombre }}"
                    data-nit="{{ $sede->nit }}" data-direccion="{{ $sede->direccion }}"
                    data-telefono="{{ $sede->telefono }}" data-celular="{{ $sede->celular }}">
                    <p>{{ $sede->nombre }}</p>
                </button>
            @endforeach

        </div>

    </div>
</div>


<script src="{{ asset('js/sede.js') }}"></script>



@include('partials.footer')
