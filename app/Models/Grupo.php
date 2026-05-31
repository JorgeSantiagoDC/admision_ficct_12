<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    protected $table      = 'grupo';
    protected $primaryKey = 'id_grupo';
    public    $timestamps = false;

    protected $fillable = [
        'id_materia',
        'id_docente',
        'nombre_grupo',
        'capacidad_maxima',
        'gestion',
    ];

    // Un grupo pertenece a una materia
    public function materia()
    {
        return $this->belongsTo(Materia::class, 'id_materia', 'id_materia');
    }

    // Un grupo pertenece a un docente
    public function docente()
    {
        return $this->belongsTo(Docente::class, 'id_docente', 'id_docente');
    }

    // Un grupo tiene muchos postulantes (tabla intermedia)
    public function postulantes()
    {
        return $this->belongsToMany(
            Postulante::class,
            'inscripcion_grupo',
            'id_grupo',
            'id_postulante'
        );
    }

    // Un grupo tiene muchos exámenes
    public function examenes()
    {
        return $this->hasMany(Examen::class, 'id_grupo', 'id_grupo');
    }
}