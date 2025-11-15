<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $fillable = ['client_id', 'description', 'debt_date', 'amount'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}

