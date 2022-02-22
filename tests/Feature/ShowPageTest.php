<?php

use App\Http\Livewire\Show;
use App\Models\Post;

use function Pest\Livewire\livewire;

it('can display posts on show page', function () {
    $post = Post::factory()->published()->create(); 

    livewire(Show::class, ['post' => $post])
        ->assertSee($post->title);
});