</head>

<body>

    <header class="header">
        <!-- Logo -->
        <div class="header-contenedor">
            <a class="header-logo" href="{{ route('inicio') }}">
                <img src="{{ asset('img/logoCompleto.png') }}" alt="logo">
            </a>
        </div>

        <!-- Sede -->
        <div class="header-contenedor">
            <p class="sede-nombre">{{ session('sede.nombre') }}</p>
        </div>

        <!-- Usuario -->
        <div class="header-contenedor" id= "contenedor-usuario">
            <p class="header-usuario-nombre">{{ auth()->user()->nombre }}</p>
            <div class="usuario-imagen">
                <img src="{{ asset('img/usuario.png') }}" alt="foto de usuario">
            </div>
            <div class="menu-usuario hidden" id="menu-usuario">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-logout">Cerrar sesión</button>
                </form>
            </div>
        </div>

    </header>
