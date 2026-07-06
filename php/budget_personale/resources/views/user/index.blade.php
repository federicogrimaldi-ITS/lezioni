@extends('layouts.app')

@section('content')
<div class="grid gap-6">
    <section class="flex flex-col gap-6 rounded-[2rem] border border-white/10 bg-white/5 p-8 shadow-2xl shadow-black/20 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-cyan-300">Dashboard</p>
            <h1 class="mt-3 text-3xl font-semibold text-white sm:text-4xl">
                Benvenuto{{ $user['name'] ? ', ' . $user['name'] : '' }}
            </h1>
            <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-300">
                Sei autenticato e puoi usare il menu in alto a destra per uscire rapidamente.
                Da qui puoi gestire le tue transazioni e monitorare il bilancio mensile.
            </p>
        </div>

        <div class="flex shrink-0 flex-col gap-3 sm:flex-row">
            <a href="{{ route('transactions.create') }}" class="inline-flex items-center justify-center rounded-2xl bg-cyan-400 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-cyan-300">
                + Nuova transazione
            </a>
            <a href="{{ route('transactions.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-slate-100 transition hover:bg-white/10">
                Vedi tutte le transazioni
            </a>
        </div>
    </section>

    <section class="grid gap-4 md:grid-cols-3">
        <div class="rounded-2xl border border-white/10 bg-slate-900/70 p-5">
            <p class="text-sm text-slate-400">Nome</p>
            <p class="mt-2 text-base font-medium text-white">{{ $user['name'] ?? session('user_name') }}</p>
        </div>
        <div class="rounded-2xl border border-white/10 bg-slate-900/70 p-5">
            <p class="text-sm text-slate-400">Email</p>
            <p class="mt-2 text-base font-medium text-white">{{ $user['email'] ?? session('user_email') }}</p>
        </div>
        <div class="rounded-2xl border border-white/10 bg-slate-900/70 p-5">
            <p class="text-sm text-slate-400">Registrato il</p>
            <p class="mt-2 text-base font-medium text-white">{{ $user['created_at'] ?? 'N/D' }}</p>
        </div>
    </section>
</div>
@endsection