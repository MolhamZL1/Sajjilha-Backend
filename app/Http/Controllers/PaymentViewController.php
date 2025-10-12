<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Client;
use Illuminate\Http\Request;

class PaymentViewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        // جلب فقط المدفوعات المرتبطة بعملاء المستخدم الحالي
        $query = Payment::with('client')
            ->whereHas('client', function ($q) {
                $q->where('user_id', auth()->id());
            });

        // الفلاتر كما هي
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('payment_date', [$request->from_date, $request->to_date]);
        }

        if ($request->filled('min_amount')) {
            $query->where('amount', '>=', $request->min_amount);
        }

        if ($request->filled('max_amount')) {
            $query->where('amount', '<=', $request->max_amount);
        }

        $sortFields = [
            'amount_desc' => ['amount', 'desc'],
            'amount_asc' => ['amount', 'asc'],
            'date_desc' => ['payment_date', 'desc'],
            'date_asc' => ['payment_date', 'asc'],
        ];

        if ($request->filled('sort_by') && isset($sortFields[$request->sort_by])) {
            $query->orderBy(...$sortFields[$request->sort_by]);
        }

        $payments = $query->paginate(10);

        return view('payments.index', compact('payments'));
    }

    public function create()
    {
        // فقط العملاء التابعين للمستخدم الحالي
        $clients = Client::where('user_id', auth()->id())->get();
        return view('payments.create', compact('clients'));
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $data['payment_date'] = now();

        Payment::create($data);

        return redirect()->route('payments.index')->with('success', 'تم إضافة الدفعة بنجاح');
    }
    public function byClient($client_id)
    {
        $client = Client::where('id', $client_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $payments = Payment::with('client')
            ->where('client_id', $client_id)
            ->latest()
            ->paginate(10); // ✅ هذا التعديل المهم

        return view('payments.by-client', compact('payments', 'client'));
    }



}
