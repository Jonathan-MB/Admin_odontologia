@include('partials.head')
<link rel="stylesheet" href="{{ asset('css/crearCliente.css') }}">
<link rel="stylesheet" href="{{ asset('css/listasEditarCrear.css') }}">

<title>Editar Metodo de Pago</title>

@include('partials.header')

<div class="contenedor-general">

    <h1 class="vista-titulo">Editar Metodo de Pago</h1>

    @if ($errors->any())
        <div class="mensaje mensaje-error">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('metodoPagos.update',$metodoPago->id) }}" method="post" autocomplete="off">
@method('PUT')
        @csrf


        <div class="contenedor-agregar-cliente">

            <div class="contenedor-agregar">
                <div>
                    <div class="elemento-formulario">
                        <label for="nombre">Nombre</label>
                        <input autocomplete="off" type="text" name="nombre" id="nombre" maxlength="45" value="{{$metodoPago->nombre}}" required>
                    </div>
                </div>



                <button class="boton-guardar" type="submit">
                    <img src="{{ asset('img/guardar.png') }}" alt="">
                    <p>Guardar</p>
                </button>
            </div>
        </div>
    </form>


</div>




@include('partials.footer')
