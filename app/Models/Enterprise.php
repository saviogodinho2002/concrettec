<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enterprise extends Model
{
    protected $fillable = [
        'name', 'fantasy_name', 'cnpj', 'email', 'price_cp', 'type',
    ];
}
