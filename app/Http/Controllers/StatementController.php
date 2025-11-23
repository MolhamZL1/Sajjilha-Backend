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
    $debts = Debt::where('client_id', $client_id)
        ->select('id', 'debt_date', 'amount', 'description', DB::raw("'debt' as type"),'created_at')
        ->get();

    $payments = Payment::where('client_id', $client_id)
        ->select('id', 'payment_date', 'amount', 'notes', DB::raw("'payment' as type"),'created_at')
        ->get();

    $merged = $debts
        ->concat($payments) // بدل merge
        ->sortByDesc(function ($item) {
            return $item->created_at;
        })
        ->values(); // لإعادة ترتيب الإندكسات من 0..n

    return response_data($merged, 'حركات العميل حسب التاريخ');
}


public function allTransactions(Request $request, string $category)
{
    $search   = $request->input('search');
    $fromDate = $request->input('from_date');
    $toDate   = $request->input('to_date');

    $userId = auth()->id();

    // الديون
    $debts = Debt::with('client')
        ->whereHas('client', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })
        ->select(
            'id',
            'client_id',
            'debt_date as date',
            'amount',
            'description',
            DB::raw("'debt' as type"),
            'created_at'
        );

    // الدفعات
    $payments = Payment::with('client')
        ->whereHas('client', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })
        ->select(
            'id',
            'client_id',
            'payment_date as date',
            'amount',
            'notes as description',
            DB::raw("'payment' as type"),
            'created_at'
        );

    // فلترة بالاسم
    if (!empty($search)) {
        $debts->whereHas('client', function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%");
        });

        $payments->whereHas('client', function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%");
        });
    }

    // فلترة من تاريخ
    if (!empty($fromDate)) {
        $debts->where('debt_date', '>=', $fromDate);
        $payments->where('payment_date', '>=', $fromDate);
    }

    // فلترة إلى تاريخ
    if (!empty($toDate)) {
        $debts->where('debt_date', '<=', $toDate);
        $payments->where('payment_date', '<=', $toDate);
    }

    // نجلب البيانات
    $debts    = $debts->get();
    $payments = $payments->get();

    // ندمجهم مع بعض
    $merged = $debts->concat($payments);

    // فلترة النوع إذا طلب المستخدم
    if ($category === 'debt') {
        $merged = $merged->where('type', 'debt');
    } elseif ($category === 'payment') {
        $merged = $merged->where('type', 'payment');
    }

    // نرتب حسب الأحدث
    $merged = $merged->sortByDesc('created_at')->values();

    return response_data($merged, 'كل الحركات بعد الفلاتر');
}


}
