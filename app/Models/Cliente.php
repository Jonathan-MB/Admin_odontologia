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
        'tipo_documento_id'
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'fecha_cita' => 'datetime',
        'saldo' => 'decimal:2',
    ];


    public function eps(): BelongsTo
    {
        return $this->belongsTo(Eps::class, 'eps_id');
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


    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->primer_apellido} {$this->segundo_apellido}";
    }
}
