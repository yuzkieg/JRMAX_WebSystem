<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use Auditable;
    protected $primaryKey = 'boarding_id';
    protected $table = 'bookings';
    
    protected $fillable = [
        'client_id',
        'boarding_date',
        'start_datetime',
        'end_datetime',
        'pickup_location',
        'dropoff_location',
        'driver_id',
        'total_price',
        'status_id',
        'payment_method',
        'special_requests',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'boarding_date' => 'date',
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'total_price' => 'decimal:2'
    ];

    // Relationships
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'Editor_id');
    }

    public function vehicles()
    {
        return $this->hasMany(BookingVehicle::class, 'booking_id', 'boarding_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'id', 'id');
    }

    public function status()
    {
        return $this->belongsTo(BookingStatus::class, 'status_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'booking_id', 'boarding_id');
    }

    // Helper methods
    public function getFormattedTotalAttribute()
    {
        return '₱' . number_format($this->total_price, 2);
    }

    public function getDurationAttribute()
    {
        if ($this->start_datetime && $this->end_datetime) {
            $start = \Carbon\Carbon::parse($this->start_datetime);
            $end = \Carbon\Carbon::parse($this->end_datetime);
            
            $days = $start->diffInDays($end);
            $hours = $start->diffInHours($end);
            
            if ($days > 0) {
                return $days . ' day' . ($days > 1 ? 's' : '');
            } else {
                return $hours . ' hour' . ($hours > 1 ? 's' : '');
            }
        }
        return 'N/A';
    }

    public function getStatusColorAttribute()
    {
        return $this->status ? $this->status->color : '#6B7280';
    }

    protected function getAuditModule()
    {
        return 'Bookings';
    }

}