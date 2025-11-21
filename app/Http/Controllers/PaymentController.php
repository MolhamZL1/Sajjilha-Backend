<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $data['payment_date'] = now();

        $payment = Payment::create($data);

        return response_data($payment, __('messages.payment_added'));
    }

    public function index(Request $request)
    {
        $query = Payment::query();

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('payment_date', [$request->from_date, $request->to_date]);
        }

        if ($request->filled('min_amount')) {
            $query->where('amount', '>=', $request->min_amount);
        }

        if ($request->filled('max_amount')) {
            $query->where('amount', '<=', $request->max_amount);
        }

        $sortFields = [
            'amount_desc' => ['amount', 'desc'],
            'amount_asc' => ['amount', 'asc'],
            'date_desc' => ['payment_date', 'desc'],
            'date_asc' => ['payment_date', 'asc'],
        ];

        if ($request->filled('sort_by') && isset($sortFields[$request->sort_by])) {
            $query->orderBy(...$sortFields[$request->sort_by]);
        } elseif ($request->filled('order_by_amount')) {
            $query->orderBy('amount', $request->order_by_amount);
        } elseif ($request->filled('order_by_date')) {
            $query->orderBy('payment_date', $request->order_by_date);
        }

        $payments = $query->paginate(10);

        return response_data($payments, __('messages.payment_listed'));
    }

    public function byClient($client_id)
    {
        $payments = Payment::where('client_id', $client_id)
        ->orderBy('created_at', 'desc')
        ->get();
        return response_data($payments, __('messages.payment_listed'));
    }
}
