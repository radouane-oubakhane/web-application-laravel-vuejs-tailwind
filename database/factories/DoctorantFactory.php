<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DoctorantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nom' => $this->faker->lastName(),
            'prenom' => $this->faker->firstName(),
            'email' =>$this->faker->unique()->email(),
            'numero_telephone' => $this->faker->unique()->phoneNumber(),
            'cin' => $this->faker->unique()->countryCode(),
            'cne' => $this->faker->unique()->countryCode(),
            'date_naissance' =>$this->faker->date(),
            'lieux_naissance' =>$this->faker->country(),
            'photo' => '/path/abc',
            'chercheur_id' => rand(1, 10),
            'sujet' => $this->faker->text()
        ];
    }
}
