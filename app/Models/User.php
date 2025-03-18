<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use HasRoles; // Asegúrate de tener este trait en tu modelo

    // Permitir la asignación masiva de estos campos
    protected $fillable = [
        'empleado_id',
        'name',
        'email',
        'password',
    ];

    // Relación con Empleado
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
    public function getGeneroAttribute()
    {
        return $this->empleado->genero;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    // En el modelo User.php

    public function oficina()
    {
        return $this->belongsTo(Oficina::class);  // Un usuario tiene una oficina
    }

    public function grupos()
    {
        return $this->belongsToMany(Grupo::class);  // Un usuario puede pertenecer a varios grupos
    }


}
