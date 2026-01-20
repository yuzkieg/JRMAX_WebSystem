<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $primaryKey = 'payment_id';
    protected $table = 'Payment';
    
    protected $fillable = [
        'booking_id',
        'amount',
        'payment_method_id',
        'payment_status_id',
        'reference_number',
        'paid_at',
        'issued_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'boarding_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function paymentStatus()
    {
        return $this->belongsTo(PaymentStatus::class, 'payment_status_id');
    }

    public function issuedBy()
    {
        return $this->belongsTo(User::class, 'issued_by', 'id');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'payment_id', 'payment_id');
    }
}