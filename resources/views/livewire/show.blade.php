<div class="w-3/4 md:w-2/3 mx-auto">
    
    <div class="flex items-center justify-center">
        <img class="w-full rounded-lg mt-12 z-10" src="{{ $post->displayFeaturedImage() }}">
    </div>

    <h1 class="text-3xl font-bold mt-8">{{ $post->title }}</h1>

    <p class="text-gray-500 text-sm mt-2">{{ $post->author }} | {{ $post->published_at }}</p>

    <div class="mt-8 text-lg">
        {!! $post->body !!}
    </div>

    <hr class="my-8">

    <h2 class="text-lg font-bold">{{ __('Comments') }}</h2>
    @forelse ($comments as $comment)
        <ul class="m-4 space-y-4">
            <li class="bg-gray-100 border border-gray-300 shadow p-4 rounded-lg flex items-end justify-between">
                <div class="flex-1 flex flex-col space-y-2">
                    <span class="text-gray-800">{{ $comment->body }}</span>
                    <span class="text-sm text-gray-600">{{ $comment->name }}</span>
                </div>
                <span class="text-sm text-gray-500 text-right">{{ $comment->created_at->shortRelativeDiffForHumans() }}</span>
            </li>
        </ul>
    @empty
        <p class="m-4">{{ __('There are no comments yet.') }}</p>
    @endforelse

    <form wire:submit.prevent="submit" class="mt-8 space-y-3">
        <h3>Leave Comment</h3>
        @if(session()->has('CommentSent'))
            <p class="text-green-600 font-bold text-center">{{ session('CommentSent') }}</p>
        @endif
        <div class="flex flex-col">
            <label for="name" class="text-sm">Name</label>
            <input wire:model="name" type="text" id="name" class="text-sm mt-2 py-1 border-gray-300 focus:outline-none focus:ring-0 focus:border-gray-300 @error('name') border border-red-600 @enderror" autocomplete="off" placeholder="Your Name">
            @error('name')
                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex flex-col">
            <label for="email" class="text-sm">Email</label>
            <input wire:model="email" type="email" id="email" class="text-sm mt-2 py-1 border-gray-300 focus:outline-none focus:ring-0 focus:border-gray-300 @error('email') border border-red-600 @enderror" autocomplete="off" placeholder="Your Email Address">
            @error('email')
                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex flex-col">
            <label for="textarea_default" class="text-sm">Comment</label>
            <textarea wire:model="body" id="textarea_default" class="text-sm mt-2 border-gray-300 focus:border-gray-300 focus:outline-none focus:ring-0 @error('body') border border-red-600 @enderror" placeholder="Leave comment here..."></textarea>
            @error('body')
                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit" class="text-sm bg-blue-500 hover:bg-blue-600 rounded-sm px-6 py-1.5 text-gray-100 hover:shadow-xl  transition duration-150">Send</button>
    </form>
</div>
