<?php


namespace App\Http\Controllers;

use App\Models\Debt;
use App\Models\Payment;

class TotalAccountController extends Controller
{
    public function index()
    {
       
        $total_debts = Debt::sum('amount');
        $total_payments = Payment::sum('amount');
        $balance = $total_debts - $total_payments;

        $debts = Debt::with('client')
            ->select('id', 'client_id', 'debt_date as date', 'amount', 'description', DB::raw("'debt' as type"))
            ->latest()
            ->take(3)
            ->get();

        $payments = Payment::with('client')
            ->select('id', 'client_id', 'payment_date as date', 'amount', 'notes as description', DB::raw("'payment' as type"))
            ->latest()
            ->take(3)
            ->get();
 // إحصائيات العملاء
    $total_clients = Client::where('user_id', $userId)->count();

    // العملاء المدينون: remaining > 0  (remaining = total_debt - total_paid)
    $indebted_clients = Client::where('user_id', $userId)
        ->withSum('debts as total_debt', 'amount')
        ->withSum('payments as total_paid', 'amount')
        ->havingRaw('(COALESCE(total_debt,0) - COALESCE(total_paid,0)) > 0')
        ->count();

    // غير المدينين: remaining <= 0
    $non_indebted_clients = Client::where('user_id', $userId)
        ->withSum('debts as total_debt', 'amount')
        ->withSum('payments as total_paid', 'amount')
        ->havingRaw('(COALESCE(total_debt,0) - COALESCE(total_paid,0)) <= 0')
        ->count();
    $data = [     
        'summary' => [
            'balance'        => (float) $balance,
            'clients' => [
                'total'        => (int) $total_clients,
                'indebted'     => (int) $indebted_clients,
                'non_indebted' => (int) $non_indebted_clients,
            ],
        ],
        'recent' => [
            'debts'    => $debts,
            'payments' => $payments,
        ],
    ];

        return response_data($data, __('messages.total_account_fetched'));
    }

}
