<section class="text-gray-600 body-font overflow-hidden">
    <h1 class="text-2xl text-center text-gray-900 font-bold">{{ __('Latest Posts') }}</h1>
    <div class="container px-5 py-24 mx-auto">
        @forelse ($posts as $post)
            <div class="-my-8 divide-y-2 divide-gray-100">
                <div class="py-8 flex flex-wrap md:flex-nowrap">
                    <div class="md:w-64 md:mb-0 mb-6 flex-shrink-0 flex flex-col">
                        <span class="font-semibold title-font text-gray-700">CATEGORY</span>
                        <span class="mt-1 text-gray-500 text-sm">{{ $post->published_at }}</span>
                    </div>
                    <div class="md:flex-grow">
                        <h2 class="text-2xl font-medium text-gray-900 title-font mb-2">{{ $post->title }}</h2>
                        <p class="leading-relaxed">{!! str($post->body)->limit(300) !!}</p>
                        <a href="{{ route('posts.show', $post->slug) }}" class="text-blue-600 inline-flex items-center mt-4">Read More
                            <svg class="w-4 h-4 ml-2" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="M12 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="flex items-center justify-center">
                <p>{{ __('There is no posts yet.') }}</p>
            </div>
        @endforelse
        
        @if(\App\Models\Post::published()->count() > $amount)
            <div class="mt-16 flex items-center justify-center">
                <button wire:click="loadMore" class="bg-blue-200 hover:bg-blue-300 text-sky-700 px-8 py-2 rounded-lg transition duration-200">{{ __('Load More') }}</button>
            </div>    
        @endif
    </div>
</section>