<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->paragraph(),
            'quantity' => $this->faker->numberBetween(1, 10),
            'price' => $this->faker->numberBetween(10, 50),
        ];
    }
}
