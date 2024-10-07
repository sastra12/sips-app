<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WastePayment extends Model
{
    use HasFactory;
    protected $table = "waste_payments";
    protected $primaryKey = "payment_id";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'customer_id', 'month_payment', 'year_payment',
        'amount_due', 'status'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }
}
