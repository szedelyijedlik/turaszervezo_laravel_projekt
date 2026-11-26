<?php

namespace Database\Seeders;

use App\Models\Tours;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ToursSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 15 db - következő 3–60 napban induló
        Tours::factory(15)->create();

        // 2 db - betelt
        Tours::factory(2)->create([
            "free_places" => 0
        ]);

        // 2 db - már elindult
        Tours::factory(2)->create([
            "starts_on" => now()->format("Y-m-d")
        ]);
    }
}
