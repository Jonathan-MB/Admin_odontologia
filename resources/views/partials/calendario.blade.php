@php
    $inicio = $mes->copy()->startOfMonth();
    $fin    = $mes->copy()->endOfMonth();

    // Lunes = 1 ... Domingo = 7
    $huecos = $inicio->dayOfWeekIso - 1;

    $rutaBase = fn($dia, $verMes) => route('clientes.citas', [
        'sedeId' => session('sede.id'),
        'fecha'  => $dia,
        'mes'    => $verMes,
    ]);
@endphp

<div class="calendario">

    <div class="calendario-encabezado">
        <a class="calendario-mover"
            href="{{ $rutaBase($fecha, $mes->copy()->subMonth()->format('Y-m')) }}">&lt;</a>

        <span class="calendario-mes">{{ $mes->translatedFormat('F Y') }}</span>

        <a class="calendario-mover"
            href="{{ $rutaBase($fecha, $mes->copy()->addMonth()->format('Y-m')) }}">&gt;</a>
    </div>

    <div class="calendario-dias">
        <span class="calendario-inicial">L</span>
        <span class="calendario-inicial">M</span>
        <span class="calendario-inicial">M</span>
        <span class="calendario-inicial">J</span>
        <span class="calendario-inicial">V</span>
        <span class="calendario-inicial">S</span>
        <span class="calendario-inicial">D</span>

        @for ($i = 0; $i < $huecos; $i++)
            <span class="calendario-hueco"></span>
        @endfor

        @for ($d = 1; $d <= $fin->day; $d++)
            @php
                $esteDia = $inicio->copy()->day($d);
                $clave   = $esteDia->toDateString();
                $cuantas = $citasPorDia[$clave] ?? 0;
            @endphp

            <a class="calendario-dia @if ($clave === $fecha) calendario-elegido @endif @if ($esteDia->isToday()) calendario-hoy @endif"
                href="{{ $rutaBase($clave, $mes->format('Y-m')) }}"
                @if ($cuantas) title="{{ $cuantas }} citas" @endif>
                {{ $d }}
                @if ($cuantas)
                    <span class="calendario-marca">{{ $cuantas }}</span>
                @endif
            </a>
        @endfor
    </div>
</div>
