    @if (session('mensajeActualizado'))
        <div class="mensaje" id="mensaje">
            <p>{{ session('mensajeActualizado') }}</p>
        </div>
    @endif


    @if (session('mensajeCreado'))
        <div class="mensaje" id="mensaje">
            <p>{{ session('mensajeCreado') }}</p>
        </div>
    @endif

    @if (session('error'))
        <div class="mensaje mensaje-error" id="mensaje">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    @if (session('mensaje'))
        <div class="mensaje" id="mensaje">
            <p>{{ session('mensaje') }}</p>
        </div>
    @endif
