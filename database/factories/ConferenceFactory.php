<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ConferenceFactory extends Factory
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
            'date' => $this->faker->dateTime(),
            'ville' =>$this->faker->city(),
            'pays' =>$this->faker->country(),
            'conferenceable_id' => rand(1, 11),
            'conferenceable_type' => $this->faker->randomElement(['\App\Models\Chercheur', '\App\Models\Doctorant'])

        ];
    }
}
