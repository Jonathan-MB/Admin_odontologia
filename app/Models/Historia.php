<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Historia extends Model
{
    protected $fillable = [
        'observacion',
        'diente_id',
        'cliente_id',
        'especialista_id'

    ];

    public function diente(): BelongsTo
    {
        return $this->belongsTo(Diente::class, 'diente_id');
    }


    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'diente_id');
    }


    public function especialista(): BelongsTo
    {
        return $this->belongsTo(Especialista::class, 'diente_id');
    }
}
