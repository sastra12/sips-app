<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteEntry extends Model
{
    use HasFactory;
    protected $table = "waste_entries";
    protected $primaryKey = "entry_id";
    protected $keyType = "int";
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = ['waste_organic', 'waste_anorganic', 'waste_residu', 'waste_id'];

    public function waste_bank()
    {
        return $this->belongsTo(WasteBank::class, 'waste_id', 'waste_bank_id');
    }
}
