<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sto extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'sektor_id'
    ];

    public function sektor() {
        return $this->belongsTo(Sektor::class);
    }
}
