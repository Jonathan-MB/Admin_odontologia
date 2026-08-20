@include('partials.head')
<link rel="stylesheet" href="{{ asset('css/crearCliente.css') }}">
<link rel="stylesheet" href="{{ asset('css/listasEditarCrear.css') }}">

<title>Editar Usuarios</title>

@include('partials.header')

<div class="contenedor-general">

    <h1 class="vista-titulo">Editar Usuarios</h1>
    <form action="{{ route('usuarios.update',$usuario->id) }}" method="post" autocomplete="off">
        @method('PATCH')
        @csrf



        <div class="contenedor-agregar-cliente">
            <div class="contenedor-agregar">
                <div>
                    <div class="elemento-formulario">
                        <label for="nombre">Nombre</label>
                        <input autocomplete="off" type="text" name="nombre" id="nombre" maxlength="45" value="{{$usuario->nombre}}" required>
                    </div>
                    <div class="elemento-formulario">
                        <label for="correo">Correo</label>
                        <input autocomplete="off" type="email" name="correo" id="correo" maxlength="150" value="{{$usuario->correo}}">
                    </div>
                </div>

                <div>

                    <div class="elemento-formulario">
                        <label for="password">Contraseña</label>
                        <input autocomplete="off" type="text" name="password" id="password" minlength="8" placeholder="contraseña">
                    </div>
                    <div class="elemento-formulario">
                        <label for="rolId">Tipo Usuario</label>
                        <select name="rolId" id="rolId">
                            <option selected value="{{$usuario->rol_id}}">{{$usuario->rol->nombre}}</option>
                            @foreach ($rols as $rol)
                                <option value="{{ $rol->id }}">{{ $rol->nombre }}</option>
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

</div>






@include('partials.footer')
