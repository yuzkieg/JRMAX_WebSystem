<?php

namespace App\Traits;

use App\Models\Audit;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    /**
     * Boot the trait
     */
    protected static function bootAuditable()
    {
        // Log creation
        static::created(function ($model) {
            $model->auditCreate();
        });

        // Log update
        static::updated(function ($model) {
            $model->auditUpdate();
        });

        // Log deletion
        static::deleted(function ($model) {
            $model->auditDelete();
        });
    }

    /**
     * Log create action
     */
    protected function auditCreate()
    {
        $this->createAuditLog('create', 'Created new ' . $this->getAuditModule());
    }

    /**
     * Log update action
     */
    protected function auditUpdate()
    {
        $changes = $this->getChanges();
        
        if (!empty($changes)) {
            $description = 'Updated ' . $this->getAuditModule();
            
            // Add specific field changes to description
            $fieldNames = array_keys($changes);
            if (count($fieldNames) <= 3) {
                $description .= ': ' . implode(', ', $fieldNames);
            }

            $this->createAuditLog('update', $description, $this->getOriginal(), $changes);
        }
    }

    /**
     * Log delete action
     */
    protected function auditDelete()
    {
        $this->createAuditLog('delete', 'Deleted ' . $this->getAuditModule());
    }

    /**
     * Create audit log entry
     */
    protected function createAuditLog($action, $description, $oldValues = null, $newValues = null)
    {
        $user = Auth::user();
        
        Audit::create([
            'user_id' => $user->id ?? null,
            'user_name' => $user->name ?? 'System',
            'action' => $action,
            'module' => $this->getAuditModule(),
            'description' => $description,
            'old_values' => $oldValues ? json_encode($oldValues) : null,
            'new_values' => $newValues ? json_encode($newValues) : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'related_id' => $this->getKey(),
            'related_type' => get_class($this)
        ]);
    }

    /**
     * Get the module name for audit
     * Override this in your model if needed
     */
    protected function getAuditModule()
    {
        return class_basename($this);
    }

    /**
     * Get audit display name
     * Override this in your model to customize
     */
    protected function getAuditDisplayName()
    {
        // Try common name fields
        if (isset($this->name)) return $this->name;
        if (isset($this->title)) return $this->title;
        if (isset($this->plate_num)) return $this->plate_num;
        if (isset($this->first_name) && isset($this->last_name)) {
            return $this->first_name . ' ' . $this->last_name;
        }
        
        return $this->getAuditModule() . ' #' . $this->getKey();
    }

    /**
     * Manual audit log (for custom actions)
     */
    public static function logAudit($action, $module, $description, $relatedId = null, $relatedType = null)
    {
        $user = Auth::user();
        
        Audit::create([
            'user_id' => $user->id ?? null,
            'user_name' => $user->name ?? 'System',
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'related_id' => $relatedId,
            'related_type' => $relatedType
        ]);
    }
}   