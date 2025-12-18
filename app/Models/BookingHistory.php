<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingHistory extends Model
{
    protected $primaryKey = 'history_id';
    protected $table = 'BookingHistory';
    
    protected $fillable = [
        'booking_id',
        'changed_by',
        'previous_status',
        'new_status',
        'changes',
        'reason',
        'changed_at'
    ];

    protected $casts = [
        'changes' => 'array',
        'changed_at' => 'datetime'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'boarding_id');
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by', 'Next_id');
    }
}