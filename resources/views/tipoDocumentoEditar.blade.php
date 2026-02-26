@include('partials.head')
<link rel="stylesheet" href="{{ asset('css/crearCliente.css') }}">
<link rel="stylesheet" href="{{ asset('css/listasEditarCrear.css') }}">

<title>Editar tipo Documento</title>

@include('partials.header')

<div class="contenedor-general">

    <h1 class="vista-titulo">Editar tipo Documentos</h1>
    <form action="{{ route('tipoDocumentos.update',$tipoDocumento->id) }}" method="post" autocomplete="off">
@method('PUT')
        @csrf


        <div class="contenedor-agregar-cliente">

            <div class="contenedor-agregar">
                <div>
                    <div class="elemento-formulario">
                        <label for="nombre">Nombre</label>
                        <input autocomplete="off" type="text" name="nombre" id="nombre" maxlength="45" value="{{$tipoDocumento->nombre}}" required>
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
