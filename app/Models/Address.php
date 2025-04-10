<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    //
    protected $fillable = [
        'street', 'number', 'neighborhood', 'complement', 'cep', 'latitude', 'longitude', 'city_id',
    ];
    
    /**
     * Relacionamento com a cidade
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
