<?php

namespace Database\Seeders;

use App\Models\Guide;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GuidesTableSeeder extends Seeder
{
    public function run(): void
    {
        Guide::factory()->count(50)->create();

    }
}
