<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Budget Personale') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
    <div class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_top,_rgba(56,189,248,0.14),_transparent_40%),radial-gradient(circle_at_bottom_right,_rgba(34,197,94,0.12),_transparent_35%),linear-gradient(180deg,_#020617,_#0f172a)]"></div>

    <header class="border-b border-white/10 bg-slate-950/70 backdrop-blur-xl">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
            <a href="{{ route('dashboard') }}" class="text-sm font-semibold tracking-[0.24em] text-cyan-300 uppercase">
                Budget Personale
            </a>

            @if(session('user_id'))
                <details class="relative">
                    <summary class="flex cursor-pointer list-none items-center gap-3 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-slate-100 shadow-lg shadow-slate-950/20">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-cyan-400/15 text-xs font-semibold text-cyan-200 ring-1 ring-inset ring-cyan-300/30">
                            {{ strtoupper(substr(session('user_name', 'U'), 0, 1)) }}
                        </span>
                        <span class="hidden sm:block">{{ session('user_name', 'Utente') }}</span>
                        <svg class="h-4 w-4 text-slate-300" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd" />
                        </svg>
                    </summary>

                    <div class="absolute right-0 mt-3 w-64 overflow-hidden rounded-2xl border border-white/10 bg-slate-900 shadow-2xl shadow-black/30">
                        <div class="border-b border-white/10 px-4 py-3">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Accesso attivo</p>
                            <p class="mt-1 text-sm font-medium text-white">{{ session('user_email') }}</p>
                        </div>

                        <a href="{{ route('dashboard') }}" class="block px-4 py-3 text-sm text-slate-200 transition hover:bg-white/5">
                            Dashboard
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full px-4 py-3 text-left text-sm text-rose-200 transition hover:bg-rose-500/10">
                                Logout
                            </button>
                        </form>
                    </div>
                </details>
            @endif
        </div>
    </header>

    <main class="mx-auto w-full max-w-6xl px-6 py-10">
        @if(session('status'))
            <div class="mb-6 rounded-2xl border border-emerald-400/20 bg-emerald-400/10 px-4 py-3 text-sm text-emerald-100">
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 rounded-2xl border border-rose-400/20 bg-rose-400/10 px-4 py-3 text-sm text-rose-100">
                <ul class="space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html><div>
    <!-- Simplicity is the essence of happiness. - Cedric Bledsoe -->
</div>
