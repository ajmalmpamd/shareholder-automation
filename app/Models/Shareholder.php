<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shareholder extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'mobile',
        'email',
        'country',
        'duration',
        'annual_amount',
        'installment_type',
        'start_date',
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
