<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'detail',
        'email',
        'numero_telephone',
        'adresse'
    ];
}
