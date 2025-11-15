<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Debt;
use App\Models\Payment;

class TotalAccountViewController extends Controller
{
    public function index()
    {
        // إجمالي الديون والتسديدات والرصيد
        $total_debts = Debt::sum('amount');
        $total_payments = Payment::sum('amount');
        $balance = $total_debts - $total_payments;

        // حساب العملاء
        $clients = Client::where('user_id', auth()->id())->with(['debts', 'payments'])->get();
        $clients_count = $clients->count();

        // حساب العملاء المدينين (ديونهم أكبر من تسديداتهم)
        $clients_in_debt = $clients->filter(function ($client) {
            $totalDebt = $client->debts->sum('amount');
            $totalPaid = $client->payments->sum('amount');
            return $totalDebt > $totalPaid;
        })->count();

        // العملاء المسددين
        $clients_clear = $clients_count - $clients_in_debt;

        // آخر الديون والتسديدات
        $debts = Debt::with('client')->latest()->take(10)->get();
        $payments = Payment::with('client')->latest()->take(10)->get();

        return view('dashboard', compact(
            'total_debts',
            'total_payments',
            'balance',
            'debts',
            'payments',
            'clients_count',
            'clients_in_debt',
            'clients_clear'
        ));
    }



}

