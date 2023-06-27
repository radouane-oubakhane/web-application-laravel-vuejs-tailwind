<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'detail',
        'chercheur_id'
    ];

    public function projets()
    {
        return $this->hasMany(Projet::class);
    }

    public function chercheurs()
    {
        return $this->hasMany(Chercheur::class);
    }

    public function chercheur()
    {
        return $this->belongsTo(Chercheur::class);
    }
}
