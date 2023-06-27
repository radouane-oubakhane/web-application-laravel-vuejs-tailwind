<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctorant extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'numero_telephone',
        'cin',
        'cne',
        'date_naissance',
        'lieux_naissance',
        'photo',
        'chercheur_id',
        'sujet'
    ];

    public function user()
    {
        return $this->morphOne(User::class, 'userable');
    }

    public function encadrant()
    {
        return $this->belongsTo(Chercheur::class);
    }

    public function conferences()
    {
        return $this->morphMany(Conference::class, 'conferenceable')->orderBy('created_at', 'ASC');
    }

    public function rapports()
    {
        return $this->hasMany(Rapport::class);
    }
}
