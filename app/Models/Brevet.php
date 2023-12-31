<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brevet extends Model
{
    use HasFactory;

    protected $fillable = [
        'intitule',
        'detail',
        'type',
        'fichier',
        'etat',
        'chercheur_id'

    ];

    public function chercheur()
    {
        return $this->belongsTo(Chercheur::class);
    }
}
