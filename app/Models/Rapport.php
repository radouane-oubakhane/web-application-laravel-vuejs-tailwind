<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rapport extends Model
{
    use HasFactory;

    protected $fillable = [
        'intitule',
        'detail',
        'fichier',
        'etat',
        'doctorant_id'

    ];

    public function Doctorant()
    {
        return $this->belongsTo(Doctorant::class);
    }
}
