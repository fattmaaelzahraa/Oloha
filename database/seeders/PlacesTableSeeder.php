<?php

namespace Database\Seeders;

use App\Models\Place;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlacesTableSeeder extends Seeder
{

    public function run(): void
    {
        Place::factory()->count(50)->create();
    }
}
