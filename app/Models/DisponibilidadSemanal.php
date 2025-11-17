<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisponibilidadSemanal extends Model
{
    use HasFactory;

    protected $table = 'disponibilidad_semanal';
    protected $primaryKey = 'id_disponibilidad';
    protected $fillable = ['id_trabajador', 'id_turno'];

    public function trabajador()
    {
        return $this->belongsTo(Trabajador::class, 'id_trabajador', 'id_trabajador');
    }

    public function turno()
    {
        return $this->belongsTo(Turno::class, 'id_turno', 'id_turno');
    }
}
