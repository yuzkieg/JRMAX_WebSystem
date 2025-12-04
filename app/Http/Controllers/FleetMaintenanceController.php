<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VehicleMaintenance;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FleetMaintenanceController extends Controller
{
    public function index()
    {
        // Get current fleet user
        $user = Auth::user();
        
        // Get maintenances - fleet users can see all or filter by their responsibilities
        $maintenances = VehicleMaintenance::with(['vehicle', 'reporter'])
            ->orderBy('scheduled_date', 'asc')
            ->get();
        
        // Get vehicles for dropdowns
        $vehicles = Vehicle::all();
        
        // Get users (admins and fleet assistants) for reporting
        $users = User::whereIn('role', ['admin', 'fleet_assistant'])->get();
        
        return view('employee.fleet.maintenance', compact('maintenances', 'vehicles', 'users'));
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $maintenance = VehicleMaintenance::where('maintenance_ID', $id)->firstOrFail();
            
            // Fleet users can only update status, not other fields
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