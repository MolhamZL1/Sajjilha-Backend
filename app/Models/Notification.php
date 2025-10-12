<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'client_id', 'body'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // دالة مساعدة للتحقق من ملكية الإشعار للمستخدم
    public function belongsToUser($userId)
    {
        return $this->client && $this->client->user_id == $userId;
    }
}
