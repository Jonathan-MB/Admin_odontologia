<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grupo extends Model
{
    protected $fillable = [
        'nombre'
    ];

    public function dientes(): HasMany
    {
        return $this->hasMany(Diente::class);
    }
}
