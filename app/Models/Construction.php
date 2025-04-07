<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Construction extends Model
{
    protected $fillable = [
        'name', 'enterprise_id', 'address_id',
    ];
}
