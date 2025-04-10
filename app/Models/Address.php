<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    //
    protected $fillable = [
        'street', 'number', 'neighborhood', 'complement', 'cep', 'latitude', 'longitude', 'city_id',
    ];
}
