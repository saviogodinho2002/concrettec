<?php

namespace App\Models;

use App\Enums\EnterpriseTypes;
use Illuminate\Database\Eloquent\Model;

class Enterprise extends Model
{
    protected $fillable = [
        'name', 'fantasy_name', 'cnpj', 'email', 'price_cp', 'type',
    ];

    protected $casts = [
        'type' => EnterpriseTypes::class
    ];

    public function isConstruction(): bool
    {
        return $this->type === EnterpriseTypes::CONSTRUCTION;
    }
}
