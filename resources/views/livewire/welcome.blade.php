<div class="w-full min-h-screen">
    <div class="w-full h-10 flex items-center justify-end text-sm px-12">
        <a href="{{ route('login') }}" class="text-gray-600 underline hover:text-gray-800">
            @auth
                {{ __('Dashboard') }}
            @else
                {{ __('Log in') }}
            @endauth
        </a>
    </div>
    <div class="max-w-7xl mx-auto pb-16">
        <h1 class="text-center text-2xl font-bold">{{ __('Lastest Posts') }}</h1>
        @forelse ($posts as $post)
            <div class="grid grid-cols-4 gap-8 mt-12">
                <div class="h-[400px] border border-gray-300 shadow-xl rounded-lg overflow-hidden">
                    <img class="w-full h-[180px] rounded-tl-lg rounded-tr-lg" src="{{ $post->displayFeaturedImage() }}">
                    <div class="h-[220px] flex flex-col justify-between px-4 py-3">
                        <div>
                            <a href="#" class="inline-block text-xl font-bold mt-1 hover:underline">{{ str($post->title)->limit(40) }}</a>
                            <p class="mt-2">{!! str($post->body)->limit(100) !!}</p>
                        </div>
                        <div>
                            <a href="#" class="inline-block mt-4 px-2 py-1 rounded text-sm bg-blue-500 hover:bg-blue-700 text-gray-100 transition duration-150">{{ __('Read More') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="w-full text-center mt-12">{{ ('There is no posts yet.') }}</p>
        @endforelse
        @if($posts->count() > $amount)
            <div class="w-full flex items-center justify-center mt-16">
                <button wire:click="loadMore" class="bg-blue-200 hover:bg-blue-300 px-4 py-2 text-blue-900 font-bold text-sm rounded-lg transition duration-200">{{ __('Load More...')}}</button>
            </div>
        @endif
    </div>
</div>
