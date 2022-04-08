<?php

namespace Database\Factories;

use App\Models\ItemProperty;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemPropertyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ItemProperty::class;

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
            'item_id' => $this->faker->randomNumber(),
            'key' => $this->faker->name,
            'value' => $this->faker->name,
            'status_id' => 20,
        ];
    }
}
