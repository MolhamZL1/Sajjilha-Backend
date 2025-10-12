<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
//    public function index()
//    {
//        $clients = Client::where('user_id', auth()->id())->get();
//        return response_data($clients, __('messages.get_debts'));
//    }
public function search(Request $request)
{
    $request->validate([
        'q'     => 'nullable|string|max:100', // نص البحث
        'limit' => 'nullable|integer|min:1|max:50',
    ]);

    $userId = auth()->id();
    $q      = $request->q;
    $limit  = $request->limit ?? 10;

    $clients = Client::query()
        ->where('user_id', $userId)
        ->when($q, fn($qb) =>
            $qb->where('name', 'like', '%'.$q.'%')
               ->orWhere('phone', 'like', '%'.$q.'%')  // احذفها لو ما بدك تبحث برقم الهاتف
        )
        ->orderBy('name')
        ->select(['id', 'name'])   // فقط الحقول المطلوبة
        ->limit($limit)
        ->get();

    return response_data($clients, __('messages.get_clients'));
}


  public function index()
{
    $userId = auth()->id();

   $clients = Client::where('user_id', $userId)
    ->withSum('debts as total_debt', 'amount')
    ->withSum('payments as total_paid', 'amount')
    ->withMax('debts as last_debt_date', 'debt_date')
    ->withMax('payments as last_payment_date', 'payment_date')
    ->get()
    ->map(function ($c) {
        $c->total_debt = (float) ($c->total_debt ?? 0);
        $c->total_paid = (float) ($c->total_paid ?? 0);
        $c->remaining  = $c->total_debt - $c->total_paid;
        $c->last_transaction_date = collect([$c->last_debt_date, $c->last_payment_date])
            ->filter()
            ->max();

        // خبّي العلاقات
      return $c->makeHidden(['debts','payments','user_id','created_at','updated_at']);
    });

    return response_data($clients, __('messages.get_clients'));
}
    public function store(Request $request)
    {
        $data = Client::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'user_id' => auth()->id(),
        ]);

        return response_data($data, __('messages.client_created'));
    }

    public function show(string $id)
    {
        $client = Client::find($id);
        return response_data($client, 'بيانات العميل رقم: ' . $id);
    }

    public function update(Request $request, string $id)
    {
        $client = Client::find($id);

        $client->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'user_id' => auth()->id(),
        ]);

        return response_data($client, __('messages.client_updated'));
    }

    public function destroy($id)
    {
        Client::find($id)->delete();
        return response_data([], __('messages.client_deleted'));
    }
}


