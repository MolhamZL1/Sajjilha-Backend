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

        $debts = Debt::with('client')->latest()->take(10)->get();
        $payments = Payment::with('client')->latest()->take(10)->get();

        return view('dashboard', compact('total_debts', 'total_payments', 'balance', 'debts', 'payments'));
    }

}
