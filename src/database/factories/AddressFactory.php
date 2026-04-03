<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'postal_code' => $this->faker->numerify('###-####'),
            'address_line' => $this->faker->address(),
            'building' => $this->faker->word(),
            'type' => $this->faker->numberBetween(1,2),
        ];
    }
}
