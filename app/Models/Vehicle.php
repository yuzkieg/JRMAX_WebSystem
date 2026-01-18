<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory,Auditable;

    protected $primaryKey = 'vehicle_id';
    protected $table = 'vehicles';
    
    // Cast driver to integer
    protected $casts = [
        'driver' => 'integer',
        'is_available' => 'boolean',
        'year' => 'integer',
        'seat_cap' => 'integer',
        'price_rate' => 'float'
    ];
    
    protected $fillable = [
        'plate_num', 'brand', 'model', 'year', 'body_type', 
        'seat_cap', 'transmission', 'fuel_type', 'color', 
        'price_rate', 'driver', 'added_by', 'updated_by',
        'image', 'is_available' // Add these
    ];

    // Add appends for JSON response
    protected $appends = ['image_url', 'driver_name'];

    // Relationship
    public function driverInfo()
    {
        return $this->belongsTo(Driver::class, 'driver', 'id');
    }

    // Get driver attribute
    public function getDriverAttribute()
    {
        if ($this->relationLoaded('driverInfo') && $this->driverInfo) {
            return $this->driverInfo;
        }
        
        return $this->attributes['driver'] ?? null;
    }
    
    // Get driver name safely
    public function getDriverNameAttribute()
    {
        if ($this->driverInfo) {
            return $this->driverInfo->name;
        }
        return 'No Driver';
    }

    // Get full image URL
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/vehicles/' . $this->image);
        }
        
        // Return default vehicle image
        return asset('assets/default-vehicle.jpg');
    }

    protected function getAuditModule()
    {
        return 'Vehicles';
    }
}