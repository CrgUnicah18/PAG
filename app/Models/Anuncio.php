<?php

// app/Models/Anuncio.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anuncio extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'contenido',
        'audiencia',
        'oficinas',
        'grupos',
        'fecha_hora',
        'prioridad',
        'activo',
        'fecha_expiracion',
    ];

    // Relación con oficinas
    public function oficinas()
    {
        return $this->belongsToMany(Oficina::class, 'anuncios_oficinas');
    }

    // Relación con grupos
    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'anuncios_grupos');
    }
    // En el modelo Anuncio (Anuncio.php)
    public function reactions()
    {
        return $this->hasMany(Reaccion::class, 'anuncio_id');
    }

}
