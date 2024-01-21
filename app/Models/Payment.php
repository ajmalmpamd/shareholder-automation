<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'shareholder_id',
        'due_date',
        'installment_amount',
        'payment_date',
        'paid_amount',
        'status',
    ];

    public function shareholder()
    {
        return $this->belongsTo(Shareholder::class);
    }

    
}
