<?php

declare(strict_types=1);


namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'product_name' => $this->faker->shuffleString,
            'quantity' => $this->faker->numberBetween(0, 1000),
            'total_price' => $this->faker->randomFloat(2, 9, 99),
        ];
    }
}
