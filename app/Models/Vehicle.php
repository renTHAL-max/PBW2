<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Vehicle extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'vehicle_category_id',
        'license_plate',
        'brand',
        'model',
        'year',
        'color',
        'price_per_day',
        'status',
        'description',
        'seat_capacity',
        'transmission',
        'fuel_type',
    ];

    protected $casts = [
        'price_per_day' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(VehicleCategory::class, 'vehicle_category_id');
    }

    public function rentalItems()
    {
        return $this->hasMany(RentalItem::class);
    }

    public function maintenanceRecords()
    {
        return $this->hasMany(MaintenanceRecord::class);
    }

    public function isAvailableForRent($startDate, $endDate)
    {
        if ($this->status !== 'tersedia') {
            return false;
        }

        $conflictingRentals = RentalItem::where('vehicle_id', $this->id)
            ->whereHas('rental', function ($query) use ($startDate, $endDate) {
                $query->whereIn('status', ['pending', 'active'])
                    ->where(function ($q) use ($startDate, $endDate) {
                        $q->whereBetween('start_date', [$startDate, $endDate])
                          ->orWhereBetween('end_date', [$startDate, $endDate])
                          ->orWhere(function ($q2) use ($startDate, $endDate) {
                              $q2->where('start_date', '<=', $startDate)
                                 ->where('end_date', '>=', $endDate);
                          });
                    });
            })
            ->exists();

        return !$conflictingRentals;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('vehicle_images')
            ->useDisk('public');
    }
}