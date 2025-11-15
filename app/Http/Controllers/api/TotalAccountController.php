<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Debt;
use App\Models\Payment;
use App\Models\Client;

class TotalAccountController extends Controller
{
    public function total_account()
    {
        // الحصول على ID المستخدم المسجل
        $userId = auth()->id();

        // إجمالي الديون للمستخدم الحالي فقط
        $total_debts = Debt::whereHas('client', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })->sum('amount');

        // إجمالي التسديدات للمستخدم الحالي فقط
        $total_payments = Payment::whereHas('client', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })->sum('amount');

        $balance = $total_debts - $total_payments;

        // عدد العملاء للمستخدم الحالي
        $clients_count = Client::where('user_id', $userId)->count();

        // حساب العملاء المدينين
        $clients_in_debt = Client::where('user_id', $userId)
            ->with(['debts', 'payments'])
            ->get()
            ->filter(function ($client) {
                $totalDebt = $client->debts->sum('amount');
                $totalPaid = $client->payments->sum('amount');
                return $totalDebt > $totalPaid;
            })->count();

        // العملاء المسددين
        $clients_clear = $clients_count - $clients_in_debt;

        // آخر الديون والتسديدات للمستخدم الحالي
        $debts = Debt::whereHas('client', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })->with('client')->select(['id', 'client_id', 'amount'])
            ->addSelect(['date' => function ($query) {
                $query->selectRaw('DATE(debt_date) as date');
            }])
            ->addSelect(['type' => function ($query) {
                $query->selectRaw("'debt' as type");
            }])->latest()->take(3)->get();

        $payments = Payment::whereHas('client', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })->with('client')->select(['id', 'client_id', 'amount'])
            ->addSelect(['date' => function ($query) {
                $query->selectRaw('DATE(payment_date) as date');
            }])
            ->addSelect(['type' => function ($query) {
                $query->selectRaw("'payment' as type");
            }])->latest()->take(3)->get();

        $data = [
            'balance' => $balance,
            'clients_count' => $clients_count,
            'clients_in_debt' => $clients_in_debt,
            'clients_clear' => $clients_clear,
            'recent_debts' => $debts,
            'recent_payments' => $payments
        ];

        return response_data($data, __('messages.account_summary_retrieved'));
    }
}
