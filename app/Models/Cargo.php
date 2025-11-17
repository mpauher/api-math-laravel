<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    use HasFactory;

    protected $table = 'cargos';
    protected $primaryKey = 'id_cargo';
    protected $fillable = ['nombre'];

    public function trabajadores()
    {
        return $this->hasMany(Trabajador::class, 'id_cargo', 'id_cargo');
    }
}
