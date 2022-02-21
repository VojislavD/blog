<?php

namespace Database\Factories;

use App\Enums\CommentStatus;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'status' => CommentStatus::Pending(),
            'post_id' => Post::factory(),
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'body' => $this->faker->text(240),
        ];
    }

    public function pending(): Factory
    {
        return $this->state([
            'status' => CommentStatus::Pending()
        ]);
    }

    public function approved(): Factory
    {
        return $this->state([
            'status' => CommentStatus::Approved()
        ]);
    }

    public function rejected(): Factory
    {
        return $this->state([
            'status' => CommentStatus::Rejected()
        ]);
    }
}
