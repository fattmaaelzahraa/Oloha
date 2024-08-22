<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{

    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->paragraph,
            'ticket_price' => $this->faker->randomFloat(),
            'organization' => $this->faker->company,
            'ending_time' => $this->faker->time,
            'event_date' => $this->faker->dateTime,
            'location' => $this->faker->sentence,
            'event_photo' => $this->faker->imageUrl(640, 480, 'experience', true),

        ];
    }
}
