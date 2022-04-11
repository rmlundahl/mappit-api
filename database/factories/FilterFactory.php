<?php

namespace Database\Factories;

use App\Models\Filter;
use Illuminate\Database\Eloquent\Factories\Factory;

class FilterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Filter::class;

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
            'parent_id' => null,
            'name' => $this->faker->name,
            'slug' => $this->faker->unique()->slug,
            'status_id' => 20,
        ];
    }
}
