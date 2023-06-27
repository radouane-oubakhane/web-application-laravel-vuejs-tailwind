<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    use HasFactory;

    protected $fillable = [
        'intitule',
        'detail'
    ];

    public function projets()
    {
        return $this->hasMany(Projet::class)->orderBy('created_at', 'DESC');;
    }
}
