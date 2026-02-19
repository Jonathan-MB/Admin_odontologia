@include('partials.head')

<link rel="stylesheet" href="{{ asset('css/historias.css') }}">

<title>Nombre</title>

@include('partials.header')





<div class="contenedor-general">

    <H1 class="vista-titulo">Datos Cliente</H1>

    <div class="contenedor-agregar-cliente">
        <div class="linea-agregar-cliente">
            <div class="elemento-formulario">
                <p class="titulo-elemento">No. Historia</p>
                <p class="dato-elemento">{{ $cliente->numero_documento }}</p>
            </div>

            <div class="elemento-formulario">
                <p class="titulo-elemento-p">Tipo Documento</p>
                <p class="dato-cliente">{{ $cliente->tipoDocumento->nombre }}</p>


            </div>
            <div class="elemento-formulario">
                <p class="titulo-elemento-p">Numero Documento</p>
                <p class="dato-cliente">{{ $cliente->numero_documento }}</p>
            </div>



            <div class="elemento-formulario">
                <p class="titulo-elemento-p">Fecha Nacimiento</p>
                <p class="dato-cliente">
                    {{ \Carbon\Carbon::parse($cliente->fecha_nacimiento)->format('d/m/Y') }}
                </p>
            </div>

            <div class="elemento-formulario">
                <p class="titulo-elemento-p">Edad</p>
                <p id="dato-edad">88</p>
            </div>

        </div>

        <div class="linea-agregar-cliente">

            <div class="elemento-formulario">
                <p class="titulo-elemento-p">Eps</p>
                <p class="dato-cliente"> {{ $cliente->eps->nombre }}</p>
            </div>



            <div class="elemento-formulario">
                <p class="titulo-elemento-p">Nombre</p>
                <p class="dato-cliente"> {{ $cliente->nombre }}</p>
            </div>
            <div class="elemento-formulario">
                <p class="titulo-elemento-p">Primer Apellido</p>
                <p class="dato-cliente"> {{ $cliente->primer_apellido }}</p>
            </div>
            <div class="elemento-formulario">
                <p class="titulo-elemento-p">Segundo Apellido</p>
                <p class="dato-cliente"> {{ $cliente->segundo_apellido }}</p>
            </div>
        </div>

        <div class="linea-agregar-cliente">


            <div class="elemento-formulario">
                <p class="titulo-elemento-p">Direccion</p>
                <p class="dato-cliente"> {{ $cliente->direccion }}</p>
            </div>
            <div class="elemento-formulario">
                <p class="titulo-elemento-p">Correo</p>
                <p class="dato-cliente"> {{ $cliente->correo }}</p>
            </div>
            <div class="elemento-formulario">
                <p class="titulo-elemento-p">Celular</p>
                <p class="dato-cliente"> {{ $cliente->telefono }}</p>

            </div>

            <button class="boton-guardar" type="submit">
                <img src="{{ asset('img/logoAgregarUsuarioN.png') }}" alt="">
                <p>Editar</p>
            </button>


        </div>


        <a href="{{ route('facturacion') }}" class="linea-agregar-cliente linea-saldo">

            <p>Saldo: $ {{ number_format($cliente->saldo, 0, ',', '.') }}</p>
        </a>
        <form action="{{ route('clientes.update', $cliente->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="linea-agregar-cliente linea-cita" ">

                <p class="titulo-elemento-p">Proxima Cita:</p>

                <div class="cita-contenedor">
                    <input type="datetime-local"  name="fechaCita" value ="{{ $cliente->fecha_cita }}" id="">
                </div>
                <button class="boton-guardar" id="boton-agendar" value="" type="submit">
                    <p>Agendar</p>
                </button>


            </div>

        </form>
        </div>

    <h2 class="vista-titulo">Historial de evolucion</h2>

    <div class="historias-contenedor">



        <button class="boton-historial boton-nueva-historia" id="boton-pop-historia"> + Nueva Historia</button>
        <button class="boton-historial diente" data-diente="General">General</button>
        <button class="boton-historial diente" data-diente="Otros">Otros</button>


        <div class="veribular-grupo">


            <h3 class="tipo-diente">VESTIBULARES</h3>
            <div class="titulos-grupos">
                <p>Vestibular Arriba Derecha</p>
                <p>Vestibular Arriba Izquierda</p>
            </div>
            <div class="dientes-fila">
                <div class="dientes-grupo">
                    <button type="button" data-diente="18" class="diente">18</button>
                    <button type="button" data-diente="17" class="diente">17</button>
                    <button type="button" data-diente="16" class="diente">16</button>
                    <button type="button" data-diente="15" class="diente">15</button>
                    <button type="button" data-diente="14" class="diente">14</button>
                    <button type="button" data-diente="13" class="diente">13</button>
                    <button type="button" data-diente="12" class="diente">12</button>
                    <button type="button" data-diente="11" class="diente">11</button>
                </div>

                <div class="dientes-grupo">
                    <button type="button" data-diente="21" class="diente">21</button>
                    <button type="button" data-diente="22" class="diente">22</button>
                    <button type="button" data-diente="23" class="diente">23</button>
                    <button type="button" data-diente="24" class="diente">24</button>
                    <button type="button" data-diente="25" class="diente">25</button>
                    <button type="button" data-diente="26" class="diente">26</button>
                    <button type="button" data-diente="27" class="diente">27</button>
                    <button type="button" data-diente="28" class="diente">28</button>

                </div>
            </div>
            <div class="titulos-grupos">

                <p>Vestibular Abajo Derecha</p>
                <p>Vestibular Abajo Izquierda</p>
            </div>
            <div class="dientes-fila">
                <div class="dientes-grupo">
                    <button type="button" data-diente="48" class="diente">48</button>
                    <button type="button" data-diente="47" class="diente">47</button>
                    <button type="button" data-diente="46" class="diente">46</button>
                    <button type="button" data-diente="45" class="diente">45</button>
                    <button type="button" data-diente="44" class="diente">44</button>
                    <button type="button" data-diente="43" class="diente">43</button>
                    <button type="button" data-diente="42" class="diente">42</button>
                    <button type="button" data-diente="41" class="diente">41</button>

                </div>

                <div class="dientes-grupo">
                    <button type="button" data-diente="31" class="diente">31</button>
                    <button type="button" data-diente="32" class="diente">32</button>
                    <button type="button" data-diente="33" class="diente">33</button>
                    <button type="button" data-diente="34" class="diente">34</button>
                    <button type="button" data-diente="35" class="diente">35</button>
                    <button type="button" data-diente="36" class="diente">36</button>
                    <button type="button" data-diente="37" class="diente">37</button>
                    <button type="button" data-diente="38" class="diente">38</button>

                </div>
            </div>
        </div>


        <div class="ungular-grupo">
            <h3 class="tipo-diente">UNGUALES</h3>
            <div class="titulos-grupos">

                <p>Unguales Arriba Derecha</p>
                <p>Unguales Arriba Izquierda</p>
            </div>
            <div class="dientes-fila">
                <div class="dientes-grupo">
                    <button type="button" data-diente="51" class="diente">51</button>
                    <button type="button" data-diente="52" class="diente">52</button>
                    <button type="button" data-diente="53" class="diente">53</button>
                    <button type="button" data-diente="54" class="diente">54</button>
                    <button type="button" data-diente="55" class="diente">55</button>

                </div>

                <div class="dientes-grupo">
                    <button type="button" data-diente="61" class="diente">61</button>
                    <button type="button" data-diente="62" class="diente">62</button>
                    <button type="button" data-diente="63" class="diente">63</button>
                    <button type="button" data-diente="64" class="diente">64</button>
                    <button type="button" data-diente="65" class="diente">65</button>

                </div>
            </div>
            <div class="titulos-grupos">
                <p>Unguales Abajo Derecha</p>
                <p>Unguales Abajo Izquierda</p>
            </div>
            <div class="dientes-fila">
                <div class="dientes-grupo">
                    <button type="button" data-diente="81" class="diente">81</button>
                    <button type="button" data-diente="82" class="diente">82</button>
                    <button type="button" data-diente="83" class="diente">83</button>
                    <button type="button" data-diente="84" class="diente">84</button>
                    <button type="button" data-diente="85" class="diente">85</button>

                </div>

                <div class="dientes-grupo">
                    <button type="button" data-diente="71" class="diente">71</button>
                    <button type="button" data-diente="72" class="diente">72</button>
                    <button type="button" data-diente="73" class="diente">73</button>
                    <button type="button" data-diente="74" class="diente">74</button>
                    <button type="button" data-diente="75" class="diente">75</button>

                </div>
            </div>
        </div>
    </div>


    {{-- --------------------POP UP Historias------------------- --}}

    <div class="contenedor-pop-up hidden" id="pop-up-historias-diente">
        <div class="cerrar-pop-up">
            <button class="boton-cerrar-pop-up ">
                <img src="{{ asset('img/iconoCerrar.png') }}" alt="">
            </button>
        </div>
        <div class="historias-contenedor historias-pop-up">

            <div class="historia-encabezado">
                <p>Código Diente :</p>
                <p id="diente-Actual-nombre"></p>
            </div>
            <div class="cuerpo-historias-diente" id="cuerpo-historias">


                <template  id="template-historia">
                    <div class="historia-tarjeta">
                    <div>
                        <p class="tarjeta-encabezado fecha-tarjeta">fecha</p>
                    </div>
                    <div>
                        <p class="tarjeta-encabezado especialista-tarjeta"> especialista</p>
                        <p class="tarjeta-obsevacion obserbacion-tarjeta">obserbacion</p>
                    </div>

                    </div>
                </template>

                    


            </div>
        </div>
    </div>

