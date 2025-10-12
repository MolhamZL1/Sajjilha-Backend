<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use function Termwind\ValueObjects\p;

class Client extends Model
{
    use HasFactory;
    protected $fillable=['name','phone','address','is_favorite','amount','user_id'];

    public function user(){

        return $this->belongsTo(User::class);
    }
    public function debts()
    {
        return $this->hasMany(Debt::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

// لإجمالي ديونه
    public function getTotalDebtAttribute()
    {
        return $this->debts->sum('amount');
    }

// لإجمالي ما دفعه
    public function getTotalPaidAttribute()
    {
        return $this->payments->sum('amount');
    }

// المتبقي عليه
    public function getBalanceAttribute()
    {
        return $this->total_debt - $this->total_paid;
    }

    public function notifications()
    {
        return $this->hasMany(\App\Models\Notification::class);
    }


}
