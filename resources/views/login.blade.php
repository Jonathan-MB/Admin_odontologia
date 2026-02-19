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
            @csrf
            <div class="tarjeta-login">
                <input type="email" name="correo" id="" required>
                <input type="password" name="password" id="" required>
                <button type="submit">Iniciar Sesion</button>
            </div>
            @error('correo')
                <p style="color:red">{{ $message }}</p>
            @enderror
        </form>
    </div>


</body>

</html>
