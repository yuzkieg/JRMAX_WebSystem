<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Driver;
use Illuminate\Support\Facades\Hash;

class VehicleController extends Controller
{
public function index()
{
    // Eager load with the correct relationship name
    $vehicles = Vehicle::with('driverInfo')->get();
    
    $drivers = Driver::all();
    
    // Don't transform the data here - let JavaScript handle it
    // Just pass the vehicles as they are
    return view('admin.vehicles', [
        'vehicles' => $vehicles,
        'drivers' => $drivers,
    ]);
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
    public function store(Request $request)
{
    try {
        \Log::info('Vehicle store request data:', $request->all()); // Keep this for debugging

        $validated = $request->validate([
            'plate_num' => 'required|max:7|unique:vehicles,plate_num',
            'brand' => 'required|string',
            'model' => 'required|string',
            'year' => 'required|integer|between:1980,2099',
            'body_type' => 'required|string',
            'seat_cap' => 'required|integer|min:1',
            'transmission' => 'required|string',
            'fuel_type' => 'required|string',
            'color' => 'required|string',
            'price_rate' => 'required|numeric|min:0',
            'driver' => 'nullable|integer|exists:drivers,id',
        ]);

        // Handle driver field properly
        if (!isset($validated['driver']) || $validated['driver'] === '' || $validated['driver'] === 'null') {
            $validated['driver'] = null;
        } else {
            // Ensure driver is cast to integer
            $validated['driver'] = (int) $validated['driver'];
        }
        
        // Cast other numeric fields
        $validated['year'] = (int) $validated['year'];
        $validated['seat_cap'] = (int) $validated['seat_cap'];
        $validated['price_rate'] = (float) $validated['price_rate'];
        
        // Automatically set added_by to current user ID
        $validated['added_by'] = auth()->check() ? auth()->user()->id : null;

        \Log::info('Vehicle data after processing:', $validated); // Add this

        Vehicle::create($validated);

        return response()->json(['success' => true, 'message' => 'Vehicle added successfully']);
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::warning('Vehicle store validation error: ' . json_encode($e->errors()));
        return response()->json(['success' => false, 'message' => 'Validation error', 'errors' => $e->errors()], 422);
    } catch (\Exception $e) {
        \Log::error('Vehicle store error: ' . $e->getMessage() . ' ' . $e->getFile() . ':' . $e->getLine());
        return response()->json(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
    }
}

    public function update(Request $request, Vehicle $vehicle)
{
    try {
        $validated = $request->validate([
            'plate_num' => 'required|max:7|unique:vehicles,plate_num,' . $vehicle->vehicle_id . ',vehicle_id',
            'brand' => 'required|string',
            'model' => 'required|string',
            'year' => 'required|integer|between:1980,2099',
            'body_type' => 'required|string',
            'seat_cap' => 'required|integer|min:1',
            'transmission' => 'required|string',
            'fuel_type' => 'required|string',
            'color' => 'required|string',
            'price_rate' => 'required|numeric|min:0',
            'driver' => 'nullable|integer|exists:drivers,id',
        ]);

        // Handle driver field properly
        if (!isset($validated['driver']) || $validated['driver'] === '' || $validated['driver'] === 'null') {
            $validated['driver'] = null;
        } else {
            // Ensure driver is cast to integer
            $validated['driver'] = (int) $validated['driver'];
        }
        
        // Cast other numeric fields
        $validated['year'] = (int) $validated['year'];
        $validated['seat_cap'] = (int) $validated['seat_cap'];
        $validated['price_rate'] = (float) $validated['price_rate'];

        // Automatically set updated_by to current user ID
        $validated['updated_by'] = auth()->check() ? auth()->user()->id : null;

        $vehicle->update($validated);

        return response()->json(['success' => true, 'message' => 'Vehicle updated successfully']);
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::warning('Vehicle update validation error: ' . json_encode($e->errors()));
        return response()->json(['success' => false, 'message' => 'Validation error', 'errors' => $e->errors()], 422);
    } catch (\Exception $e) {
        \Log::error('Vehicle update error: ' . $e->getMessage() . ' ' . $e->getFile() . ':' . $e->getLine());
        return response()->json(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
    }
}

    public function destroy(Request $request, $vehicle_id)
    {
        try {
            // Check if user is authenticated
            $user = auth()->user();
            if (!$user) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Unauthorized. Please login again.'
                ], 401);
            }

            // Validate password is provided
            $request->validate([
                'admin_password' => 'required|string',
            ], [
                'admin_password.required' => 'Password is required to confirm deletion.',
            ]);

            // Find the vehicle
            $vehicle = Vehicle::where('vehicle_id', $vehicle_id)->first();
            
            if (!$vehicle) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Vehicle not found'
                ], 404);
            }

            // Verify password is correct
            $password = $request->input('admin_password');
            if (!Hash::check($password, $user->password)) {
                \Log::warning('Password mismatch for user delete attempt: ' . $user->email);
                return response()->json([
                    'success' => false, 
                    'message' => 'Incorrect password. Please try again.'
                ], 422);
            }

            $vehicle->delete();
            
            return response()->json([
                'success' => true, 
                'message' => 'Vehicle deleted successfully'
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('Delete validation error: ' . json_encode($e->errors()));
            return response()->json([
                'success' => false, 
                'message' => 'Validation error', 
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Vehicle delete error: ' . $e->getMessage() . ' ' . $e->getFile() . ':' . $e->getLine());
            return response()->json([
                'success' => false, 
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }
}