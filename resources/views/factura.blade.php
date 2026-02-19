@include('partials.head')

<link rel="stylesheet" href="{{ asset('css/historias.css') }}">
<link rel="stylesheet" href="{{ asset('css/factura.css') }}">

<title>Nombre</title>

@include('partials.header')





<div class="contenedor-general">

    <H1 class="vista-titulo">Datos Cliente</H1>


    <div class="contenedor-agregar-cliente">
        <div class="linea-agregar-cliente">
            <div class="elemento-formulario">
                <p class="titulo-elemento">No. Historia</p>
                <p class="dato-elemento">888888888888</p>
            </div>

            <div class="elemento-formulario">
                <p class="titulo-elemento-p">Tipo Documento</p>
                <p class="dato-traido">Cedula de ciudadania</p>
            </div>
            <div class="elemento-formulario">
                <p class="titulo-elemento-p">Numero Documento</p>
                <p class="dato-traido"></p>
            </div>


            <div class="elemento-formulario">
                <p class="titulo-elemento-p">Fecha Nacimiento</p>
                <p class="dato-traido"></p>
            </div>



        </div>

        <div class="linea-agregar-cliente">

            <div class="elemento-formulario">
                <p class="titulo-elemento-p">Eps</p>
                <p class="dato-traido"></p>
            </div>



            <div class="elemento-formulario">
                <p class="titulo-elemento-p">Nombre</p>
                <p class="dato-traido"></p>
            </div>
            <div class="elemento-formulario">
                <p class="titulo-elemento-p">Primer Apellido</p>
                <p class="dato-traido"></p>
            </div>
            <div class="elemento-formulario">
                <p class="titulo-elemento-p">Segundo Apellido</p>
                <p class="dato-traido"></p>
            </div>
        </div>

        <div class="linea-agregar-cliente">


            <div class="elemento-formulario">
                <p class="titulo-elemento-p">Direccion</p>
                <p class="dato-traido"></p>
            </div>
            <div class="elemento-formulario">
                <p class="titulo-elemento-p">Correo</p>
                <p class="dato-traido"></p>
            </div>
            <div class="elemento-formulario">
                <p class="titulo-elemento-p">Celular</p>
                <p class="dato-traido"></p>
            </div>



        </div>

        <div class="linea-agregar-cliente saldo">
            <p>Saldo: $ 688999</p>
        </div>

    </div>


    <h2 class="vista-titulo">Facturas</h2>

    <div class="historias-contenedor">



        <button class="boton-historial boton-nueva-historia"> + Nueva Factura</button>





        {{-- @foreach ($historias as $historia) --}}
        <div class="factura-tarjeta">

            <div>
                <p class="tarjeta-encabezado">Fecha - Hora</p>
                <p class="tarjeta-encabezado">00/00/0000-00:00</p>
            </div>
            <div>
                <p class="tarjeta-encabezado">abono</p>
                <p class="tarjeta-encabezado">$ 777777</p>
            </div>
            <div>
                <p class="tarjeta-encabezado">Saldo</p>
                <p class="tarjeta-encabezado">$ 999999</p>
            </div>
            <div>
                <p class="tarjeta-encabezado">No. Factura</p>
                <p class="tarjeta-encabezado">0000</p>
            </div>
        </div>

        <p class="tarjeta-separador">
            ----------------------------------------------------------------------------</p>
    </div>



    {{-- --------------------POP UP------------------- --}}

    <div class="contenedor-pop-up hidden">
        <div class="cerrar-pop-up">
            <button class="boton-cerrar-pop-up ">
                <img src="{{ asset('img/iconoCerrar.png') }}" alt="">
            </button>
        </div>
        <div class="historias-contenedor historias-pop-up">

            <div class="historia-encabezado">
                <p>Nueva Factura</p>
            </div>
            <div class="cuerpo-historias">

                {{-- @foreach ($historias as $historia) --}}
                <form action="" method="post">
                    <div class="factura-linea">
                        <label for="">Nombre</label>
                        <input type="text" name="" id="">
                    </div>
                    <div class="factura-linea">
                        <label for="">Saldo</label>
                        <input type="number" placeholder="Déjelo en blanco si es solo abono" name="" id="">
                        <p><- SUMA a saldo pendiente</p>
                    </div>
                    <div class="factura-linea">
                        <label for="">Abono</label>
                        <input type="number" name="" id="">
                        <p><- RESTA a saldo pendiente</p>
                    </div>

                    <div class="factura-linea">
                        <label for="">Atendido por</label>
                        <select name="" id="">

                        </select>
                    </div>


                    <div class="factura-linea">
                        <label for="">Próxima cita</label>
                        <input type="datetime-local" name="" id="">
                    </div>

                    <button class="boton-guardar" type="button">
                        <p>Guardar / Imprimir</p>
                    </button>


                </form>
            </div>
        </div>
    </div>
</div>


@include('partials.footer')
