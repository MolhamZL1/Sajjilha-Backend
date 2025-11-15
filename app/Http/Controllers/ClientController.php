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

public function index(string $category)
{
    $userId = auth()->id();
    $cat = strtolower($category);

    $clients = Client::where('user_id', $userId)

        ->withMax('debts as last_debt_date', 'debt_date')
        ->withMax('payments as last_payment_date', 'payment_date')
->withMin('debts as first_debt_date', 'debt_date') ->get()
        ->map(function ($c) {
            $c->total_debt = (float) ($c->total_debt ?? 0);
            $c->total_paid = (float) ($c->total_paid ?? 0);
            $c->remaining  = $c->total_debt - $c->total_paid;

            $c->last_transaction_date = collect([
                    $c->last_debt_date,
                    $c->last_payment_date
                ])->filter()->max();

            // خبّي العلاقات والحقول غير اللازمة
            return $c->makeHidden(['debts','payments','user_id','created_at','updated_at','amount']);
        }) ;
     // الشرط المطلوب حسب category
if ($cat === 'debt') {
    // اللي عليهم ديون (remaining > 0)
    $clients = $clients->filter(fn ($c) => ($c->remaining ?? 0) > 0)->values();

} else if ($cat === 'late') {
$cutoff = \Carbon\Carbon::now()->subDays(30)->startOfDay();
    $clients = $clients->filter(function ($c) use ($cutoff) {
        $remaining = (float) ($c->remaining ?? 0);
        if ($remaining <= 0) {
            return false; // ما عليه دين ⇒ مو متأخر
        }

      $lastPay   = $c->last_payment_date ? \Carbon\Carbon::parse($c->last_payment_date) : null;
        $firstDebt = $c->first_debt_date   ? \Carbon\Carbon::parse($c->first_debt_date)   : null;

        if ($lastPay) {
            // عنده دفعات: اعتبره متأخر إذا آخر دفعة أقدم من العتبة
            return $lastPay->lt($cutoff);
        }

        // ما عنده ولا دفعة: اعتمد أول تاريخ دين
        return $firstDebt && $firstDebt->lt($cutoff);
    })->values();} else if ($cat === 'clear') {
    // المسدّدين تماماً (remaining == 0)
    $clients = $clients->filter(fn ($c) => ($c->remaining ?? 0) == 0)->values();

} else if ($cat === 'all') {
    // الكل بدون فلترة
    // لا شيء: اترك $clients كما هي

} else {
    // أي قيمة غير معروفة
    $clients = collect(); // لو بدكها Array عادي: $clients = [];
}


    // ترتيب اختياري حسب آخر حركة (الأحدث أولاً)
    $clients = $clients->sortByDesc('last_transaction_date')->values();
    return response_data($clients, 'قائمة العملاء حسب التصنيف: ' . $cat);
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
        $remaining = $client->total_debt - $client->total_paid;
        $client->remaining = $remaining;
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


