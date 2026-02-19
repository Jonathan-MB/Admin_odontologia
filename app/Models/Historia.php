<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Historia extends Model
{
    use HasFactory;
    protected $fillable = [
        'cliente_id',
        'especialista_id',
        'diente_id',
        'observacion',
        'fecha',


    ];

    public function diente(): BelongsTo
    {
        return $this->belongsTo(Diente::class, 'diente_id');
    }


    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }


    public function especialista(): BelongsTo
    {
        return $this->belongsTo(Especialista::class, 'especialista_id');
    }
}
