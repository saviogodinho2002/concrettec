<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhoneNumber extends Model
{
    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array<string>
     */
    protected $fillable = [
        'phone_number',
        'user_id',
    ];

    /**
     * Obter o usuário dono deste número de telefone
     * 
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
