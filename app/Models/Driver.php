<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
     protected $table = 'drivers';
    protected $fillable = ['name', 'email', 'license_num', 'dateadded'];
}
