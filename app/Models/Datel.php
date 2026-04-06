<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Datel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'branch_id'
    ] ;

    public function serviceAreas() {
        return $this->hasMany(ServiceArea::class);
    }
}
