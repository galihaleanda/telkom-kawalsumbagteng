<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sektor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'service_area_id'
    ];

    public function serviceArea() {
        return $this->belongsTo(ServiceArea::class);
    }

    public function stos() {
        return $this->hasMany(Sto::class);
    }
}
