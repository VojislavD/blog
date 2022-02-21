<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class Welcome extends Component
{
    use WithPagination;
    
    public $amount = 8;

    public function loadMore()
    {
        $this->amount += 8;
    }

    public function render()
    {
        $posts = Post::published()
            ->latest()
            ->paginate($this->amount);

        return view('livewire.welcome', [
            'posts' => $posts
        ]);
    }
}
