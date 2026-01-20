<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VehicleMaintenance;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class VehicleMaintenanceController extends Controller
{
    public function index()
    {
        // Eager load relationships
        $maintenances = VehicleMaintenance::with(['vehicle', 'reporter', 'handler'])->get();
        $vehicles = Vehicle::all();
        $users = User::whereIn('role', ['admin', 'fleet_assistant'])->get();
        
        return view('admin.maintenance', compact('maintenances', 'vehicles', 'users'));
    }

    public function edit($id)
{
    try {
        $maintenance = VehicleMaintenance::with(['vehicle', 'handler', 'reporter'])
            ->where('maintenance_ID', $id)
            ->firstOrFail();
        
        return response()->json($maintenance);
    } catch (\Exception $e) {
        \Log::error('Maintenance edit error: ' . $e->getMessage());
        return response()->json(['error' => 'Record not found'], 404);
    }
}

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'vehicle_ID' => 'required|exists:vehicles,vehicle_id',
                'maintenance_type' => 'required|in:repair,check-up,oil change,tire replacement,engine service,cleaning,other',
                'description' => 'nullable|string|max:1000',
                'odometer_reading' => 'nullable|integer|min:0',
                'scheduled_date' => 'required|date|after_or_equal:today',
                'cost' => 'required|numeric|min:0',
                'status' => 'required|in:scheduled,in progress,completed,cancelled',
                'handled_by' => 'nullable|exists:users,id',
            ]);

            // Set current user as reporter
            $validated['reported_by'] = auth()->id();
            
            // Set handled_by to current user if not specified
            if (!isset($validated['handled_by']) || empty($validated['handled_by'])) {
                $validated['handled_by'] = auth()->id();
            }
            
            // If status is 'in progress', set started_at
            if ($validated['status'] === 'in progress') {
                $validated['started_at'] = now();
            }
            
            // If status is 'completed', set completed_at
            if ($validated['status'] === 'completed') {
                $validated['completed_at'] = now();
                if (!isset($validated['started_at'])) {
                    $validated['started_at'] = now()->subHours(2); // Assume started 2 hours ago
                }
            }

            VehicleMaintenance::create($validated);

            return response()->json([
                'success' => true, 
                'message' => 'Maintenance record added successfully'
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Validation error', 
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Maintenance store error: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $maintenance = VehicleMaintenance::where('maintenance_ID', $id)->firstOrFail();
            
            $validated = $request->validate([
                'vehicle_ID' => 'required|exists:vehicles,vehicle_id',
                'maintenance_type' => 'required|in:repair,check-up,oil change,tire replacement,engine service,cleaning,other',
                'description' => 'nullable|string|max:1000',
                'odometer_reading' => 'nullable|integer|min:0',
                'scheduled_date' => 'required|date',
                'cost' => 'required|numeric|min:0',
                'status' => 'required|in:scheduled,in progress,completed,cancelled',
                'handled_by' => 'nullable|exists:users,id',
            ]);

            // Handle status transitions
            if ($validated['status'] === 'in progress' && $maintenance->status !== 'in progress') {
                $validated['started_at'] = now();
            }
            
            if ($validated['status'] === 'completed' && $maintenance->status !== 'completed') {
                $validated['completed_at'] = now();
                if (!$maintenance->started_at) {
                    $validated['started_at'] = now()->subHours(2);
                }
            }

            $maintenance->update($validated);

            return response()->json([
                'success' => true, 
                'message' => 'Maintenance record updated successfully'
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Validation error', 
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Maintenance update error: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
{
    try {
        // Ensure user is authenticated
        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Please login again.'
            ], 401);
        }

        // ADD THIS: Check if user has appropriate role
        if (!in_array($user->role, ['admin', 'fleet_assistant'])) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to delete maintenance records.'
            ], 403);
        }

        // Validate password input
        $request->validate([
            'admin_password' => 'required|string',
        ], [
            'admin_password.required' => 'Password is required to confirm deletion.',
        ]);

        $password = (string) $request->input('admin_password');

        // Ensure stored password is available
        if (empty($user->password)) {
            \Log::warning('User has no password set for delete attempt: ' . $user->id);
            return response()->json([
                'success' => false,
                'message' => 'Account cannot be authenticated. Please contact administrator.'
            ], 422);
        }

        // Check if password is correct
        if (!Hash::check($password, $user->password)) {
            \Log::warning('Password mismatch for maintenance delete by user: ' . ($user->email ?? $user->id));
            return response()->json([
                'success' => false,
                'message' => 'Incorrect password. Please try again.'
            ], 422);
        }

        $maintenance = VehicleMaintenance::where('maintenance_ID', $id)->firstOrFail();
        $maintenance->delete();

        return response()->json([
            'success' => true,
            'message' => 'Maintenance record deleted successfully'
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::warning('Maintenance delete validation error: ' . json_encode($e->errors()));
        return response()->json([
            'success' => false,
            'message' => 'Validation error',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        \Log::error('Maintenance delete error: ' . $e->getMessage() . ' ' . $e->getFile() . ':' . $e->getLine());
        return response()->json([
            'success' => false,
            'message' => 'Server error: ' . $e->getMessage()
        ], 500);
    }
}

    // Additional method to update status only
    public function updateStatus(Request $request, $id)
    {
        try {
            $maintenance = VehicleMaintenance::where('maintenance_ID', $id)->firstOrFail();
            
            $request->validate([
                'status' => 'required|in:scheduled,in progress,completed,cancelled',
            ]);

            $updateData = ['status' => $request->status];

            // Handle timestamps based on status
            if ($request->status === 'in progress' && !$maintenance->started_at) {
                $updateData['started_at'] = now();
            }
            
            if ($request->status === 'completed' && !$maintenance->completed_at) {
                $updateData['completed_at'] = now();
                if (!$maintenance->started_at) {
                    $updateData['started_at'] = now()->subHours(2);
                }
            }

            $maintenance->update($updateData);

            return response()->json([
                'success' => true, 
                'message' => 'Status updated successfully',
                'maintenance' => $maintenance->fresh(['vehicle', 'reporter'])
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Update status error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Server error'], 500);
        }
    }
}