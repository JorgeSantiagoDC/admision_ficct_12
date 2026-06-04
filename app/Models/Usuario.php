<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    protected $table      = 'usuario';
    protected $primaryKey = 'id_usuario';
    public    $timestamps = false;

    protected $fillable = [
        'id_rol',
        'usuario',
        'password',
        'activo',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // Un usuario pertenece a un rol
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }

    // Un usuario puede ser postulante
    public function postulante()
    {
        return $this->hasOne(Postulante::class, 'id_usuario', 'id_usuario');
    }

    // Un usuario puede ser docente
    public function docente()
    {
        return $this->hasOne(Docente::class, 'id_usuario', 'id_usuario');
    }

public function getAuthIdentifierName(): string
{
    return 'usuario';
}

}