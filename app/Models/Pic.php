<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pic extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'service_area_id',
    ];

    public function serviceArea() {
        return $this->belongsTo(ServiceArea::class);
    }
}
