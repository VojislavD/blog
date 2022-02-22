<?php

use App\Enums\CommentStatus;
use App\Http\Livewire\Show;
use App\Models\Comment;
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

it('can display approved comments on show page', function () {
    $post = Post::factory()->published()->create();
    
    $pendingComment = Comment::factory()->pending()->for($post)->create();
    $rejectedComment = Comment::factory()->rejected()->for($post)->create();
    $approvedComment = Comment::factory()->approved()->for($post)->create();

    livewire(Show::class, ['post' => $post])
        ->assertSee([
            $post->title,
            $approvedComment->body,
            $approvedComment->name
        ])
        ->assertDontSee([
            $pendingComment->body,
            $pendingComment->name,
            $rejectedComment->body,
            $rejectedComment->name
        ]);
});

it('can validate form for adding a comment', function () {
    $post = Post::factory()->published()->create();
    
    $data = [
        'name' => '',
        'email' => '',
        'body' => ''
    ];

    livewire(Show::class, ['post' => $post])
        ->set('name', $data['name'])
        ->set('email', $data['email'])
        ->set('body', $data['body'])
        ->call('submit')
        ->assertHasErrors([
            'name' => 'required',
            'email' => 'required',
            'body' => 'required',
        ]);

    $data = [
        'name' => 'Greater than 255 characters. Greater than 255 characters. Greater than 255 characters. Greater than 255 characters. Greater than 255 characters. Greater than 255 characters. Greater than 255 characters. Greater than 255 characters. Greater than 255 characters.',
        'email' => 'Greater than 255 characters. Greater than 255 characters. Greater than 255 characters. Greater than 255 characters. Greater than 255 characters. Greater than 255 characters. Greater than 255 characters. Greater than 255 characters. Greater than 255 characters.',
        'body' => 'This is test body for comment'
    ];

    livewire(Show::class, ['post' => $post])
        ->set('name', $data['name'])
        ->set('email', $data['email'])
        ->set('body', $data['body'])
        ->call('submit')
        ->assertHasErrors([
            'name' => 'max',
            'email' => 'max',
        ]);
    
    $data = [
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'body' => 'This is test body for comment'
    ];

    livewire(Show::class, ['post' => $post])
        ->set('name', $data['name'])
        ->set('email', $data['email'])
        ->set('body', $data['body'])
        ->call('submit')
        ->assertHasNoErrors();
});

it('can leave a comment', function () {
    $post = Post::factory()->published()->create();

    $data = [
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'body' => 'This is test body for comment'
    ];

    livewire(Show::class, ['post' => $post])
        ->set('name', $data['name'])
        ->set('email', $data['email'])
        ->set('body', $data['body'])
        ->call('submit')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('comments', [
        'post_id' => $post->id,
        'name' => $data['name'],
        'email' => $data['email'],
        'body' => $data['body'],
        'status' => CommentStatus::Pending 
    ]);
});