<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteBank extends Model
{
    use HasFactory;
    protected $table = "waste_banks";
    protected $primaryKey = "waste_bank_id";
    protected $keyType = "int";
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = ['waste_name', 'village_id'];

    public function village()
    {
        return $this->belongsTo(Village::class, 'village_id', 'village_id');
    }

    public function waste_entries()
    {
        return $this->hasMany(WasteEntry::class, 'waste_id', 'waste_bank_id');
    }

    public function waste_bank_users()
    {
        return $this->belongsToMany(User::class, 'user_waste_bank', 'waste_id', 'user_id');
    }

    public function customers()
    {
        return $this->hasMany(Customer::class, 'waste_id', 'waste_bank_id');
    }
}
