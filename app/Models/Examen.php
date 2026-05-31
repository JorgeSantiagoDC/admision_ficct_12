<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Examen extends Model
{
    protected $table      = 'examen';
    protected $primaryKey = 'id_examen';
    public    $timestamps = false;

    protected $fillable = [
        'id_grupo',
        'nombre_evaluacion',
        'porcentaje_ponderado',
    ];

    // Un examen pertenece a un grupo
    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'id_grupo', 'id_grupo');
    }

    // Un examen tiene muchas notas
    public function notas()
    {
        return $this->hasMany(Nota::class, 'id_examen', 'id_examen');
    }
}