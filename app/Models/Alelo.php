<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alelo extends Model
{
    use HasFactory;

    protected $fillable = [
        'animal_id',
        'marcador',
        'alelo1',
        'alelo2',
        'lab',
        'data',
    ];
}
