@include('partials.head')
<link rel="stylesheet" href="{{ asset('css/crearCliente.css') }}">
<link rel="stylesheet" href="{{ asset('css/listasEditarCrear.css') }}">

<title>Editar Especialista</title>

@include('partials.header')

<div class="contenedor-general">

    <h1 class="vista-titulo">Editar especialistas</h1>
    <form action="{{ route('especialistas.update',$especialista->id) }}" method="post" autocomplete="off">
        @method('PATCH')
        @csrf



        <div class="contenedor-agregar-cliente">
            <div class="contenedor-agregar">
                <div>
                    <div class="elemento-formulario">
                        <label for="nombre">Nombre</label>
                        <input autocomplete="off" type="text" name="nombre" id="nombre" maxlength="45" value="{{$especialista->nombre}}" required>
                    </div>
                </div>

                <div>

                    <div class="elemento-formulario">
                        <label for="sedeId">Sede</label>
                        <select name="sedeId" id="sedeId">
                            <option selected value="{{$especialista->sede_id}}">{{$especialista->sede->nombre}}</option>
                            @foreach ($sedes as $sede)
                                <option value="{{ $sede->id }}">{{ $sede->nombre }}</option>
                            @endforeach
                        </select>

                    </div>
                </div>

                <button class="boton-guardar" type="submit">
                    <img src="{{ asset('img/guardar.png') }}" alt="">
                    <p>Guardar</p>
                </button>
            </div>
        </div>
    </form>







@include('partials.footer')
