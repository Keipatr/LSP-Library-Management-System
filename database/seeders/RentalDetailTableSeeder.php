<?php

namespace Database\Seeders;

use App\Models\RentalDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RentalDetailTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RentalDetail::factory(10)->create();
    }
}
