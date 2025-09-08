<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CarsModel;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CarsModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = CarsModel::class;
    
    public function definition(): array
    {
        return [
            'brand' => $this->faker->company(),
            'model' => $this->faker->word(),
            'year' => $this->faker->year(),
            'price_per_day' =>  $this->faker->randomFloat(2, 1000, 5000),
            'image' => null,
            'status' => $this->faker->randomElement(['available']),
        ];
    }
}
