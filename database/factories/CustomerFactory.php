<?php

namespace CodeWithDiki\TransactionModule\Database\Factories;

use CodeWithDiki\TransactionModule\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition()
    {
        return [
            "name" => $this->faker->name(),
            "email" => $this->faker->unique()->safeEmail(),
        ];
    }
}
