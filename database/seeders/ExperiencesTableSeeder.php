<?php

namespace Database\Seeders;

use App\Models\Experience;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExperiencesTableSeeder extends Seeder
{
    public function run(): void
    {
        Experience::factory()->count(50)->create();
    }
}
