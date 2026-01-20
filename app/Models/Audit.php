<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    use HasFactory;

    protected $table = 'audit_logs';
    protected $fillable = [
        'user_id',
        'user_name',
        'action',
        'module',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'related_id',
        'related_type'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relationship with User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the related model
     */
    public function related()
    {
        return $this->morphTo();
    }

    /**
     * Scope: Filter by action
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope: Filter by module
     */
    public function scopeByModule($query, $module)
    {
        return $query->where('module', $module);
    }

    /**
     * Scope: Filter by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope: Recent logs
     */
    public function scopeRecent($query, $limit = 100)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    /**
     * Get badge class based on action
     */
    public function getBadgeClassAttribute()
    {
        return match(strtolower($this->action)) {
            'create' => 'badge-create',
            'update' => 'badge-update',
            'delete' => 'badge-delete',
            'login' => 'badge-login',
            'logout' => 'badge-logout',
            default => 'badge-default'
        };
    }

    /**
     * Get formatted timestamp
     */
    public function getFormattedTimestampAttribute()
    {
        return $this->created_at->format('Y-m-d H:i:s');
    }
}