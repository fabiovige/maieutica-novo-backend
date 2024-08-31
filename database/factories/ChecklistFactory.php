<?php

namespace Database\Factories;

use App\Models\Checklist;
use App\Models\Child;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Checklist>
 */
class ChecklistFactory extends Factory
{
    protected $model = Checklist::class;

    public function definition(): array
    {
        return [
            'description' => $this->faker->sentence,
            'child_id' => Child::factory()->create()->id,
        ];
    }
}
