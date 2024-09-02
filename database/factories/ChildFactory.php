<?php

namespace Database\Factories;

use App\Models\Child;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Child>
 */
class ChildFactory extends Factory
{
    protected $model = Child::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName,
            'parent_id' => User::factory()->create()->id,
        ];
    }
}
