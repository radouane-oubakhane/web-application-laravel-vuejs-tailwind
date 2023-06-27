<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BrevetFactory extends Factory
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
            'type' => $this->faker->mimeType(),
            'fichier' => '/path/abc',
            'etat' => false,
            'chercheur_id' => rand(1, 10)
        ];
    }
}
