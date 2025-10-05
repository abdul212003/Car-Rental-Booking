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
            'color' => $this->faker->safeColorName(),
            'plate_number' => strtoupper($this->faker->bothify('???-####')),
            'transmission' => $this->faker->randomElement(['Automatic', 'Manual']),
            'setting_capacity' => $this->faker->numberBetween(2, 8) . ' seats',
            'fuel' => $this->faker->randomElement(['Gasoline', 'Diesel', 'Electric', 'Hybrid']),
            'year' => $this->faker->numberBetween(2000, now()->year),
            'price_per_day' => $this->faker->randomFloat(2, 1000, 5000),
            'image' => null,
            'interior_image' => null,
            'additional_image' => null,
            'status' => $this->faker->randomElement(['available', 'unavailable']),
        ];
    }
}
