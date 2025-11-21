<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use Illuminate\Http\Request;

class DebtController extends Controller
{
    public function index(Request $request)
    {
        $query = Debt::query();

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('debt_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('debt_date', '<=', $request->to_date);
        }

        if ($request->filled('min_amount')) {
            $query->where('amount', '>=', $request->min_amount);
        }

        if ($request->filled('max_amount')) {
            $query->where('amount', '<=', $request->max_amount);
        }

        if ($request->filled('description')) {
            $query->where('description', 'LIKE', "%{$request->description}%");
        }

        $sortFields = [
            'amount_desc' => ['amount', 'desc'],
            'amount_asc' => ['amount', 'asc'],
            'date_desc' => ['debt_date', 'desc'],
            'date_asc' => ['debt_date', 'asc'],
        ];

        if ($request->filled('sort_by') && isset($sortFields[$request->sort_by])) {
            $query->orderBy(...$sortFields[$request->sort_by]);
        } elseif ($request->filled('order_by_amount')) {
            $query->orderBy('amount', $request->order_by_amount);
        }

        $debts = $query->paginate(10);

        return response_data($debts, __('messages.get_debts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
        ]);

        $data['debt_date'] = now();

        $debt = Debt::create($data);

// التحقق من تجاوز حد المديونية
        // $total_debt = Debt::where('client_id', $data['client_id'])->sum('amount');
        // $debt_limit = 5000; // حد الدين مثلا 5000

        // if ($total_debt > $debt_limit) {
        //     \App\Models\Notification::create([
        //         'title' => 'تجاوز حد المديونية',
        //         'body' => "الزبون رقم {$data['client_id']} تجاوز حد المديونية. المجموع: {$total_debt}",
        //         'client_id' => $data['client_id'],
        //     ]);
        // }


        return response_data($debt, __('messages.debt_added'));
    }

    public function show($id)
    {
        $debt = Debt::findOrFail($id);
        return response_data($debt);
    }

    public function update(Request $request, $id)
    {
        $debt = Debt::findOrFail($id);
        $debt->update($request->only(['description', 'debt_date', 'amount']));

        return response_data($debt, __('messages.debt_updated'));
    }

    public function destroy($id)
    {
        Debt::findOrFail($id)->delete();

        return response_data([], __('messages.debt_deleted'));
    }
public function byClient($client_id)
{
    $debts = Debt::where('client_id', $client_id)
        ->orderBy('created_at', 'desc')
        ->get();

    return response_data($debts, __('messages.get_debts'));
}
}
