<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'name',
        'phone',
        'street_address',
        'city',
        'zip_code'
    ];

    public function order() {
        return $this->belongsTo(Order::class);
    }

}
