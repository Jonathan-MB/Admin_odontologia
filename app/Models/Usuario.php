<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Usuario extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nombre',
        'correo',
        'contrasena',
        'rol_id',
    ];

    protected $hidden = [
        'contrasena',
        'remember_token',
    ];

    // Hashear contraseña automáticamente
    public function setContrasenaAttribute($value)
    {
        $this->attributes['contrasena'] = Hash::make($value);
    }


    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }
}
