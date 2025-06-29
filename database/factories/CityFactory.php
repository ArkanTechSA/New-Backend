<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\City>
 */
class CityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->city,
            'status' => $this->faker->boolean(85),
            'region_id' => Region::factory(),
            'country_id' => Country::factory(),
        ];
    }

    public function forRegion($regionId): static
    {
        return $this->state([
            'region_id' => $regionId,
        ]);
    }

    public function forCountry($countryId): static
    {
        return $this->state([
            'country_id' => $countryId,
        ]);
    }
}
