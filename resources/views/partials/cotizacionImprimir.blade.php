<div class="factura-imprimir-contenedor hidden" id="cotizacion-imprimible">
    <div class="secciones-factura-imprimir encabezado-factura-imprimir">
        <div class="contenedor-imagen-factura-imprimir">
            <img src="{{ asset('img/logoBN.png') }}" alt="">
        </div>
        <div class="datos-encabezado-factura-imprimir">
            <div><p class="titulo-datos-encabezado-factura-imprimir">CENTRO DE ODONTOLOGÍA INTEGRAL</p></div>
            <div><p class="sede-datos-encabezado-factura-imprimir factura-imprimir-titulo">{{ session('sede.nombre') }}</p></div>
            <div><p class="nit-datos-encabezado-factura-imprimir">Nit - {{ session('sede.nit') }}</p></div>
        </div>
        <div class="numero-factura-encabezado-factura-imprimir">
            <p>COTIZACIÓN</p>
        </div>
    </div>

    <div class="secciones-factura-imprimir direcciones-factura-imprimir">
        <div class="direccion-items">
            <img src="{{ asset('img/direccion.png') }}" alt="">
            <p>{{ session('sede.direccion') }}</p>
        </div>
        <div class="direccion-items">
            <img src="{{ asset('img/tel.png') }}" alt="">
            <p>{{ session('sede.telefono') }}</p>
        </div>
        <div class="direccion-items">
            <img src="{{ asset('img/wapp.png') }}" alt="">
            <p>{{ session('sede.celular') }}</p>
        </div>
        <p>Cali - Colombia</p>
    </div>

    <div class="secciones-factura-imprimir cuerpo-factura-imprimir">
        <div class="columna-cuerpo-factura-imprimir">
            <div class="item-cuerpo-factura-imprimir">
                <p class="item-titulo-cuerpo-factura-imprimir">Fecha :</p>
                <p id="cotizacion-fecha">00/00/0000</p>
            </div>
            <div class="item-cuerpo-factura-imprimir">
                <p class="item-titulo-cuerpo-factura-imprimir">Nombre :</p>
                <p id="cotizacion-nombre">Nombre</p>
            </div>
        </div>
        <div class="columna-cuerpo-factura-imprimir">
            <div class="item-cuerpo-factura-imprimir">
                <p class="item-titulo-cuerpo-factura-imprimir">Hora :</p>
                <p id="cotizacion-hora">00:00:00</p>
            </div>
        </div>
    </div>

    {{-- Observación de cotización --}}
    <div class="secciones-factura-imprimir" style="padding: 10px 20px;">
        <p class="item-titulo-cuerpo-factura-imprimir">Cotización :</p>
        <p id="cotizacion-observacion" style="white-space: pre-wrap; margin-top: 6px;"></p>
    </div>
</div>