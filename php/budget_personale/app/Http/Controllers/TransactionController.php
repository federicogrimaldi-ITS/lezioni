<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Support\UserSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TransactionController extends Controller
{
    /**
     * Elenco delle transazioni dell'utente autenticato + riepilogo.
     */
    public function index(): View
    {
        $userId = UserSession::userId();

        $transactions = Transaction::forUser($userId)
            ->orderByDesc('date')
            ->get();

        $entrate = $transactions->where('category', 'Stipendio')->sum('amount');
        $uscite  = $transactions->where('category', '!=', 'Stipendio')->sum('amount');

        return view('transactions.index', [
            'transactions' => $transactions,
            'entrate'      => $entrate,
            'uscite'       => $uscite,
            'saldo'        => $entrate - $uscite,
        ]);
    }

    /**
     * Mostra il form per creare una nuova transazione.
     */
    public function create(): View
    {
        return view('transactions.form', [
            'transaction' => new Transaction(),
            'isEdit'      => false,
            'categories'  => Transaction::CATEGORIES,
        ]);
    }

    /**
     * Salva una nuova transazione.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);

        if ($request->hasFile('receipt')) {
            $data['receipt'] = $request->file('receipt')->store('receipts', 'public');
        }

        $data['user_id'] = UserSession::id();

        Transaction::create($data);

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transazione aggiunta con successo.');
    }

    /**
     * Mostra il form per modificare una transazione esistente.
     */
    public function edit(Transaction $transaction): View
    {
        $this->authorizeOwnership($transaction);

        return view('transactions.form', [
            'transaction' => $transaction,
            'isEdit'      => true,
            'categories'  => Transaction::CATEGORIES,
        ]);
    }

    /**
     * Aggiorna una transazione esistente.
     */
    public function update(Request $request, Transaction $transaction): RedirectResponse
    {
        $this->authorizeOwnership($transaction);

        $data = $this->validateData($request);

        if ($request->hasFile('receipt')) {
            if ($transaction->receipt) {
                Storage::disk('public')->delete($transaction->receipt);
            }
            $data['receipt'] = $request->file('receipt')->store('receipts', 'public');
        }

        $transaction->update($data);

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transazione aggiornata con successo.');
    }

    /**
     * Cancella una transazione.
     */
    public function destroy(Transaction $transaction): RedirectResponse
    {
        $this->authorizeOwnership($transaction);

        if ($transaction->receipt) {
            Storage::disk('public')->delete($transaction->receipt);
        }

        $transaction->delete();

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transazione eliminata.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'description' => ['required', 'string', 'max:255'],
            'date'        => ['required', 'date'],
            'amount'      => ['required', 'numeric', 'min:0.01'],
            'category'    => ['required', 'in:' . implode(',', Transaction::CATEGORIES)],
            'receipt'     => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);
    }

    private function authorizeOwnership(Transaction $transaction): void
    {
        abort_unless($transaction->user_id === UserSession::id(), 403);
    }
}