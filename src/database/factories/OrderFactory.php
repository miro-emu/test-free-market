<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Item;
use App\Models\Address;

class OrderFactory extends Factory
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
            'item_id' => Item::factory(),
            'payment_method' => $this->faker->numberBetween(1,2),
            'payment_status' => $this->faker->numberBetween(1,2),
            'payment_ref_id' => $this->faker->numberBetween(1,2),
            'address_id' => Address::factory(),
        ];
    }
}
