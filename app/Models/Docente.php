<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    protected $table      = 'docente';
    protected $primaryKey = 'id_docente';
    public    $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'ci',
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'nivel_academico',
    ];

    // Un docente pertenece a un usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    // Un docente tiene muchos grupos
    public function grupos()
    {
        return $this->hasMany(Grupo::class, 'id_docente', 'id_docente');
    }
}