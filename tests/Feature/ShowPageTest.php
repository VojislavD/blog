<?php

use App\Http\Livewire\Show;
use App\Models\Post;

use function Pest\Livewire\livewire;

it('can render show page', function () {
    $this->get(route('posts.show', Post::factory()->published()->create()))
        ->assertSuccessful();
});

it('can display posts on show page', function () {
    $post = Post::factory()->published()->create(); 

    livewire(Show::class, ['post' => $post])
        ->assertSee($post->title);
});