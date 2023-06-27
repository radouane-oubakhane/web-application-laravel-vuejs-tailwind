<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EvenementFactory extends Factory
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
            'date_evenement' => $this->faker->date(),
            'photo' => '/path/abc'
        ];
    }
}
