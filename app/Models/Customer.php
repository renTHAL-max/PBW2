<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Customer extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'id_card_number',
        'address',
        'birth_date',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('id_card')
            ->singleFile()
            ->useDisk('public');
        
        $this->addMediaCollection('driver_license')
            ->singleFile()
            ->useDisk('public');
    }
}