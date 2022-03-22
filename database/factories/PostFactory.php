<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => random_int(1,5),
            'image' => $this->faker->sentence(1),
            'slug' => $this->faker->slug(),
            'caption' => $this->faker->sentence(6)
        ];
    }
}
