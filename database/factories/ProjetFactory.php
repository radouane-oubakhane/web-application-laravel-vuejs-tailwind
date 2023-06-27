<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProjetFactory extends Factory
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
            'photo' => '/path/abc',
            'theme_id' => rand(1, 10),
            'equipe_id' => rand(1, 10)
        ];
    }
}
