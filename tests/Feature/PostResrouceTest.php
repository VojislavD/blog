<?php

use App\Filament\Resources\PostResource;
use App\Models\Post;
use function Pest\Livewire\livewire;

it('can render create page', function () {
    $this->get(PostResource::getUrl('create'))->assertSuccessful();
});

it('can render edit page', function () {
    $this->get(PostResource::getUrl('edit', [
        'record' => Post::factory()->create()
    ]))->assertSuccessful();
});

it('can validate input', function () {
    $data = [
        'user_id' => '',
        'title' => '',
        'slug' => '',
        'body' => '',
        'featured_image' => null,
        'published' => 0
    ];

    livewire(PostResource\Pages\CreatePost::class)
        ->set('data.user_id', $data['user_id'])
        ->set('data.title', $data['title'])
        ->set('data.slug', $data['slug'])
        ->set('data.body', $data['body'])
        ->set('data.featured_image', $data['featured_image'])
        ->set('data.published', $data['published'])
        ->call('create')
        ->assertHasErrors([
            'data.title' => 'required',
            'data.slug' => 'required',
            'data.body' => 'required'
        ]);

    $data = [
        'user_id' => auth()->id(),
        'title' => 'Test Post',
        'slug' => 'test-post',
        'body' => 'Body length less than 120 characters.',
        'featured_image' => null,
        'published' => 0
    ];

    livewire(PostResource\Pages\CreatePost::class)
        ->set('data.user_id', $data['user_id'])
        ->set('data.title', $data['title'])
        ->set('data.slug', $data['slug'])
        ->set('data.body', $data['body'])
        ->set('data.featured_image', $data['featured_image'])
        ->set('data.published', $data['published'])
        ->call('create')
        ->assertHasErrors([
            'data.body' => 'min'
        ]);
});

it('can retrieve data', function () {
    $post = Post::factory()->create();
 
    livewire(PostResource\Pages\EditPost::class, [
        'record' => $post->getKey(),
    ])
        ->assertSet('data.title', $post->title)
        ->assertSet('data.slug', $post->slug)
        ->assertSet('data.body', $post->body)
        ->assertSet('data.featured_image', [])
        ->assertSet('data.published', $post->published);
});

it('can save', function () {
    $post = Post::factory()->create();
    $newData = [
        'user_id' => auth()->id(),
        'title' => 'Test Post',
        'slug' => 'test-post',
        'body' => 'This is body of the test post and it should be more than 120 characters! This is body of the test post and it should be more than 120 characters!',
        'featured_image' => null,
        'published' => 0
    ];

    livewire(PostResource\Pages\EditPost::class, [
        'record' => $post->getKey(),
    ])
        ->set('data.title', $newData['title'])
        ->set('data.slug', $newData['slug'])
        ->set('data.body', $newData['body'])
        ->set('data.featured_image', $newData['featured_image'])
        ->set('data.published', $newData['published'])
        ->call('save');

    expect($post->refresh())
        ->user_id->toBe($post->user_id)
        ->title->toBe($newData['title'])
        ->slug->toBe($newData['slug'])
        ->body->toBe($newData['body'])
        ->featured_image->toBe($newData['featured_image'])
        ->published->toBe($newData['published']);
});

it('can create', function () {
    $data = [
        'user_id' => auth()->id(),
        'title' => 'Test Post',
        'slug' => 'test-post',
        'body' => 'This is body of the test post and it should be more than 120 characters! This is body of the test post and it should be more than 120 characters!',
        'featured_image' => null,
        'published' => 0
    ];

    livewire(PostResource\Pages\CreatePost::class)
        ->set('data.user_id', $data['user_id'])
        ->set('data.title', $data['title'])
        ->set('data.slug', $data['slug'])
        ->set('data.body', $data['body'])
        ->set('data.featured_image', $data['featured_image'])
        ->set('data.published', $data['published'])
        ->call('create');
 
    $this->assertDatabaseHas(Post::class, [
        'user_id' => $data['user_id'],
        'title' => $data['title'],
        'slug' => $data['slug'],
        'body' => $data['body'],
        'featured_image' => $data['featured_image'],
        'published' => $data['published']
    ]);
});