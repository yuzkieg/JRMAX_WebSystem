<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelfDriverRentedVehicle extends Model
{
    protected $primaryKey = 'rental_id';
    protected $table = 'selfdriver_rented_vehicles';
    
    protected $fillable = [
        'vehicle_id',
        'booking_id',
        'released_by',
        'received_by',
        'picked_up_by_client_id',
        'dropped_off_by_client_id',
        'released_at',
        'returned_at',
        'status',
        'release_notes',
        'return_notes'
    ];

    protected $casts = [
        'released_at' => 'datetime',
        'returned_at' => 'datetime'
    ];

    // Relationships
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'vehicle_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'boarding_id');
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
}
