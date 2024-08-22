<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class ExperienceFactory extends Factory
{

    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'price' => $this->faker->randomFloat(),
            'experience' => json_encode($this->faker->words(3)),
            'type' => $this->faker->word,
            'experience_photo' => $this->faker->imageUrl(640, 480, 'experience', true),

        ];
    }
}
