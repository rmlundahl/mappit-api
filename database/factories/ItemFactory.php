<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Item::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->unique()->randomNumber(),
            'language' => $this->faker->languageCode(),
            'external_id' => $this->faker->randomNumber(),
            'name' => $this->faker->name,
            'slug' => $this->faker->unique()->slug,
            'user_id' => $this->faker->randomNumber(),
            'status_id' => 20,
        ];
    }
}
