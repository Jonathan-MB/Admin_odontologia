@include('partials.head')
<link rel="stylesheet" href="{{ asset('css/crearCliente.css') }}">
<link rel="stylesheet" href="{{ asset('css/listasEditarCrear.css') }}">

<title>Usuarios</title>

@include('partials.header')

<div class="contenedor-general">

    <h1 class="vista-titulo">Usuarios</h1>
    <form action="{{ route('clientes.store') }}" method="post" autocomplete="off">
        @csrf



        <div class="contenedor-agregar-cliente">
            <p class="titulo-tarjeta-agregar">Agregar Usuario</p>
            <div class="contenedor-agregar">
                <div>
                    <div class="elemento-formulario">
                        <label for="nombre">Nombre</label>
                        <input autocomplete="off" type="text" name="nombre" id="nombre" maxlength="45" required>
                    </div>
                    <div class="elemento-formulario">
                        <label for="correo">Correo</label>
                        <input autocomplete="off" type="email" name="correo" id="correo" maxlength="150">
                    </div>
                </div>

                <div>

                    <div class="elemento-formulario">
                        <label for="celular">Contraseña</label>
                        <input autocomplete="off" type="text" name="password" id="password" minlength="8" required>
                    </div>
                    <div class="elemento-formulario">
                        <label for="rolId">Tipo Usuario</label>
                        <select name="rolId" id="rolId">
                            <option selected disabled>Seleccionar</option>
                            @foreach ($rols as $rol)
                                <option value="{{ $rol->id }}">{{ $rol->nombre }}</option>
                            @endforeach
                        </select>

                    </div>
                </div>

                <button class="boton-guardar" type="submit">
                    <img src="{{ asset('img/logoAgregarUsuarioN.png') }}" alt="">
                    <p>Guardar</p>
                </button>
            </div>
        </div>
    </form>


    <div class="contenedor-listas">
        <div class="titulo-listas">
            <p>Lista de Usuarios</p>
        </div>
        <div class="linea-listas encabezado-lista">
            <p class="listas-titulos">Nombre</p>
            <p class="listas-titulos">Correo</p>
            <p class="listas-titulos">Tipo Usuario</p>
            <p class="listas-titulos">Accion</p>
        </div>
        <div class="todos-lista">
            @foreach ($usuarios as $usuario)
                <div class="linea-listas">
                    <p>{{ $usuario->nombre }}</p>
                    <p>{{ $usuario->correo }}</p>
                    <p>{{ $usuario->rol->nombre }}</p>
                    <a class="editar-listas" href="{{ route('usuarios.edit', $usuario->id) }}">
                        <p>Editar</p>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>







@include('partials.footer')
