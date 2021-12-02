<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    protected Collection $countries;

    public function __construct(
        $count = null,
        ?Collection $states = null,
        ?Collection $has = null,
        ?Collection $for = null,
        ?Collection $afterMaking = null,
        ?Collection $afterCreating = null,
        $connection = null
    ) {
        $this->count         = $count;
        $this->states        = $states ?: new Collection();
        $this->has           = $has ?: new Collection();
        $this->for           = $for ?: new Collection();
        $this->afterMaking   = $afterMaking ?: new Collection();
        $this->afterCreating = $afterCreating ?: new Collection();
        $this->connection    = $connection;
        $this->faker         = $this->withFaker();
        $this->countries     = Country::pluck('id');
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'              => $this->faker->name(),
            'email'             => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token'    => Str::random(10),
            'created_at'        => $this->faker->dateTimeInInterval('-6 months', '+6 months'),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    public function optionalAbout()
    {
        return $this->state(function (array $attributes) {
            return [
                'about' => collect([null, $this->faker->sentence])->random(),
            ];
        });
    }

    public function randomCountry()
    {
        return $this->state(function (array $attributes) {
            return [
                'country_id' => $this->countries->random(),
            ];
        });
    }
}
