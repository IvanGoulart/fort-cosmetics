<?php

namespace Database\Factories;

use App\Models\Cosmetic;
use Illuminate\Database\Eloquent\Factories\Factory;

class CosmeticFactory extends Factory
{
    protected $model = Cosmetic::class;

    public function definition(): array
    {
        return [
            'api_id' => $this->faker->uuid(),
            'name' => $this->faker->word(),
            'type' => $this->faker->randomElement(['outfit', 'backpack', 'pickaxe', 'bundle']),
            'rarity' => $this->faker->randomElement(['common', 'uncommon', 'rare', 'epic', 'legendary']),
            'image' => 'https://via.placeholder.com/300x300',
            'price' => $this->faker->numberBetween(100, 1500),
            'regular_price' => $this->faker->numberBetween(100, 1500),
            'is_new' => $this->faker->boolean(),
            'is_shop' => $this->faker->boolean(),
            'bundle_id' => null,
            'release_date' => now(),
        ];
    }
}
