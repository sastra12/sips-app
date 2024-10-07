<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
        'amount_due',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    // untuk uuid
    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->payment_id = Str::uuid();
        });
    }
}
