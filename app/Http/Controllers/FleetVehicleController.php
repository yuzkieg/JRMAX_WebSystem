<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Driver;
use Illuminate\Support\Facades\Auth;

class FleetVehicleController extends Controller
{
    public function index()
    {
        // Get the current fleet user
        $user = Auth::user();
        
        // Get vehicles - fleet users might see all vehicles or only assigned ones
        // For now, let's show all vehicles but you can filter based on your logic
        $vehicles = Vehicle::with('driverInfo')->get();
        $drivers = Driver::all();
        
        return view('employee.fleet.vehicles', compact('vehicles', 'drivers'));
    }

    public function getVehiclesData()
    {
        try {
            $vehicles = Vehicle::with('driverInfo')->get();
            return response()->json($vehicles);
        } catch (\Exception $e) {
            \Log::error('Error fetching vehicles data: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }
}