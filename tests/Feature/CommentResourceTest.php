<?php

use App\Enums\CommentStatus;
use App\Filament\Resources\CommentResource;
use App\Models\Comment;

use function Pest\Livewire\livewire;

it('can render list page', function () {
    $this->get(CommentResource::getUrl())->assertSuccessful();
});

it('can render edit page', function () {
    $this->get(CommentResource::getUrl('edit', [
        'record' => Comment::factory()->create()
    ]))->assertSuccessful();
});

it('can display data on list page', function () {
    $comment = Comment::factory()->create();
 
    livewire(CommentResource\Pages\ListComments::class)
        ->assertSee([
            $comment->post->title,
            $comment->name,
            $comment->email,
            $comment->status->name()
        ]);
});

it('can retrieve data on edit page', function () {
    $comment = Comment::factory()->create();
 
    livewire(CommentResource\Pages\EditComment::class, [
        'record' => $comment->getKey(),
    ])
        ->assertSet('data.name', $comment->name)
        ->assertSet('data.email', $comment->email)
        ->assertSet('data.body', $comment->body);
});

it('can validate input', function () {
    $comment = Comment::factory()->create();

    $data = [
        'name' => '',
        'email' => '',
        'body' => '',
        'status' => ''
    ];
    
    livewire(CommentResource\Pages\EditComment::class, [
        'record' => $comment->getKey()
    ])
        ->set('data.name', $data['name'])
        ->set('data.email', $data['email'])
        ->set('data.body', $data['body'])
        ->set('data.status', $data['status'])
        ->call('save')
        ->assertHasErrors([
            'data.name' => 'required',
            'data.email' => 'required',
            'data.body' => 'required',
            'data.status' => 'required'
        ]);
    
    $data = [
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'body' => 'Greate post!',
        'status' => CommentStatus::Pending
    ];

    livewire(CommentResource\Pages\EditComment::class, [
        'record' => $comment->getKey()
    ])
        ->set('data.name', $data['name'])
        ->set('data.email', $data['email'])
        ->set('data.body', $data['body'])
        ->set('data.status', $data['status'])
        ->call('save')
        ->assertHasNoErrors();

});

it('can update', function () {
    $comment = Comment::factory()->create();
    $data = [
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'body' => 'Greate post!',
        'status' => CommentStatus::Pending
    ];

    livewire(CommentResource\Pages\EditComment::class, [
        'record' => $comment->getKey()
    ])
        ->set('data.name', $data['name'])
        ->set('data.email', $data['email'])
        ->set('data.body', $data['body'])
        ->set('data.status', $data['status'])
        ->call('save')
        ->assertHasNoErrors();

    expect($comment->refresh())
        ->name->toBe($data['name'])
        ->email->toBe($data['email'])
        ->body->toBe($data['body'])
        ->status->toBe($data['status']);
});

it('can delete', function () {
    $comment = Comment::factory()->create();
 
    livewire(CommentResource\Pages\EditComment::class, [
        'record' => $comment->getKey(),
    ])
        ->call('delete');
    
    $this->assertDatabaseMissing('comments', [
        'id' => $comment->id
    ]);
});