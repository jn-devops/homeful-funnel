<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'product_code' => $this->faker->word(),
            'default_product' => $this->faker->word(),
            'minimum_salary' => $this->faker->numberBetween(10000,25000) * 1.00,
            'default_price' => $this->faker->numberBetween(8000000,2500000) * 1.00,
            'default_percent_down_payment' => $this->faker->numberBetween(0,10) / 100,
            'default_percent_miscellaneous_fees' => $this->faker->numberBetween(0,10) / 100,
            'default_down_payment_term' => $this->faker->numberBetween(0,24),
            'default_balance_payment_term' => $this->faker->numberBetween(0,30),
            'default_balance_payment_interest_rate' => $this->faker->numberBetween(3,8) / 100,
            'default_seller_commission_code' => $this->faker->word(),
            'kwyc_check_campaign_code' => $this->faker->word()
        ];
    }
}
