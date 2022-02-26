<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'title' => $this->faker->sentence(),
            'slug' => $this->faker->slug(),
            'body' => $this->faker->realText(1500),
            'published' => $this->faker->boolean(),
            'published_at' => now(),
        ];
    }

    public function published(): Factory
    {
        return $this->state([
            'published' => true
        ]);
    }

    public function unpublished(): Factory
    {
        return $this->state([
            'published' => false
        ]);
    }
}
