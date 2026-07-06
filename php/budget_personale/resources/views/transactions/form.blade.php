@extends('layouts.app')

@section('content')

<div class="grid gap-10 lg:grid-cols-2 lg:items-center">
    <section class="space-y-6">
        <div class="inline-flex rounded-full border border-cyan-300/20 bg-cyan-300/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-cyan-200">
            Budget Personale
        </div>

        <div class="space-y-4">
            <h1 class="max-w-xl text-4xl font-semibold tracking-tight text-white sm:text-5xl">
                {{ $isEdit ? 'Aggiorna i dettagli della tua transazione' : 'Registra una nuova entrata o uscita' }}
            </h1>

            <p class="max-w-xl text-base leading-7 text-slate-300">
                {{ $isEdit ? 'Modifica importo, categoria o ricevuta: le modifiche vengono salvate subito.' : 'Aggiungi descrizione, importo, categoria ed eventuale ricevuta per tenere aggiornato il tuo budget.' }}
            </p>
        </div>

        <div class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                <p class="text-sm text-slate-400">Categorie</p>
                <p class="mt-2 text-sm font-medium text-white">Affitto, Stipendio...</p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                <p class="text-sm text-slate-400">Allegati</p>
                <p class="mt-2 text-sm font-medium text-white">Ricevuta PDF/JPG</p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                <p class="text-sm text-slate-400">Sicurezza</p>
                <p class="mt-2 text-sm font-medium text-white">Solo tue transazioni</p>
            </div>
        </div>
    </section>

    <section class="rounded-[2rem] border border-white/10 bg-slate-900/80 p-8 shadow-2xl shadow-black/30 backdrop-blur">
        <div class="mb-8">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-cyan-300">{{ $isEdit ? 'Modifica' : 'Nuova' }}</p>
            <h2 class="mt-2 text-2xl font-semibold text-white">
                {{ $isEdit ? 'Modifica transazione' : 'Crea una transazione' }}
            </h2>
        </div>

        @if($errors->any())
            <div class="mb-6 rounded-2xl border border-rose-400/30 bg-rose-400/10 px-5 py-4 text-sm text-rose-300">
                <ul class="list-inside list-disc space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST"
              action="{{ $isEdit ? route('transactions.update', $transaction) : route('transactions.store') }}"
              class="space-y-5"
              enctype="multipart/form-data">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <label class="block">
                <span class="mb-2 block text-sm font-medium text-slate-300">Descrizione</span>
                <input name="description" value="{{ old('description', $transaction->description) }}" type="text" required
                       class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none ring-0 placeholder:text-slate-500 focus:border-cyan-300/60"
                       placeholder="Es. Affitto Settembre">
            </label>

            <div class="grid gap-5 sm:grid-cols-2">
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-slate-300">Data</span>
                    <input name="date" value="{{ old('date', optional($transaction->date)->format('Y-m-d')) }}" type="date" required
                           class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none ring-0 focus:border-cyan-300/60">
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-slate-300">Importo (€)</span>
                    <input name="amount" value="{{ old('amount', $transaction->amount) }}" type="number" step="0.01" min="0.01" required
                           class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none ring-0 placeholder:text-slate-500 focus:border-cyan-300/60"
                           placeholder="0.00">
                </label>
            </div>

            <label class="block">
                <span class="mb-2 block text-sm font-medium text-slate-300">Categoria</span>
                <select name="category" required
                        class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none ring-0 focus:border-cyan-300/60">
                    <option value="" disabled {{ old('category', $transaction->category) ? '' : 'selected' }}>Seleziona una categoria</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ old('category', $transaction->category) === $category ? 'selected' : '' }}>
                            {{ $category }}
                        </option>
                    @endforeach
                </select>
            </label>

            <label class="block">
                <span class="mb-2 block text-sm font-medium text-slate-300">Ricevuta (opzionale)</span>
                <input name="receipt" type="file" accept=".jpg,.jpeg,.png,.pdf"
                       class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-300 outline-none ring-0 file:mr-4 file:rounded-full file:border-0 file:bg-cyan-400 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-slate-950 hover:file:bg-cyan-300">
                @if($isEdit && $transaction->receipt)
                    <span class="mt-2 block text-xs text-slate-400">
                        File attuale: <a href="{{ Storage::url($transaction->receipt) }}" target="_blank" class="text-cyan-300 underline underline-offset-4">visualizza</a>
                    </span>
                @endif
            </label>

            <button type="submit" class="w-full rounded-2xl bg-cyan-400 px-4 py-3 text-sm font-semibold text-slate-950 transition hover:bg-cyan-300">
                {{ $isEdit ? 'Salva modifiche' : 'Crea transazione' }}
            </button>
        </form>

        <div class="mt-6 border-t border-white/10 pt-6 text-sm text-slate-400">
            <a href="{{ route('transactions.index') }}" class="font-semibold text-cyan-300 hover:text-cyan-200">
                ← Torna all'elenco transazioni
            </a>
        </div>
    </section>
</div>
@endsection
