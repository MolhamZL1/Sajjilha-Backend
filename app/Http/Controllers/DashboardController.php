<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Debt;
use App\Models\Notification;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // الحصول على ID المستخدم المسجل حالياً
        $userId = Auth::id();

        // إجمالي الديون للمستخدم الحالي فقط
        $total_debts = Debt::whereHas('client', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })->sum('amount');

        // إجمالي التسديدات للمستخدم الحالي فقط
        $total_payments = Payment::whereHas('client', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })->sum('amount');

        $balance = $total_debts - $total_payments;

        // عملاء المستخدم الحالي فقط
        $clients = Client::where('user_id', $userId)->with(['debts', 'payments'])->get();
        $clients_count = $clients->count();

        // حساب العملاء المدينين للمستخدم الحالي
        $clients_in_debt = $clients->filter(function ($client) {
            $totalDebt = $client->debts->sum('amount');
            $totalPaid = $client->payments->sum('amount');
            return $totalDebt > $totalPaid;
        })->count();

        $clients_clear = $clients_count - $clients_in_debt;

        // آخر الديون والتسديدات للمستخدم الحالي
        $debts = Debt::whereHas('client', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })->with('client')->latest()->take(10)->get();

        $payments = Payment::whereHas('client', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })->with('client')->latest()->take(10)->get();

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
