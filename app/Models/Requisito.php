<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requisito extends Model
{
    protected $table      = 'requisito';
    protected $primaryKey = 'id_requisito';
    public    $timestamps = false;

    protected $fillable = [
        'id_postulante',
        'tipo_documento',
        'url_archivo',
        'estado_validacion',
    ];

    // Un requisito pertenece a un postulante
    public function postulante()
    {
        return $this->belongsTo(Postulante::class, 'id_postulante', 'id_postulante');
    }
}