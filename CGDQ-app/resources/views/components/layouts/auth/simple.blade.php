<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white antialiased">
                <div class="flex flex-col gap-6s">
                    {{ $slot }}
                </div>
        @fluxScripts
    </body>
</html>
