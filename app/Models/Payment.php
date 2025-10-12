<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $fillable = [
        'client_id',
        'amount',
        'payment_date',
        'notes'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
