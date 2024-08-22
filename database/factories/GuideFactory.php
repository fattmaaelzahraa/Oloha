<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class GuideFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'guiding_type' => $this->faker->word,
            'about' => $this->faker->paragraph,
            'price' => $this->faker->randomFloat(),
            'guide_time' => $this->faker->numberBetween(1,3),
            'languages' => json_encode($this->faker->words(3)),
            'interests' => json_encode($this->faker->words(3)),
            'activities' => json_encode($this->faker->words(3)),
            'guide_city' => $this->faker->city,
            'guide_photo' => $this->faker->imageUrl(640, 480, 'experience', true),

        ];
    }
}
