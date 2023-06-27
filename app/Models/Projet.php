<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projet extends Model
{
    use HasFactory;

    protected $fillable = [
        'intitule',
        'detail',
        'photo',
        'equipe_id',
        'theme_id'
    ];

    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    public function equipe()
    {
        return $this->belongsTo(Equipe::class);
    }
}
