<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chercheur extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'numero_telephone',
        'cin',
        'date_naissance',
        'lieux_naissance',
        'photo',
        'equipe_id'
    ];

    public function user()
    {
        return $this->morphOne(User::class, 'userable');
    }

    public function brevets()
    {
        return $this->hasMany(Brevet::class)->orderBy('created_at', 'DESC');
    }

    public function doctorants()
    {
        return $this->hasMany(Doctorant::class);
    }

    public function conferences()
    {
        return $this->morphMany(Conference::class, 'conferenceable')->orderBy('created_at', 'ASC');
    }

    public function equipe()
    {
        return $this->belongsTo(Equipe::class);
    }
}
