<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleMaintenance extends Model
{
    use HasFactory,Auditable;

    protected $primaryKey = 'maintenance_ID';
    protected $table = 'vehicle_maintenance';

    protected $fillable = [
        'vehicle_ID',
        'reported_by',
        'maintenance_type',
        'description',
        'odometer_reading',
        'scheduled_date',
        'started_at',
        'completed_at',
        'cost',
        'status'
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'cost' => 'decimal:2',
    ];

    // Relationship with Vehicle
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_ID', 'vehicle_id');
    }

    // Relationship with User (reporter)
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    protected function getAuditModule()
    {
        return 'Maintenances';
    }
}