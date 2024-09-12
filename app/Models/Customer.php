<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $table = "customers";
    protected $primaryKey = "customer_id";
    protected $keyType = "int";
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'customer_name', 'customer_address', 'customer_neighborhood',
        'customer__community_association', 'rubbish_fee', 'customer_status', 'waste_id'
    ];

    public function waste_bank()
    {
        return $this->belongsTo(WasteBank::class, 'waste_id', 'waste_bank_id');
    }
}
