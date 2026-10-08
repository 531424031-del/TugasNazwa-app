<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $type = $request->query('type');
        $keyword = $request->query('q');

        if (! in_array($type, [Transaction::TYPE_INCOME, Transaction::TYPE_EXPENSE], true)) {
            $type = null;
        }

        $transactions = $user->transactions()
            ->when($type, fn ($query) => $query->where('type', $type))
            ->search($keyword)
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $totals = [
            'income' => (float) $user->transactions()->where('type', Transaction::TYPE_INCOME)->sum('amount'),
            'expense' => (float) $user->transactions()->where('type', Transaction::TYPE_EXPENSE)->sum('amount'),
        ];

        return view('transactions.index', [
            'transactions' => $transactions,
            'totals' => $totals,
            'type' => $type,
            'keyword' => $keyword,
        ]);
    }

    public function create(Request $request): View
    {
        $type = $request->query('type', Transaction::TYPE_EXPENSE);

        if (! in_array($type, [Transaction::TYPE_INCOME, Transaction::TYPE_EXPENSE], true)) {
            $type = Transaction::TYPE_EXPENSE;
        }

        return view('transactions.form', [
            'transaction' => null,
            'type' => $type,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        $request->user()->transactions()->create($data);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function show(Request $request, $transaction): View
    {
        return view('transactions.show', [
            'transaction' => $this->resolve($request, $transaction),
        ]);
    }

    public function edit(Request $request, $transaction): View
    {
        $transaction = $this->resolve($request, $transaction);

        return view('transactions.form', [
            'transaction' => $transaction,
            'type' => $transaction->type,
        ]);
    }

    public function update(Request $request, $transaction): RedirectResponse
    {
        $transaction = $this->resolve($request, $transaction);
        $transaction->update($this->validated($request, $transaction));

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Request $request, $transaction): RedirectResponse
    {
        $this->resolve($request, $transaction)->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil dihapus.');
    }

    /**
     * Load a transaction only from the authenticated user's own records.
     */
    private function resolve(Request $request, $transaction): Transaction
    {
        return $request->user()
            ->transactions()
            ->findOrFail((int) $transaction);
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Transaction $transaction = null): array
    {
        $type = $request->input('type', $transaction?->type);

        return $request->validate([
            'type' => ['required', Rule::in([Transaction::TYPE_INCOME, Transaction::TYPE_EXPENSE])],
            'category' => [
                'required',
                'string',
                'max:255',
                Rule::in(transaction_categories($type)),
            ],
            'description' => ['nullable', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:1', 'max:999999999999999'],
            'date' => ['required', 'date'],
        ]);
    }
}
