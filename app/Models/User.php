<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Vivero;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    // --- Y LUEGO AÑADE ESTA LÍNEA AQUÍ DENTRO ---
    use HasFactory, Notifiable, SoftDeletes;
    use HasApiTokens, HasFactory, Notifiable; 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombres',
        'primer_apellido',
        'segundo_apellido',
        'email',
        'password',
        'role_id',
        'estado',
    ];

    /**
     * Accesor para obtener el nombre completo del usuario.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->nombres} {$this->primer_apellido} {$this->segundo_apellido}";
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function role()
    {
        // Si ya tienes una relación de rol, déjala.
        return $this->belongsTo(Role::class);
    }

    // --- AGREGA ESTA FUNCIÓN ---
    /**
     * Los viveros que pertenecen al usuario.
     * The nurseries that belong to the user.
     */
    public function viveros() 
    {
        return $this->hasMany(Vivero::class);
    }
}
