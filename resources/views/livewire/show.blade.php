<div>
    <h1 class="text-2xl font-bold mt-8">{{ $post->title }}</h1>

    <p class="text-gray-500 text-sm mt-2">{{ $post->author }} | {{ $post->published_at }}</p>

    <div class="flex items-center justify-center">
        <img class="mt-8 text-center" src="{{ $post->displayFeaturedImage() }}">
    </div>

    <div class="mt-8">
        {!! $post->body !!}
    </div>
</div>
