<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Especialista extends Model
{
    protected $fillable = [
        'nombre',
        'sede_id'
    ];


    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class, 'sede_id');
    }

    public function historias(): HasMany
    {
        return $this->hasMany(Historia::class);
    }

    public function facturas(): HasMany
    {
        return $this->hasMany(Factura::class);
    }
}
