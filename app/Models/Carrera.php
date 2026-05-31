<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    protected $table      = 'carrera';
    protected $primaryKey = 'id_carrera';
    public    $timestamps = false;

    protected $fillable = [
        'nombre',
        'cupo_maximo',
    ];

    // Una carrera tiene muchos postulantes como opción 1
    public function postulantesOpcion1()
    {
        return $this->hasMany(Postulante::class, 'id_carrera_opcion1', 'id_carrera');
    }

    // Una carrera tiene muchos postulantes como opción 2
    public function postulantesOpcion2()
    {
        return $this->hasMany(Postulante::class, 'id_carrera_opcion2', 'id_carrera');
    }
}