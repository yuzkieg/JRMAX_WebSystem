<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    /**
     * Display audit logs with filtering and pagination
     */
    public function index(Request $request)
    {
        $query = Audit::with('user')->orderBy('created_at', 'desc');

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('module', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%");
            });
        }

        // Apply action filter
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Apply module filter
        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        // Apply date filter
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Get paginated logs
        $logs = $query->paginate(20);

        // Get unique modules and actions for filter dropdowns
        $modules = Audit::select('module')
            ->distinct()
            ->orderBy('module')
            ->pluck('module');
            
        $actions = Audit::select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        return view('admin.audit.index', compact('logs', 'modules', 'actions'));
    }

    /**
     * Show specific audit log details (AJAX)
     */
    public function show($id)
    {
        $log = Audit::with('user')->findOrFail($id);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'log' => [
                    'id' => $log->id,
                    'user' => $log->user_name,
                    'action' => strtoupper($log->action),
                    'module' => $log->module,
                    'description' => $log->description,
                    'timestamp' => $log->formatted_timestamp,
                    'ip_address' => $log->ip_address,
                    'user_agent' => $log->user_agent,
                    'old_values' => $log->old_values,
                    'new_values' => $log->new_values,
                    'badge_class' => $log->badge_class
                ]
            ]);
        }

        return view('admin.audit.show', compact('log'));
    }

    /**
     * Get audit logs data (for AJAX/DataTables)
     */
    public function getData(Request $request)
    {
        $query = Audit::with('user')->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('module', 'like', "%{$search}%");
            });
        }

        $logs = $query->get();

        return response()->json([
            'success' => true,
            'data' => $logs->map(function($log) {
                return [
                    'id' => $log->id,
                    'user' => $log->user_name,
                    'action' => strtoupper($log->action),
                    'module' => $log->module,
                    'description' => $log->description,
                    'timestamp' => $log->formatted_timestamp,
                    'date' => $log->created_at->format('Y-m-d'),
                    'badge_class' => $log->badge_class
                ];
            })
        ]);
    }

    /**
     * Export audit logs to CSV
     */
    public function export(Request $request)
    {
        $query = Audit::with('user')->orderBy('created_at', 'desc');

        // Apply same filters as index
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('module', 'like', "%{$search}%");
            });
        }

        $logs = $query->get();

        // Create CSV
        $filename = 'audit_logs_' . now()->format('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'ID', 
                'User', 
                'Action', 
                'Module', 
                'Description', 
                'Timestamp', 
                'IP Address'
            ]);

            // CSV Data
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->user_name,
                    strtoupper($log->action),
                    $log->module,
                    $log->description,
                    $log->formatted_timestamp,
                    $log->ip_address ?? 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get statistics for audit dashboard
     */
    public function getStats(Request $request)
    {
        $period = $request->get('period', 'today'); // today, week, month, year
        
        $query = Audit::query();
        
        switch ($period) {
            case 'today':
                $query->whereDate('created_at', today());
                break;
            case 'week':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);
                break;
            case 'year':
                $query->whereYear('created_at', now()->year);
                break;
        }

        $stats = [
            'total_logs' => $query->count(),
            'create_actions' => $query->clone()->where('action', 'create')->count(),
            'update_actions' => $query->clone()->where('action', 'update')->count(),
            'delete_actions' => $query->clone()->where('action', 'delete')->count(),
            'login_actions' => $query->clone()->where('action', 'login')->count(),
            'unique_users' => $query->distinct('user_id')->count('user_id'),
            'most_active_module' => Audit::selectRaw('module, COUNT(*) as count')
                ->when($period, function($q) use ($period) {
                    switch ($period) {
                        case 'today':
                            return $q->whereDate('created_at', today());
                        case 'week':
                            return $q->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                        case 'month':
                            return $q->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                        case 'year':
                            return $q->whereYear('created_at', now()->year);
                    }
                })
                ->groupBy('module')
                ->orderByDesc('count')
                ->first()
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats,
            'period' => $period
        ]);
    }

    /**
     * Delete old audit logs (cleanup utility)
     */
    public function cleanup(Request $request)
    {
        $validated = $request->validate([
            'days' => 'required|integer|min:30|max:365'
        ]);

        $days = $validated['days'];
        $cutoffDate = now()->subDays($days);
        
        $deletedCount = Audit::where('created_at', '<', $cutoffDate)->delete();

        return response()->json([
            'success' => true,
            'message' => "Successfully deleted {$deletedCount} audit logs older than {$days} days.",
            'deleted_count' => $deletedCount
        ]);
    }

    /**
     * Get activity timeline for a specific user
     */
    public function userActivity($userId)
    {
        $logs = Audit::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return response()->json([
            'success' => true,
            'logs' => $logs
        ]);
    }

    /**
     * Get activity for a specific module/record
     */
    public function recordActivity(Request $request)
    {
        $validated = $request->validate([
            'module' => 'required|string',
            'record_id' => 'required|integer'
        ]);

        $logs = Audit::where('module', $validated['module'])
            ->where('related_id', $validated['record_id'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'logs' => $logs
        ]);
    }
}