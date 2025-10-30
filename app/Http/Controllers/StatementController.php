<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Debt;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatementController extends Controller
{
    public function show($id)
    {
        $client = Client::with(['debts', 'payments'])->findOrFail($id);

        $total_debt = $client->debts->sum('amount');
        $total_paid = $client->payments->sum('amount');
        $remaining = $total_debt - $total_paid;

        $data = [
            'client' => [
                'id' => $client->id,
                'name' => $client->name,
                'phone' => $client->phone,
            ],
            'total_debt' => $total_debt,
            'total_paid' => $total_paid,
            'remaining' => $remaining,
            'debts' => $client->debts,
            'payments' => $client->payments,
        ];

        return response_data($data, __('messages.statement_fetched'));
    }


   public function merged($client_id)
{
    $debts = \App\Models\Debt::where('client_id', $client_id)
        ->select('id', 'debt_date', 'amount', 'description', DB::raw("'debt' as type"))
        ->get();

    $payments = \App\Models\Payment::where('client_id', $client_id)
        ->select('id', 'payment_date', 'amount', 'notes', DB::raw("'payment' as type"))
        ->get();

    $merged = $debts->merge($payments)->sortByDesc(function ($item) {
        return $item->debt_date ?? $item->payment_date;
    })->values();

    return response_data($merged, 'حركات العميل حسب التاريخ');
}




    public function allTransactions(Request $request,string $category)
    {
        $search = $request->input('search');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        $debts = Debt::with('client')
            ->select('id', 'client_id', 'debt_date as date', 'amount', 'description', DB::raw("'debt' as type"));

        $payments = Payment::with('client')
            ->select('id', 'client_id', 'payment_date as date', 'amount', 'notes as description', DB::raw("'payment' as type"));

        if ($search) {
            $debts->whereHas('client', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            });

            $payments->whereHas('client', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            });
        }

        if ($fromDate) {
            $debts->where('debt_date', '>=', $fromDate);
            $payments->where('payment_date', '>=', $fromDate);
        }

        if ($toDate) {
            $debts->where('debt_date', '<=', $toDate);
            $payments->where('payment_date', '<=', $toDate);
        }

        // نجلب البيانات
        $debts = $debts->get();
        $payments = $payments->get();

        // ندمجهم مع بعض
        $merged = $debts->merge($payments);

        // فلترة النوع اذا طلب المستخدم
        if ($category == 'debt') {
            $merged = $merged->where('type', 'debt');
        } elseif ($category == 'payment') {
            $merged = $merged->where('type', 'payment');
        }

        // نرتب حسب التاريخ الأحدث دائما
        $merged = $merged->sortByDesc('date')->values();

        return response_data($merged, 'كل الحركات بعد الفلاتر');
    }


}
