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
        'price_rate' => 'float',
        'released_at' => 'datetime',
        'returned_at' => 'datetime'
    ];
    
    protected $fillable = [
        'plate_num', 'brand', 'model', 'year', 'body_type', 
        'seat_cap', 'transmission', 'fuel_type', 'color', 
        'price_rate', 'driver', 'added_by', 'updated_by',
        'image', 'is_available', 'vehicle_image', 'maintenance_handled_by',
        'released_by', 'received_by', 'picked_up_by_client_id', 
        'dropped_off_by_client_id', 'released_at', 'returned_at'
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
        // Check vehicle_image first (new field)
        if ($this->vehicle_image) {
            // vehicle_image is stored as 'vehicles/filename.jpg'
            return asset('storage/' . $this->vehicle_image);
        }
        
        // Fallback to legacy image field
        if ($this->image) {
            // image is stored as just 'filename.jpg' in storage/vehicles/
            return asset('storage/vehicles/' . $this->image);
        }
        
        // Return default vehicle image
        return asset('assets/default-vehicle.jpg');
    }

    // Relationships for handover tracking
    public function rentedVehicles()
    {
        return $this->hasMany(SelfDriverRentedVehicle::class, 'vehicle_id', 'vehicle_id');
    }

    public function currentRental()
    {
        return $this->hasOne(SelfDriverRentedVehicle::class, 'vehicle_id', 'vehicle_id')
            ->where('status', 'on_client')
            ->latest('released_at');
    }

    public function releasedByUser()
    {
        return $this->belongsTo(User::class, 'released_by', 'id');
    }

    public function receivedByUser()
    {
        return $this->belongsTo(User::class, 'received_by', 'id');
    }

    public function pickedUpByClient()
    {
        return $this->belongsTo(Client::class, 'picked_up_by_client_id', 'Editor_id');
    }

    public function droppedOffByClient()
    {
        return $this->belongsTo(Client::class, 'dropped_off_by_client_id', 'Editor_id');
    }

    protected function getAuditModule()
    {
        return 'Vehicles';
    }
}