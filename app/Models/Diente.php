<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Diente extends Model
{

    protected $fillable = [
        'nombre',
        'grupo_id'
    ];

    protected $with = ['grupo'];


    public function grupo(): BelongsTo
    {
        return $this->belongsTo(Grupo::class, 'grupo_id');
    }


    public function historias(): HasMany
    {
        return $this->hasMany(Historia::class);
    }
}
