<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cita extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'sede_id',
        'especialista_id',
        'fecha_hora',
        'estado',
        'observacion'
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
    ];


    /**
     * Cada vez que cambia una cita se recalcula clientes.fecha_cita,
     * que es el espejo que siguen leyendo facturación, historias y
     * la impresión de recibos.
     */
    protected static function booted(): void
    {
        $sincronizar = fn(Cita $cita) => $cita->cliente?->sincronizarProximaCita();

        static::created($sincronizar);
        static::updated($sincronizar);
        static::deleted($sincronizar);
    }


    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class, 'sede_id');
    }

    public function especialista(): BelongsTo
    {
        return $this->belongsTo(Especialista::class, 'especialista_id');
    }
}
