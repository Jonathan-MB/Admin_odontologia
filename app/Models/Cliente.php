<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'primer_apellido',
        'segundo_apellido',
        'numero_documento',
        'direccion',
        'correo',
        'telefono',
        'fecha_nacimiento',
        'fecha_cita',
        'saldo',
        'eps_id',
        'sede_id',
        'tipo_documento_id'
    ];

    protected $casts = [
        'fecha_nacimiento'  => 'date',
        'fecha_cita'        => 'datetime',
        'saldo'             => 'decimal:2',
    ];


    public function eps(): BelongsTo
    {
        return $this->belongsTo(Eps::class, 'eps_id');
    }
    
    public function sede(): BelongsTo
    {
        return $this->belongsTo(Eps::class, 'sede_id');
    }


    public function tipoDocumento(): BelongsTo
    {
        return $this->belongsTo(TipoDocumento::class, 'tipo_documento_id');
    }

    public function historias(): HasMany
    {
        return $this->hasMany(Historia::class);
    }

    public function facturas(): HasMany
    {
        return $this->hasMany(Factura::class);
    }

    public function citas(): HasMany
    {
        return $this->hasMany(Cita::class);
    }


    /**
     * Regla del negocio: un paciente tiene una sola cita pendiente a la vez.
     * Si ya existe una futura se MUEVE; solo si no hay se crea una nueva.
     * Asi reagendar no deja la cita vieja colgada en su dia original.
     */
    public function agendarProximaCita($fechaHora, $sedeId = null, $especialistaId = null): Cita
    {
        $datos = [
            'sede_id'         => $sedeId,
            'especialista_id' => $especialistaId,
            'fecha_hora'      => $fechaHora,
            'estado'          => 'agendada',
        ];

        $pendiente = $this->citaPendiente();

        if ($pendiente) {
            $pendiente->update($datos);

            return $pendiente;
        }

        return $this->citas()->create($datos);
    }


    /**
     * Marca la cita pendiente como cancelada. No se borra: queda el rastro
     * de que existio y de que no se cumplio.
     */
    public function cancelarProximaCita(): void
    {
        $this->citaPendiente()?->update(['estado' => 'cancelada']);
    }


    public function citaPendiente(): ?Cita
    {
        return $this->citas()
            ->where('estado', 'agendada')
            ->where('fecha_hora', '>=', now())
            ->orderBy('fecha_hora')
            ->first();
    }


    /**
     * Deja en clientes.fecha_cita la proxima cita agendada del paciente.
     * Esa columna sigue siendo la que leen facturacion, historias y la
     * impresion: aqui solo se mantiene al dia a partir de la tabla citas.
     */
    public function sincronizarProximaCita(): void
    {
        $proxima = $this->citas()
            ->where('estado', 'agendada')
            ->where('fecha_hora', '>=', now())
            ->orderBy('fecha_hora')
            ->first();

        $this->updateQuietly(['fecha_cita' => $proxima?->fecha_hora]);
    }


    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->primer_apellido} {$this->segundo_apellido}";
    }


    /**
     * Busca por nombre y apellidos.
     * Cada palabra escrita debe aparecer en alguno de los tres campos,
     * asi "juan perez" encuentra a Juan Perez sin importar el orden.
     */
    public function scopeBuscarPorNombre($query, string $busqueda)
    {
        $palabras = preg_split('/\s+/', trim($busqueda), -1, PREG_SPLIT_NO_EMPTY);

        foreach ($palabras as $palabra) {
            $query->where(function ($sub) use ($palabra) {
                $sub->where('nombre', 'like', "%{$palabra}%")
                    ->orWhere('primer_apellido', 'like', "%{$palabra}%")
                    ->orWhere('segundo_apellido', 'like', "%{$palabra}%");
            });
        }

        return $query
            ->orderBy('primer_apellido')
            ->orderBy('segundo_apellido')
            ->orderBy('nombre')
            ->limit(50);
    }
}
