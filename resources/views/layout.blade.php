<!DOCTYPE html>
<html lang="{{ $locale = \Jegex\Koboi\Nova::resolveUserLocale(request()) }}" dir="{{ \Jegex\Koboi\Nova::rtlEnabled() ? 'rtl' : 'ltr' }}" class="h-full font-sans antialiased">
<head>
    <meta name="theme-color" content="#fff">
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width"/>
    <meta name="locale" content="{{ $locale }}"/>
    <meta name="robots" content="noindex">

    @include('nova::partials.meta')

    <!-- Styles -->
    @php
        $viteManifestPath = public_path('vendor/koboi/manifest.json');
    @endphp

    @if(file_exists($viteManifestPath))
        @vite(['resources/js/app.js', 'resources/js/ui.js'], 'vendor/koboi')
    @else
        <!-- Vite manifest placeholder: build not yet available -->
    @endif

    @if ($styles = \Jegex\Koboi\Nova::availableStyles(request()))
    <!-- Tool Styles -->
        @foreach($styles as $asset)
            <link rel="stylesheet" href="{!! $asset->url() !!}">
        @endforeach
    @endif

    <script>
        if (localStorage.novaTheme === 'dark' || (!('novaTheme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>
<body class="min-w-site text-sm font-medium min-h-full text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-900">
    @inertia

    @if(file_exists($viteManifestPath))
        <!-- Build Nova Instance -->
        <script>
            const config = @json(\Jegex\Koboi\Nova::jsonVariables(request()));
            window.Nova = createNovaApp(config)
            Nova.countdown()
        </script>

        @if ($scripts = \Jegex\Koboi\Nova::availableScripts(request()))
            <!-- Tool Scripts -->
            @foreach ($scripts as $asset)
                <script src="{!! $asset->url() !!}"></script>
            @endforeach
        @endif

        <!-- Start Nova -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Nova.liftOff()
            })
        </script>
    @else
        <!-- Vite manifest placeholder: Nova runtime will boot once assets are built -->
    @endif
</body>
</html>
