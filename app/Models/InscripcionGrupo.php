<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InscripcionGrupo extends Model
{
    protected $table      = 'inscripcion_grupo';
    protected $primaryKey = 'id_inscripcion';
    public    $timestamps = false;

    protected $fillable = [
        'id_postulante',
        'id_grupo',
    ];

    // Pertenece a un postulante
    public function postulante()
    {
        return $this->belongsTo(Postulante::class, 'id_postulante', 'id_postulante');
    }

    // Pertenece a un grupo
    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'id_grupo', 'id_grupo');
    }
}