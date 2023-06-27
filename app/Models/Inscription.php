<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create()
 */
class Inscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'numero_telephone',
        'cin',
        'cne',
        'photo',
        'date_naissance',
        'lieux_naissance',
        'fonction_id'
    ];

    public function fonction()
    {
        return $this->belongsTo(Fonction::class);
    }
}
