<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trabajador extends Model
{
    use HasFactory;

    protected $table = 'trabajadores'; 

    protected $primaryKey = 'id_trabajador';   
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nombre',
        'id_cargo',
        'no_sabados_domingos',
        'max_5_turnos',
    ];

    public function cargo()
    {
        return $this->belongsTo(Cargo::class, 'id_cargo');
    }
}
