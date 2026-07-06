@extends('layouts.app')

@section('content')
@php($isRegister = ($mode ?? 'login') === 'register')

<div class="grid gap-10 lg:grid-cols-2 lg:items-center">
    <section class="space-y-6">
        <div class="inline-flex rounded-full border border-cyan-300/20 bg-cyan-300/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-cyan-200">
            Budget Personale
        </div>

        <div class="space-y-4">
            <h1 class="max-w-xl text-4xl font-semibold tracking-tight text-white sm:text-5xl">
                {{ $isRegister ? 'Crea il tuo account e inizia a tracciare il budget' : 'Accedi per gestire entrate e uscite in un unico posto' }}
            </h1>

            <p class="max-w-xl text-base leading-7 text-slate-300">
                {{ $isRegister ? 'Registrazione rapida, sessione sicura e dashboard pronta per il monitoraggio finanziario.' : 'Login minimale con password verificata lato server e menu utente con logout rapido.' }}
            </p>
        </div>

        <div class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                <p class="text-sm text-slate-400">Sicurezza</p>
                <p class="mt-2 text-sm font-medium text-white">Password hashata</p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                <p class="text-sm text-slate-400">Sessione</p>
                <p class="mt-2 text-sm font-medium text-white">Guard protetto</p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                <p class="text-sm text-slate-400">UI</p>
                <p class="mt-2 text-sm font-medium text-white">Header con menu</p>
            </div>
        </div>
    </section>

    <section class="rounded-[2rem] border border-white/10 bg-slate-900/80 p-8 shadow-2xl shadow-black/30 backdrop-blur">
        <div class="mb-8">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-cyan-300">{{ $isRegister ? 'Registrazione' : 'Login' }}</p>
            <h2 class="mt-2 text-2xl font-semibold text-white">
                {{ $isRegister ? 'Crea un nuovo account' : 'Entra nel tuo account' }}
            </h2>
        </div>

        <form method="POST" action="{{ $isRegister ? route('register.store') : route('login.store') }}" class="space-y-5">
            @csrf

            @if($isRegister)
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-slate-300">Nome</span>
                    <input name="name" value="{{ old('name') }}" type="text" required class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none ring-0 placeholder:text-slate-500 focus:border-cyan-300/60" placeholder="Il tuo nome">
                </label>
            @endif

            <label class="block">
                <span class="mb-2 block text-sm font-medium text-slate-300">Email</span>
                <input name="email" value="{{ old('email') }}" type="email" required class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none ring-0 placeholder:text-slate-500 focus:border-cyan-300/60" placeholder="nome@esempio.it">
            </label>

            <label class="block">
                <span class="mb-2 block text-sm font-medium text-slate-300">Password</span>
                <input name="password" type="password" required class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none ring-0 placeholder:text-slate-500 focus:border-cyan-300/60" placeholder="••••••••">
            </label>

            <button type="submit" class="w-full rounded-2xl bg-cyan-400 px-4 py-3 text-sm font-semibold text-slate-950 transition hover:bg-cyan-300">
                {{ $isRegister ? 'Crea account' : 'Accedi' }}
            </button>
        </form>

        <div class="mt-6 border-t border-white/10 pt-6 text-sm text-slate-400">
            @if($isRegister)
                Hai già un account?
                <a href="{{ route('login.form') }}" class="font-semibold text-cyan-300 hover:text-cyan-200">Vai al login</a>
            @else
                Non hai ancora un account?
                <a href="{{ route('register.form') }}" class="font-semibold text-cyan-300 hover:text-cyan-200">Registrati</a>
            @endif
        </div>
    </section>
</div>
@endsection