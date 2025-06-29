<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Country;
use App\Models\Nationality;
use App\Models\Region;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;

        return [
            'first_name' => $firstName,
            'second_name' => $this->faker->firstName,
            'third_name' => $this->faker->firstName,
            'fourth_name' => $this->faker->lastName,
            'latest_name' => $lastName,
            'full_name' => "$firstName $lastName",

            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),

            'mobile_country_code' => '+'.rand(1, 999),
            'mobile_number' => $this->faker->unique()->phoneNumber(),
            'mobile_verified_at' => now(),

            'gender' => $this->faker->randomElement(['male', 'female']),
            'address' => $this->faker->address,

            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),

            'role' => $this->faker->randomElement([
                User::ROLE_LAWYER,
                User::ROLE_CLIENT,
                User::ROLE_ADMIN,
                User::ROLE_SUPERVISOR,
            ]),

            'photo' => $this->faker->imageUrl(200, 200, 'people'),
            'last_login' => now(),
            'ip' => $this->faker->ipv4,

            'lat' => $this->faker->latitude,
            'long' => $this->faker->longitude,

            'referral_code' => User::generateReferralCode(),
            'referred_by' => null,

            'nationality_id' => Nationality::factory(),
            'country' => Country::factory(),
            'region' => Region::factory(),
            'city' => City::factory(),

            'is_active' => $this->faker->randomElement([0, 1, 2]),

            // Additional fields
            'json1' => json_encode(['key' => 'value']),
            'column1' => $this->faker->word,
            'longtext1' => $this->faker->paragraph,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            // Set referred_by for some users
            if ($this->faker->boolean(30)) {
                $referrer = User::inRandomOrder()->first();
                $user->update(['referred_by' => $referrer->id]);
            }
        });
    }

    // States for specific roles
    public function lawyer(): static
    {
        return $this->state([
            'role' => User::ROLE_LAWYER,
        ]);
    }

    public function client(): static
    {
        return $this->state([
            'role' => User::ROLE_CLIENT,
        ]);
    }

    public function admin(): static
    {
        return $this->state([
            'role' => User::ROLE_ADMIN,
        ]);
    }

    public function supervisor(): static
    {
        return $this->state([
            'role' => User::ROLE_SUPERVISOR,
        ]);
    }

    // States for active statuses
    public function active(): static
    {
        return $this->state([
            'is_active' => 1,
        ]);
    }

    public function pending(): static
    {
        return $this->state([
            'is_active' => 0,
        ]);
    }

    public function banned(): static
    {
        return $this->state([
            'is_active' => 2,
        ]);
    }

    // State for incomplete profiles
    public function incompleteProfile(): static
    {
        return $this->state([
            'email' => null,
            'gender' => null,
            'country' => null,
        ]);
    }
}
