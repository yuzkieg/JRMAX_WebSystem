<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $table = 'drivers';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'email', 'license_num', 'dateadded', 'address', 'contact_number', 'status'];
    public $timestamps = true;
    
    protected $casts = [
        'status' => 'string',
    ];
}
