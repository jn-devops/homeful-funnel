<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Campaign>
 */
class CampaignFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'rider_url' => $this->faker->url(),
            'chat_url' => $this->faker->url(),
            'feedback' => $this->faker->sentence(),
            'avail_label' => $this->faker->word(),
            'trip_label' => $this->faker->word(),
            'undecided_label' => $this->faker->word(),
        ];
    }
}
