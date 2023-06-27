<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RapportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'intitule' => $this->faker->name(),
            'detail' => $this->faker->text(),
            'fichier' => '/path/abc',
            'etat' => false,
            'doctorant_id' => rand(1, 10)
        ];
    }
}
