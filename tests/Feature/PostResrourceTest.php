<?php

use App\Filament\Resources\PostResource;
use App\Models\Category;
use App\Models\Post;
use function Pest\Livewire\livewire;

it('can render list page', function () {
    $this->get(PostResource::getUrl())->assertSuccessful();
});

it('can render create page', function () {
    $this->get(PostResource::getUrl('create'))->assertSuccessful();
});

it('can render edit page', function () {
    $this->get(PostResource::getUrl('edit', [
        'record' => Post::factory()->create()
    ]))->assertSuccessful();
});

it('can display data on list page', function () {
    $post = Post::factory()->create();
 
    livewire(PostResource\Pages\ListPosts::class)
        ->assertSee([
            str($post->title)->limit(40),
            $post->category->name,
            $post->author,
            $post->published,
            $post->published_at,
        ]);
});

it('can retrieve data on edit page', function () {
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

it('can validate input', function () {
    $category = Category::factory()->create();

    $data = [
        'user_id' => '',
        'category_id' => '',
        'title' => '',
        'slug' => '',
        'body' => '',
        'featured_image' => null,
        'published' => 0
    ];

    livewire(PostResource\Pages\CreatePost::class)
        ->set('data.user_id', $data['user_id'])
        ->set('data.category_id', $data['category_id'])
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
        'category_id' => $category->id,
        'title' => 'Test Post',
        'slug' => 'test-post',
        'body' => 'Body length less than 120 characters.',
        'featured_image' => null,
        'published' => 0
    ];

    livewire(PostResource\Pages\CreatePost::class)
        ->set('data.user_id', $data['user_id'])
        ->set('data.category_id', $data['category_id'])
        ->set('data.title', $data['title'])
        ->set('data.slug', $data['slug'])
        ->set('data.body', $data['body'])
        ->set('data.featured_image', $data['featured_image'])
        ->set('data.published', $data['published'])
        ->call('create')
        ->assertHasErrors([
            'data.body' => 'min'
        ]);

    $data = [
        'user_id' => auth()->id(),
        'category_id' => $category->id,
        'title' => 'Test Post',
        'slug' => 'test-post',
        'body' => 'This is body of the test post and it should be more than 120 characters! This is body of the test post and it should be more than 120 characters!',
        'featured_image' => null,
        'published' => 0
    ];

    livewire(PostResource\Pages\CreatePost::class)
        ->set('data.user_id', $data['user_id'])
        ->set('data.category_id', $data['category_id'])
        ->set('data.title', $data['title'])
        ->set('data.slug', $data['slug'])
        ->set('data.body', $data['body'])
        ->set('data.featured_image', $data['featured_image'])
        ->set('data.published', $data['published'])
        ->call('create')
        ->assertHasNoErrors();
});

it('can update', function () {
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
        'category_id' => Category::factory()->create()->id,
        'title' => 'Test Post',
        'slug' => 'test-post',
        'body' => 'This is body of the test post and it should be more than 120 characters! This is body of the test post and it should be more than 120 characters!',
        'featured_image' => null,
        'published' => 0
    ];

    livewire(PostResource\Pages\CreatePost::class)
        ->set('data.user_id', $data['user_id'])
        ->set('data.category_id', $data['category_id'])
        ->set('data.title', $data['title'])
        ->set('data.slug', $data['slug'])
        ->set('data.body', $data['body'])
        ->set('data.featured_image', $data['featured_image'])
        ->set('data.published', $data['published'])
        ->call('create');
 
    $this->assertDatabaseHas(Post::class, [
        'user_id' => $data['user_id'],
        'category_id' => $data['category_id'],
        'title' => $data['title'],
        'slug' => $data['slug'],
        'body' => $data['body'],
        'featured_image' => $data['featured_image'],
        'published' => $data['published']
    ]);
});

it('can delete', function () {
    $post = Post::factory()->create();
 
    livewire(PostResource\Pages\EditPost::class, [
        'record' => $post->getKey(),
    ])
        ->call('delete');
    
    $this->assertDatabaseMissing('posts', [
        'id' => $post->id
    ]);
});