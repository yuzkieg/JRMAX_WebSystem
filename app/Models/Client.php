<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $primaryKey = 'Editor_id';
    protected $table = 'Client';
    
    protected $fillable = [
        'first_name',
        'last_name',
        'contact_number',
        'email',
        'license_number',
        'address',
        'status_id',
        'emergency_contact',
        'notes'
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'client_id', 'Editor_id');
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    // Accessor to provide a consistent `client_id` attribute used across views/controllers
    public function getClientIdAttribute()
    {
        return $this->Editor_id;
    }

    // Provide a `phone_number` alias for existing `contact_number` column
    public function getPhoneNumberAttribute()
    {
        return $this->contact_number;
    }

    // Map identification fields to existing license column
    public function getIdentificationTypeAttribute()
    {
        return 'License';
    }

    public function getIdentificationNumberAttribute()
    {
        return $this->license_number;
    }
}