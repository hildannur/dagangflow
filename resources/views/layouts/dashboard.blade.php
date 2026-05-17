<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'DagangFlow Dashboard' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F8FAF7] text-slate-900">
    <div class="min-h-screen flex">

        @include('partials.sidebar')

        <main class="flex-1">
            <header class="bg-white border-b border-slate-200 px-6 lg:px-10 py-5 flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">
                        Selamat datang kembali, {{ auth()->user()->name ?? 'Owner' }}
                    </p>

                    <h2 class="text-2xl font-bold text-slate-900">
                        {{ $pageTitle ?? 'Dashboard' }}
                    </h2>
                </div>

                <div class="flex items-center gap-3">
                    @yield('actions')
                </div>
            </header>

            <section class="p-6 lg:p-10">
                @yield('content')
            </section>
        </main>

    </div>
</body>
</html>