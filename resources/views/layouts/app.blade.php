<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        @livewireStyles
    </head>
    <body>
        <main class="w-full min-h-screen">
            <div class="absolute top-2 right-8 text-sm">
                <a href="{{ route('login') }}" class="text-gray-600 underline hover:text-gray-800">
                    @auth
                        {{ __('Dashboard') }}
                    @else
                        {{ __('Log in') }}
                    @endauth
                </a>
            </div>
            <div class="max-w-7xl mx-auto py-8">
                {{ $slot }}
            </div>
        </main>

        @livewireScripts
    </body>
</html>
