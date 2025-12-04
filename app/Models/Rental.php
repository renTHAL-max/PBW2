<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rental extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'rental_code',
        'customer_id',
        'user_id',
        'start_date',
        'end_date',
        'actual_return_date',
        'duration_days',
        'subtotal',
        'late_fee',
        'total_amount',
        'status',
        'payment_status',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'actual_return_date' => 'date',
        'subtotal' => 'decimal:2',
        'late_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($rental) {
            $rental->rental_code = 'RNT-' . date('Ymd') . '-' . str_pad(Rental::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(RentalItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function calculateLateFee()
    {
        if (!$this->actual_return_date || $this->actual_return_date <= $this->end_date) {
            return 0;
        }

        $lateDays = $this->actual_return_date->diffInDays($this->end_date);
        $dailyLateFee = $this->subtotal / $this->duration_days * 0.2; // 20% dari harga per hari
        
        return $lateDays * $dailyLateFee;
    }
}