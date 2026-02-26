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
    
