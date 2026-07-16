<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" type="image/png" href="/images/logo.png">
        <link rel="apple-touch-icon" href="/images/logo.png">

        {{-- Installable PWA --}}
        <link rel="manifest" href="/manifest.webmanifest">
        <meta name="theme-color" content="#f7a8b5">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-title" content="MommyLovesMe">

        {{-- Realtime (Reverb): the browser's websocket endpoint, computed server-side
             so it works in dev (direct to Reverb) and prod (via the nginx /app proxy
             on the public domain) with no build-time env. Override with REVERB_CLIENT_*. --}}
        @php
            $reverbLocal = app()->environment('local');
            $reverbHost = env('REVERB_CLIENT_HOST') ?: ($reverbLocal ? env('REVERB_HOST', 'localhost') : request()->getHost());
            $reverbPort = env('REVERB_CLIENT_PORT') ?: ($reverbLocal ? (int) env('REVERB_PORT', 8080) : (request()->isSecure() ? 443 : 80));
            $reverbScheme = env('REVERB_CLIENT_SCHEME') ?: ($reverbLocal ? env('REVERB_SCHEME', 'http') : (request()->isSecure() ? 'https' : 'http'));
        @endphp
        <meta name="reverb-key" content="{{ config('broadcasting.connections.reverb.key') }}">
        <meta name="reverb-host" content="{{ $reverbHost }}">
        <meta name="reverb-port" content="{{ $reverbPort }}">
        <meta name="reverb-scheme" content="{{ $reverbScheme }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700&family=Quicksand:wght@400;500;600;700&family=Dancing+Script:wght@600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => navigator.serviceWorker.register('/sw.js').catch(() => {}));
            }
        </script>
    </body>
</html>
