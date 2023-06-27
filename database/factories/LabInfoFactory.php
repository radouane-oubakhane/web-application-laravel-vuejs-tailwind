<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LabInfoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nom' => 'Radouane & Hamza LAB',
            'detail' => 'la description du Laboratoire par defaut',
            'email' => 'contact@lab.com',
            'numero_telephone' => '+2126 66 66 66 66',
            'adresse' => 'Moulay Slimane Mghila BP: 592 Beni Mellal'
        ];
    }
}
