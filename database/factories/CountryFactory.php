<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Country>
 */
class CountryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->countryCode,
            'name' => $this->faker->country,
            'phone_code' => '+'.$this->faker->numberBetween(1, 999),
            'status' => $this->faker->boolean(90),
        ];
    }
}
