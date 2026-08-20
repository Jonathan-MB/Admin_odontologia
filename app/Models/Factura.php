<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Factura extends Model
{
    use HasFactory;
    protected $fillable = [
        'cliente_id',
        'especialista_id',
        'sede_id',
        'metodo_pago_id',
        'saldo',
        'abono',
        'nombre',
        'no_factura'
    ];


    protected $casts = [
        'saldo' => 'decimal:2',
        'abono' => 'decimal:2',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

        public function sede(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'sede_id');
    }

    public function especialista(): BelongsTo
    {
        return $this->belongsTo(Especialista::class, 'especialista_id');
    }

    public function metodoPago(): BelongsTo
    {
        return $this->belongsTo(MetodoPago::class, 'metodo_pago_id');
    }
}
