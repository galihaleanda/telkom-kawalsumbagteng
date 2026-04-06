<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'witel_id',
    ];

    public function witel(){
        return $this->belongsTo(Witel::class);
    }

    public function datel() {
        return $this->hasMany(Datel::class);
    }
}
