<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Client;
use App\Models\Notification;
use Carbon\Carbon;

class CheckClientDebts extends Command
{
    protected $signature = 'clients:check-debts';
    protected $description = 'تحقق من ديون العملاء وإرسال إشعارات';

    public function handle()
    {
        $limit = 5000; // قيمة الحد المسموح به
        $days = 30;    // عدد الأيام دون تسديد

        $clients = Client::with(['debts', 'payments'])->get();

        foreach ($clients as $client) {
            $totalDebt = $client->debts->sum('amount');
            $totalPaid = $client->payments->sum('amount');
            $balance = $totalDebt - $totalPaid;

            // إشعار إذا تجاوز الحد
            if ($balance >= $limit) {
                $this->createNotificationIfNotExists($client, 'تنبيه: حد الدين تجاوز', "العميل {$client->name} تجاوز الحد المسموح به من الدين: {$balance} د.ل");
            }

            // إشعار إذا لم يدفع منذ مدة
            $lastPayment = $client->payments->sortByDesc('payment_date')->first();
            if (!$lastPayment || Carbon::parse($lastPayment->payment_date)->diffInDays(now()) >= $days) {
                $this->createNotificationIfNotExists($client, 'تنبيه: تأخر في الدفع', "العميل {$client->name} لم يقم بأي تسديد منذ أكثر من {$days} يوم.");
            }
        }

        $this->info('تم فحص العملاء وتوليد الإشعارات.');
    }

    private function createNotificationIfNotExists($client, $title, $body)
    {
        // تجنب تكرار الإشعار نفسه
        $exists = Notification::where('client_id', $client->id)
            ->where('title', $title)
            ->where('body', $body)
            ->whereDate('created_at', now()->toDateString())
            ->exists();

        if (!$exists) {
            Notification::create([
                'client_id' => $client->id,
                'title' => $title,
                'body' => $body,
            ]);
        }
    }
}
