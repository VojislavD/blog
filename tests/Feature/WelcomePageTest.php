<?php

use App\Http\Livewire\Welcome;
use App\Models\Post;

use function Pest\Livewire\livewire;

it('can display published posts on welcome page', function () {
    $publishedPost = Post::factory()->published()->create(); 
    $unpublishedPost = Post::factory()->unpublished()->create(); 

    livewire(Welcome::class)
        ->assertSee($publishedPost->title)
        ->assertDontSee($unpublishedPost->title);
});