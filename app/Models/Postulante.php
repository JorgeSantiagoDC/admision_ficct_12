<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Postulante extends Model
{
    protected $table      = 'postulante';
    protected $primaryKey = 'id_postulante';
    public    $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'ci',
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'fecha_nacimiento',
        'sexo',
        'direccion',
        'telefono',
        'correo',
        'colegio_procedencia',
        'ciudad',
        'id_carrera_opcion1',
        'id_carrera_opcion2',
        'promedio_final',
        'estado_admision',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'promedio_final'   => 'decimal:2',
    ];

    // Un postulante pertenece a un usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    // Primera opción de carrera
    public function carreraOpcion1()
    {
        return $this->belongsTo(Carrera::class, 'id_carrera_opcion1', 'id_carrera');
    }

    // Segunda opción de carrera
    public function carreraOpcion2()
    {
        return $this->belongsTo(Carrera::class, 'id_carrera_opcion2', 'id_carrera');
    }

    // Un postulante tiene muchos requisitos
    public function requisitos()
    {
        return $this->hasMany(Requisito::class, 'id_postulante', 'id_postulante');
    }

    // Un postulante tiene muchos pagos
    public function pagos()
    {
        return $this->hasMany(Pago::class, 'id_postulante', 'id_postulante');
    }

    // Un postulante pertenece a muchos grupos (tabla intermedia)
    public function grupos()
    {
        return $this->belongsToMany(
            Grupo::class,
            'inscripcion_grupo',
            'id_postulante',
            'id_grupo'
        );
    }

    // Un postulante tiene muchas notas
    public function notas()
    {
        return $this->hasMany(Nota::class, 'id_postulante', 'id_postulante');
    }
}