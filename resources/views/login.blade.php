@include('partials.head')

<link rel="stylesheet" href="{{ asset('css/login.css') }}">

<title>Login</title>

</head>

<body>

    <header class="header-login">
        <div class="header-contenedor-logo">
            <a class="header-logo-login" href="{{ route('inicio') }}"><img src="{{ asset('img/logoCompleto.png') }}"
                    alt=""></a>
        </div>
    </header>


    <div class="contenedor-login">
        <form action="{{ route('web.login') }}" method="post">
            @if ($errors->has('correo'))
                <div class="popup-error" id="popup-error">
                    <p>{{ $errors->first('correo') }}</p>
                </div>
            @endif
            @if (session('error'))
                <div class="popup-error" id="popup-error">
                    <p>{{ session('error') }}</p>
                </div>
            @endif
            @csrf
            <div class="tarjeta-login">
                <input type="email" name="correo" placeholder="Correo" id="" required>
                <input type="password" name="password" placeholder="Contraseña" id="" required
                    autocomplete="new-password">
                <button type="submit">Iniciar Sesion</button>
            </div>

        </form>
    </div>


</body>

</html>
