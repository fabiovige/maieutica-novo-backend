<?php

namespace Database\Seeders;

use App\Models\Checklist;
use Illuminate\Database\Seeder;

class ChecklistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Checklist::factory()
            ->count(50)
            ->create();
    }
}
