<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table      = 'pago';
    protected $primaryKey = 'id_pago';
    public    $timestamps = false;

    protected $fillable = [
        'id_postulante',
        'metodo',
        'monto',
        'fecha_pago',
        'estado_pago',
    ];

    protected $casts = [
        'fecha_pago' => 'datetime',
        'monto'      => 'decimal:2',
    ];

    // Un pago pertenece a un postulante
    public function postulante()
    {
        return $this->belongsTo(Postulante::class, 'id_postulante', 'id_postulante');
    }
}