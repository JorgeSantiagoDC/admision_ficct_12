<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    protected $table      = 'nota';
    protected $primaryKey = 'id_nota';
    public    $timestamps = false;

    protected $fillable = [
        'id_examen',
        'id_postulante',
        'calificacion',
    ];

    protected $casts = [
        'calificacion' => 'decimal:2',
    ];

    // Una nota pertenece a un examen
    public function examen()
    {
        return $this->belongsTo(Examen::class, 'id_examen', 'id_examen');
    }

    // Una nota pertenece a un postulante
    public function postulante()
    {
        return $this->belongsTo(Postulante::class, 'id_postulante', 'id_postulante');
    }
}