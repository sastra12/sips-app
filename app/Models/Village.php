<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    use HasFactory;
    protected $table = "villages";
    protected $primaryKey = "village_id";
    protected $keyType = "int";
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = ['village_name', 'village_code'];

    public function waste_bank()
    {
        return $this->hasOne(WasteBank::class, 'village_id', 'village_id');
    }
}
