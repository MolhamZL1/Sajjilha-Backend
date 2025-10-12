<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Debt;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatementViewController extends Controller
{
    public function allTransactions(Request $request)
    {
        $search = $request->input('search');
        $type = $request->input('type');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $userId = auth()->id();

        $debts = Debt::with('client')
            ->whereHas('client', fn($q) => $q->where('user_id', $userId))
            ->select('id', 'client_id', 'debt_date as date', 'amount', 'description', DB::raw("'debt' as type"));

        $payments = Payment::with('client')
            ->whereHas('client', fn($q) => $q->where('user_id', $userId))
            ->select('id', 'client_id', 'payment_date as date', 'amount', 'notes as description', DB::raw("'payment' as type"));

        if ($search) {
            $debts->whereHas('client', fn($q) => $q->where('name', 'like', "%$search%"));
            $payments->whereHas('client', fn($q) => $q->where('name', 'like', "%$search%"));
        }

        if ($fromDate) {
            $debts->where('debt_date', '>=', $fromDate);
            $payments->where('payment_date', '>=', $fromDate);
        }

        if ($toDate) {
            $debts->where('debt_date', '<=', $toDate);
            $payments->where('payment_date', '<=', $toDate);
        }

        $debts = $debts->get();
        $payments = $payments->get();

        $merged = $debts->merge($payments);

        if ($type == 'debt') {
            $merged = $merged->where('type', 'debt');
        } elseif ($type == 'payment') {
            $merged = $merged->where('type', 'payment');
        }

        $merged = $merged->sortByDesc('date')->values();

        return view('transactions.index', [
            'transactions' => $merged,
            'filters' => compact('search', 'type', 'fromDate', 'toDate')
        ]);
    }

    public function show($id)
    {
        $client = Client::with(['debts', 'payments'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        $total_debt = $client->debts->sum('amount');
        $total_paid = $client->payments->sum('amount');
        $remaining = $total_debt - $total_paid;

        return view('clients.statement', [
            'client' => $client,
            'total_debt' => $total_debt,
            'total_paid' => $total_paid,
            'remaining' => $remaining,
            'debts' => $client->debts,
            'payments' => $client->payments,
        ]);
    }


}
