<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'enterprise_id',
        'password',
    ];

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

    /**
     * Obter a empresa associada ao usuário
     * 
     * @return BelongsTo
     */
    public function enterprise(): BelongsTo
    {
        return $this->belongsTo(Enterprise::class);
    }

    /**
     * Obter os números de telefone do usuário
     * 
     * @return HasMany
     */
    public function phoneNumbers(): HasMany
    {
        return $this->hasMany(PhoneNumber::class);
    }

    /**
     * Verifica se o usuário está associado a uma empresa
     * 
     * @return bool
     */
    public function hasEnterprise(): bool
    {
        return $this->enterprise_id !== null;
    }

    /**
     * Verifica se o usuário é um usuário master, gestor ou colaborador
     * (sem relação com empresa)
     * 
     * @return bool
     */
    public function isSystemUser(): bool
    {
        return $this->hasRole(['master', 'gestor', 'colaborador']);
    }
}
