@extends('layouts.app')

@section('content')
<div class="grid gap-6">

    <section class="flex flex-col gap-4 rounded-[2rem] border border-white/10 bg-white/5 p-8 shadow-2xl shadow-black/20 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-cyan-300">Transazioni</p>
            <h1 class="mt-3 text-3xl font-semibold text-white sm:text-4xl">Le tue entrate e uscite</h1>
            <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-300">
                Aggiungi, modifica o rimuovi le transazioni per tenere sotto controllo il tuo budget mensile.
            </p>
        </div>

        <a href="{{ route('transactions.create') }}" class="inline-flex shrink-0 items-center justify-center rounded-2xl bg-cyan-400 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-cyan-300">
            + Nuova transazione
        </a>
    </section>

    @if(session('success'))
        <div class="rounded-2xl border border-emerald-400/30 bg-emerald-400/10 px-5 py-4 text-sm font-medium text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    <section class="grid gap-4 md:grid-cols-3">
        <div class="rounded-2xl border border-white/10 bg-slate-900/70 p-5">
            <p class="text-sm text-slate-400">Entrate</p>
            <p class="mt-2 text-xl font-semibold text-emerald-400">+ € {{ number_format($entrate, 2, ',', '.') }}</p>
        </div>
        <div class="rounded-2xl border border-white/10 bg-slate-900/70 p-5">
            <p class="text-sm text-slate-400">Uscite</p>
            <p class="mt-2 text-xl font-semibold text-rose-400">- € {{ number_format($uscite, 2, ',', '.') }}</p>
        </div>
        <div class="rounded-2xl border border-white/10 bg-slate-900/70 p-5">
            <p class="text-sm text-slate-400">Saldo</p>
            <p class="mt-2 text-xl font-semibold {{ $saldo >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                € {{ number_format($saldo, 2, ',', '.') }}
            </p>
        </div>
    </section>

    <section class="overflow-hidden rounded-[2rem] border border-white/10 bg-slate-900/70 shadow-2xl shadow-black/20">
        @if($transactions->isEmpty())
            <p class="p-8 text-center text-sm text-slate-400">
                Non hai ancora registrato nessuna transazione.
            </p>
        @else
            <table class="w-full text-left text-sm">
                <thead class="border-b border-white/10 text-xs uppercase tracking-wide text-slate-400">
                    <tr>
                        <th class="px-6 py-4">Descrizione</th>
                        <th class="px-6 py-4">Data</th>
                        <th class="px-6 py-4">Categoria</th>
                        <th class="px-6 py-4">Ricevuta</th>
                        <th class="px-6 py-4 text-right">Importo</th>
                        <th class="px-6 py-4 text-right">Azioni</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach($transactions as $transaction)
                        <tr class="hover:bg-white/5">
                            <td class="px-6 py-4 font-medium text-white">{{ $transaction->description }}</td>
                            <td class="px-6 py-4 text-slate-300">{{ $transaction->date->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">
                                <span class="rounded-full border border-cyan-300/20 bg-cyan-300/10 px-3 py-1 text-xs font-semibold text-cyan-200">
                                    {{ $transaction->category }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($transaction->receipt)
                                    <a href="{{ Storage::url($transaction->receipt) }}" target="_blank" class="text-cyan-300 hover:text-cyan-200 underline underline-offset-4">
                                        Vedi file
                                    </a>
                                @else
                                    <span class="text-slate-500">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right font-semibold {{ $transaction->isEntrata() ? 'text-emerald-400' : 'text-rose-400' }}">
                                {{ $transaction->isEntrata() ? '+' : '-' }} € {{ number_format((float) $transaction->amount, 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('transactions.edit', $transaction) }}" class="text-slate-300 hover:text-cyan-300">
                                        Modifica
                                    </a>
                                    <form method="POST" action="{{ route('transactions.destroy', $transaction) }}" onsubmit="return confirm('Confermi l\'eliminazione di questa transazione?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-400 hover:text-rose-300">
                                            Elimina
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </section>
</div>
@endsection
