<div class="w-3/4 md:w-2/3 mx-auto">
    
    <div class="flex items-center justify-center">
        <img class="w-full rounded-lg mt-12 z-10" src="{{ $post->displayFeaturedImage() }}">
    </div>

    <h1 class="text-3xl font-bold mt-8">{{ $post->title }}</h1>

    <p class="text-gray-500 text-sm mt-2">{{ $post->author }} | {{ $post->published_at }}</p>

    <div class="mt-8 text-lg">
        {!! $post->body !!}
    </div>
</div>
