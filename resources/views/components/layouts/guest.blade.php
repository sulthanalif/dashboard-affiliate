<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Cropper.js --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" />
</head>
<body class="min-h-screen font-sans antialiased bg-white">
    <nav class="bg-white shadow">
        <div class="container mx-auto py-2 flex justify-between items-center">
            <a href="https://bervin.co.id/" class="flex items-center">
                <img src="{{ asset('img/logo-bervin.png') }}"  alt="Logo Bervin" style="width: 250px; height: auto;">
            </a>
        </div>
    </nav>
    {{-- MAIN --}}
    <x-main full-width>
        {{-- The `$slot` goes here --}}
        <x-slot:content>
            {{ $slot }}
        </x-slot:content>
    </x-main>

    {{--  TOAST area --}}
    <x-toast />
</body>
</html>
