<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Debt;
use App\Models\Notification;
use Illuminate\Http\Request;

class DebtViewController extends Controller
{
    public function index()
    {
        $debts = Debt::with('client')
            ->whereHas('client', fn($q) => $q->where('user_id', auth()->id()))
            ->latest()
            ->paginate(10);

        return view('debts.index', compact('debts'));
    }

    public function create()
    {
        $clients = Client::where('user_id', auth()->id())->get();
        return view('debts.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        $client = Client::where('user_id', auth()->id())->findOrFail($request->client_id);

        Debt::create([
            'client_id' => $client->id,
            'description' => $request->description,
            'amount' => $request->amount,
            'debt_date' => now(),
        ]);

        $totalDebt = $client->debts()->sum('amount');

        if ($totalDebt >= 10000) {
            Notification::create([
                'title' => 'تحذير: ديون مرتفعة',
                'body' => "العميل {$client->name} تجاوز حد الديون المسموح به",
                'client_id' => $client->id,
            ]);
        }

        return redirect()->route('debts.index')->with('success', 'تمت إضافة الدين بنجاح');
    }

    public function show($id)
    {
        $debt = Debt::with('client')
            ->whereHas('client', fn($q) => $q->where('user_id', auth()->id()))
            ->findOrFail($id);

        return view('debts.show', compact('debt'));
    }

    public function edit($id)
    {
        $debt = Debt::with('client')
            ->whereHas('client', fn($q) => $q->where('user_id', auth()->id()))
            ->findOrFail($id);

        $clients = Client::where('user_id', auth()->id())->get();
        return view('debts.edit', compact('debt', 'clients'));
    }

    public function update(Request $request, $id)
    {
        $debt = Debt::with('client')
            ->whereHas('client', fn($q) => $q->where('user_id', auth()->id()))
            ->findOrFail($id);

        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        $debt->update([
            'client_id' => $request->client_id,
            'description' => $request->description,
            'amount' => $request->amount,
        ]);

        return redirect()->route('debts.index')->with('success', 'تم تعديل بيانات الدين');
    }

    public function destroy($id)
    {
        $debt = Debt::with('client')
            ->whereHas('client', fn($q) => $q->where('user_id', auth()->id()))
            ->findOrFail($id);

        $debt->delete();

        return redirect()->route('debts.index')->with('success', 'تم حذف الدين');
    }
    public function byClient($client_id)
    {
        $debts = Debt::with('client')
            ->where('client_id', $client_id)
            ->latest()
            ->get();

        $client = $debts->first()?->client;


    if (!$client) {
        $client = \App\Models\Client::where('id', $client_id)
            ->where('user_id', auth()->id())
            ->first();
    }

    return view('debts.by-client', compact('debts', 'client'));
}

}
