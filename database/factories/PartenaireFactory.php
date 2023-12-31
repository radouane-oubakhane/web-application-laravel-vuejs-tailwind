<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PartenaireFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nom' => $this->faker->name(),
            'detail' => $this->faker->text(),
            'pays' => $this->faker->country(),
            'photo' => '/path/abc'
        ];
    }
}
