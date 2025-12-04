<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentalItem extends Model
{
    protected $fillable = [
        'rental_id',
        'vehicle_id',
        'price_per_day',
        'days',
        'subtotal',
    ];

    protected $casts = [
        'price_per_day' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}