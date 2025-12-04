<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $primaryKey = 'vehicle_id';
    protected $table = 'vehicles'; // Explicitly set table name
    
    // Cast driver to integer
    protected $casts = [
        'driver' => 'integer',
    ];
    
    protected $fillable = [
        'plate_num', 'brand', 'model', 'year', 'body_type', 
        'seat_cap', 'transmission', 'fuel_type', 'color', 
        'price_rate', 'driver', 'added_by', 'updated_by'
    ];

    // Use a different name for the relationship
    public function driverInfo()
{
    return $this->belongsTo(Driver::class, 'driver', 'id');
}

// Update the getDriverAttribute method:
public function getDriverAttribute()
{
    // If driverInfo relation is loaded, return it
    if ($this->relationLoaded('driverInfo') && $this->driverInfo) {
        return $this->driverInfo;
    }
    
    // Otherwise, return the raw driver ID
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
}