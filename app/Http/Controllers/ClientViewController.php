<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientViewController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::with(['debts', 'payments'])->where('user_id', auth()->id());

        if ($request->filter === 'debtor') {
            // فقط العملاء الذين رصيدهم > 0
            $query->get()->filter(function ($client) {
                return $client->debts->sum('amount') - $client->payments->sum('amount') > 0;
            });
            // لكن الأفضل فلترة من قاعدة البيانات مباشرة، لكن بسبب العلاقات نحتاج للحل التالي:
            $clients = $query->get()->filter(function ($client) {
                return $client->debts->sum('amount') > $client->payments->sum('amount');
            })->values(); // لإعادة ترتيب المفاتيح

            return view('clients.index', ['clients' => $clients]);
        }

        $clients = $query->latest()->paginate(10);
        return view('clients.index', compact('clients'));
    }



    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        Client::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('clients.index')->with('success', 'تم إضافة العميل بنجاح');
    }

    public function show($id)
    {
        $client = Client::where('user_id', auth()->id())->findOrFail($id);
        return view('clients.show', compact('client'));
    }

    public function edit($id)
    {
        $client = Client::where('user_id', auth()->id())->findOrFail($id);
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $client = Client::where('user_id', auth()->id())->findOrFail($id);

        $client->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('clients.index')->with('success', 'تم تعديل بيانات العميل');
    }

    public function destroy($id)
    {
        $client = Client::where('user_id', auth()->id())->findOrFail($id);
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'تم حذف العميل');
    }
}
