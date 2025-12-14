<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rental extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'customer_id',
        'rental_code',
        'start_date',
        'end_date',
        'duration_days',
        'status',
        'subtotal',
        'late_fee',
        'total_amount',
        'payment_status',
        'notes',
        'actual_return_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'actual_return_date' => 'date',
        'subtotal' => 'decimal:2',
        'late_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    // Auto-fill user_id dan rental_code saat create
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($rental) {
            // Auto-fill user_id
            if (empty($rental->user_id)) {
                $rental->user_id = auth()->id();
            }
            
            // Auto-generate rental_code
            if (empty($rental->rental_code)) {
                $rental->rental_code = static::generateRentalCode();
            }
        });
    }

    /**
     * Generate rental code dengan format: RNT-YYYYMMDD-XXXX
     */
    protected static function generateRentalCode(): string
    {
        $date = now()->format('Ymd');
        $prefix = 'RNT-' . $date . '-';
        
        // Cari rental terakhir hari ini
        $lastRental = static::whereDate('created_at', now()->toDateString())
            ->orderBy('id', 'desc')
            ->first();
        
        if ($lastRental && str_starts_with($lastRental->rental_code, $prefix)) {
            // Extract nomor urut dari rental code terakhir
            $lastNumber = (int) substr($lastRental->rental_code, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(RentalItem::class);
    }
}