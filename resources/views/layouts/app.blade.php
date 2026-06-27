<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name', 'Digital Store') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col">

    @include('layouts.navigation')

    {{-- FLASH MESSAGES --}}
    <div class="max-w-6xl mx-auto px-4 mt-4 w-full">
        @foreach(['success','error','info'] as $type)
            @if(session($type))
                <div class="mb-3 px-4 py-3 rounded-lg text-sm
                    {{ $type === 'success' ? 'bg-green-50 text-green-700 border border-green-200' : '' }}
                    {{ $type === 'error'   ? 'bg-red-50 text-red-700 border border-red-200'     : '' }}
                    {{ $type === 'info'    ? 'bg-blue-50 text-blue-700 border border-blue-200'  : '' }}">
                    {{ session($type) }}
                </div>
            @endif
        @endforeach
    </div>

    {{-- CONTENT: support @yield (section style) dan $slot (component style) --}}
    <main class="max-w-6xl mx-auto px-4 py-6 w-full flex-1">
        @hasSection('content')
            @yield('content')
        @else
            {{ $slot ?? '' }}
        @endif
    </main>

    <footer class="bg-white border-t border-gray-100 mt-auto py-6 text-center text-sm text-gray-400">
        © {{ date('Y') }} DigitalStore. All rights reserved.
    </footer>

</body>
</html>