{{-- --------------------POP UP Nueva Historia------------------- --}}

    <div class="contenedor-pop-up hidden" id="pop-up-historias-nuevas">
        <div class="cerrar-pop-up">
            <button class="boton-cerrar-pop-up ">
                <img src="{{ asset('img/iconoCerrar.png') }}" alt="">
            </button>
        </div>
        <div class="historias-contenedor historias-pop-up">

            <div class="historia-encabezado">
                <p>Agregar Historias</p>

            </div>
            <div class="cuerpo-historias-nuevas">
                <div class="contenedor-datos-historia">
                    <div class="datos-historia">

                        <label class="datos-historias-titulo" for="fecha" >Fecha</label>
                        <label class="datos-historias-titulo" for="especialistaId">Atendido por </label>
                    </div>
                    <div class="datos-historia">
                        <input type="date" name="fecha" id="" value="">
                        <select  name="especialistaId" id="">
                            <option value="" selected disabled>Seleccionar</option>
                            @foreach ($especialistas as $especialista )
                                <option value="{{$especialista->id}}">{{$especialista->nombre}}</option>
                            @endforeach
                            
                        </select>
                    </div>
                </div>

                <div class="agregar-historia-tarjeta">
                    <div class="datos-historia">
                        <label for="dienteId">Diente</label>
                        <label for="observacion">Observacion</label>
                    </div>
                    <div class="datos-historia">
                            <select class="diente-nueva-historia" name="dienteId" id="">
                                <option value="" selected disabled>  seleccionar   </option>
                                @foreach ( $dientes as $diente)
                                    <option value="{{$diente->id}}">{{$diente->nombre}}</option>
                                @endforeach
                            </select>
                        <textarea class="observaciones-nueva-historia" name="observacion" id=""></textarea>
                    </div>
                    
                </div>
                
                <button type="button" class="mas-historias">+</button>
            </div>
            <button type="button" id="boton-guardar-historias">Guardar</button>
        </div>
    </div>

</div>

<script>
    const historias = @json($cliente->historias);
    
    const dientes = @json($dientes);
    const especialistas = @json($especialistas);
    const clienteId = {{ $cliente->id }};
    console.log(especialistas);
</script>
<script src="{{ asset('js/nuevaTarjetaHistoria.js') }}"></script>
<script src="{{ asset('js/bulkHistorias.js') }}"></script>
<script src="{{ asset('js/historias.js') }}"></script>

@include('partials.footer')
