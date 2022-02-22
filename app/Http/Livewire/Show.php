<?php

namespace App\Http\Livewire;

use App\Enums\CommentStatus;
use App\Models\Comment;
use App\Models\Post;
use Livewire\Component;

class Show extends Component
{
    public Post $post;
    public $name;
    public $email;
    public $body;

    protected $rules = [
        'name' => ['required', 'max:255'],
        'email' => ['required', 'email', 'max:255'],
        'body' => ['required']
    ];
    
    public function submit()
    {
        $this->validate();

        Comment::create([
            'status' => CommentStatus::Pending,
            'post_id' => $this->post->id,
            'name' => $this->name,
            'email' => $this->email,
            'body' => $this->body
        ]);

        $this->reset(['name', 'email', 'body']);
        session()->flash('CommentSent', 'Comment is sent. We will review it soon and approve if everything is ok.');
    }

    public function render()
    {
        $comments = $this->post->approvedComments;

        return view('livewire.show', [
            'comments' => $comments
        ]);
    }
}
