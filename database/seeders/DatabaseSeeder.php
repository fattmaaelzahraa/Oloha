<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
//        $this->call(PlacesTableSeeder::class);
//        $this->call(ExperiencesTableSeeder::class);
//        $this->call(EventsTableSeeder::class);
        $this->call(GuidesTableSeeder::class);
    }
}
