<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class PlaceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'about' => $this->faker->paragraph,
            'opening_time' => $this->faker->time,
            'closing_time' => $this->faker->time,
            'waiting_time'=> $this->faker->randomDigit(),
            'type' => $this->faker->word,
            'capacity' => $this->faker->numberBetween(1,20),
            'good_for' => json_encode($this->faker->words(3)),
            'privileges' => json_encode($this->faker->words(3)),
            'place_photo' => $this->faker->imageUrl(640, 480, 'place', true),
            'vibes' => $this->faker->word,
            'location' => $this->faker->address,
            'created_at' => now(),
            'updated_at' => now(),
//            'remember_token' => Str::random(10),
        ];
    }
}
