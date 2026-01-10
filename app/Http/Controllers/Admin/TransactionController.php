<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $query = Transaction::with('user', 'items', 'address');

        if (request('search')) {
            $searchTerm = request('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('id', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('user', function($subQ) use ($searchTerm) {
                      $subQ->where('name', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        if (request('status')) {
            $query->where('status', request('status'));
        }

        $transactions = $query->latest()->paginate(20)->withQueryString();
        return view('admin.transactions.index', compact('transactions'));
    }

    public function create()
    {
        // Not needed for admin
    }

    public function store(Request $request)
    {
        // Not needed for admin
    }

    public function show(Transaction $transaction)
    {
        $transaction->load('user', 'items', 'address');
        return view('admin.transactions.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        // Not used, update status directly
    }

    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,paid,completed,cancelled',
        ]);

        $transaction->update($validated);
        return redirect()->route('admin.transactions.show', $transaction)
            ->with('success', 'Transaction status updated.');
    }

    public function destroy(Transaction $transaction)
    {
        // Don't delete transactions, just cancel them
        $transaction->update(['status' => 'cancelled']);
        foreach ($transaction->payments()->where('status', 'pending')->get() as $payment) {
            $payment->update(['status' => \App\Models\Payment::STATUS_CANCELLED, 'failure_reason' => 'Cancelled by admin']);
        }
        return redirect()->route('admin.transactions.index')
            ->with('success', 'Transaction cancelled.');
    }
}
