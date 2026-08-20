@php
    $cliente  = $cita->cliente;
    $hora     = $cita->fecha_hora;
    $esPasada = ($pasada ?? false) || (($esHoy ?? false) && $hora->lt(now()));
    $esProxima = isset($proximaId) && $cita->id === $proximaId;
@endphp

<button type="button"
    class="btn-cliente @if ($esPasada) cita-pasada @endif @if ($esProxima) cita-proxima @endif"
    data-id="{{ $cliente->id }}" data-nombre="{{ $cliente->nombre_completo }}"
    data-fecha="{{ $cita->fecha_hora }}" data-telefono="{{ $cliente->telefono }}"
    data-documento="{{ $cliente->numero_documento }}" data-correo="{{ $cliente->correo }}"
    data-edad="{{ $cliente->fecha_nacimiento }}"
    data-url="{{ route('clientes.agendar', $cliente->id) }}"
    data-url-historia="{{ route('clientes.show', $cliente->id) }}"
    data-url-facturas="{{ route('facturaCliente', $cliente->id) }}">

    <span class="cita-hora">
        {{ $hora->format('h:i A') }}
        @if (!empty($mostrarFecha))
            <small class="cita-dia">{{ $hora->translatedFormat('d M') }}</small>
        @endif
    </span>

    <span class="cita-datos">
        <span class="cita-nombre">{{ $cliente->nombre_completo }}</span>
        <span class="cita-meta">{{ $cliente->numero_documento }} · {{ $cliente->telefono }}</span>
    </span>

    @if ($esProxima)
        <span class="cita-badge">proxima</span>
    @endif
</button>
