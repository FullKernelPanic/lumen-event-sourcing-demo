<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use Faker\Generator;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function __construct(private Generator $generator)
    {
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Order::fill([
            'user_id' => User::all()->random(1)->first()->getId(),
            'product_name' => $this->generator->shuffleString($this->generator->name()),
            'quantity' => $this->generator->numberBetween(0, 1000),
            'total_price' => $this->generator->randomFloat(2, 9, 99),
        ])->save();
    }
}
