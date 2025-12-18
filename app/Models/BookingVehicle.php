<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingVehicle extends Model
{
    protected $primaryKey = 'booking_vehicle_id';
    protected $table = 'BookingVehicle';
    
    protected $fillable = [
        'booking_id',
        'vehicle_id',
        'assigned_by',
        'assigned_at',
        'remarks'
    ];

    protected $casts = [
        'assigned_at' => 'datetime'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'boarding_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'vehicle_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by', 'id');
    }
}