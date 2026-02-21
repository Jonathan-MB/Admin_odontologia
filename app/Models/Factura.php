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


    public function especialista(): BelongsTo
    {
        return $this->belongsTo(Especialista::class, 'cliente_id');
    }
}
