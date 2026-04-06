<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceArea extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sa_code',
        'datel_id',
    ] ;

    public function datel() {
        return $this->belongsTo(Datel::class);
    }

    public function sektors() {
        return $this->hasMany(Sektor::class);
    }

    public function pics() {
        return $this->hasMany(Pic::class);
    }
}
