<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingStatus extends Model
{
    protected $primaryKey = 'status_id';
    protected $table = 'BookingStatus';
    
    protected $fillable = ['status_name', 'color'];
}