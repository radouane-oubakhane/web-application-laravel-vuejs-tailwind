<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fonction extends Model
{
    use HasFactory;

    protected $fillable = [
        'intitule'
    ];

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }
}
