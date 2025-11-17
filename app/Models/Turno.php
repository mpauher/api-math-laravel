<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    use HasFactory;

    protected $table = 'turnos';
    protected $primaryKey = 'id_turno';
    protected $fillable = ['nombre', 'hora_inicio', 'hora_fin'];

    public function disponibilidades()
    {
        return $this->hasMany(DisponibilidadSemanal::class, 'id_turno', 'id_turno');
    }

    public function asignaciones()
    {
        return $this->hasMany(Asignacion::class, 'id_turno', 'id_turno');
    }
}
