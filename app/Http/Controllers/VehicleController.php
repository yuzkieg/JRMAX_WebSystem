<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Driver;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class VehicleController extends Controller
{
    /**
     * Display the vehicles management page
     */
    public function index()
    {
        // Eager load with the correct relationship name
        $vehicles = Vehicle::with('driverInfo')->get();
        
        $drivers = Driver::all();
        
        // Pass the vehicles as they are
        return view('admin.vehicles', [
            'vehicles' => $vehicles,
            'drivers' => $drivers,
        ]);
    }

    /**
     * Get vehicles data for AJAX requests
     */
    public function getVehiclesData()
    {
        try {
            $vehicles = Vehicle::with('driverInfo')->get();
            return response()->json($vehicles);
        } catch (\Exception $e) {
            Log::error('Error fetching vehicles data: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }

    /**
     * Store a newly created vehicle
     */
    public function store(Request $request)
    {
        try {
            Log::info('Vehicle store request data:', $request->all());

            // Updated validation - removed unique from model
            $validated = $request->validate([
                'plate_num' => 'required|max:7|unique:vehicles,plate_num',
                'brand' => 'required|string',
                'model' => 'required|string', // REMOVED: |unique:vehicles,model
                'year' => 'required|integer|between:1980,2099',
                'body_type' => 'required|string',
                'seat_cap' => 'required|integer|min:1',
                'transmission' => 'required|string|in:Automatic,Manual',
                'fuel_type' => 'required|string|in:Gasoline,Diesel,Electric,Hybrid',
                'color' => 'required|string',
                'price_rate' => 'required|numeric|min:0',
                'driver' => 'nullable|integer|exists:drivers,id',
                'is_available' => 'nullable|boolean',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('profile', 'public');
                $validated['image'] = $imagePath;
            }

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
            $validated['is_available'] = $validated['is_available'] ?? true;
            
            // Automatically set added_by to current user ID
            $validated['added_by'] = auth()->check() ? auth()->user()->id : null;

            Log::info('Vehicle data after processing:', $validated);

            Vehicle::create($validated);

            return response()->json([
                'success' => true, 
                'message' => 'Vehicle added successfully'
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Vehicle store validation error: ' . json_encode($e->errors()));
            return response()->json([
                'success' => false, 
                'message' => 'Validation error', 
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Vehicle store error: ' . $e->getMessage() . ' ' . $e->getFile() . ':' . $e->getLine());
            return response()->json([
                'success' => false, 
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified vehicle
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        try {
            Log::info('Vehicle update request data:', $request->all());

            // Updated validation - removed unique from model
            $validated = $request->validate([
                'plate_num' => 'required|max:7|unique:vehicles,plate_num,' . $vehicle->vehicle_id . ',vehicle_id',
                'brand' => 'required|string',
                'model' => 'required|string', // REMOVED: |unique:vehicles,model
                'year' => 'required|integer|between:1980,2099',
                'body_type' => 'required|string',
                'seat_cap' => 'required|integer|min:1',
                'transmission' => 'required|string|in:Automatic,Manual',
                'fuel_type' => 'required|string|in:Gasoline,Diesel,Electric,Hybrid',
                'color' => 'required|string',
                'price_rate' => 'required|numeric|min:0',
                'driver' => 'nullable|integer|exists:drivers,id',
                'is_available' => 'nullable|boolean',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($vehicle->image) {
                    Storage::delete('public/vehicles/' . $vehicle->image);
                }
                
                $image = $request->file('image');
                $filename = time() . '_' . Str::slug($request->brand . '-' . $request->model) . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/vehicles', $filename);
                $validated['image'] = $filename;
            } else {
                // Keep existing image if not uploading new one
                unset($validated['image']);
            }

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
            $validated['is_available'] = $validated['is_available'] ?? true;

            // Automatically set updated_by to current user ID
            $validated['updated_by'] = auth()->check() ? auth()->user()->id : null;

            $vehicle->update($validated);

            return response()->json([
                'success' => true, 
                'message' => 'Vehicle updated successfully'
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Vehicle update validation error: ' . json_encode($e->errors()));
            return response()->json([
                'success' => false, 
                'message' => 'Validation error', 
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Vehicle update error: ' . $e->getMessage() . ' ' . $e->getFile() . ':' . $e->getLine());
            return response()->json([
                'success' => false, 
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified vehicle
     */
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
                Log::warning('Password mismatch for user delete attempt: ' . $user->email);
                return response()->json([
                    'success' => false, 
                    'message' => 'Incorrect password. Please try again.'
                ], 422);
            }

            // Delete image if exists
            if ($vehicle->image) {
                Storage::delete('public/vehicles/' . $vehicle->image);
            }

            $vehicle->delete();
            
            return response()->json([
                'success' => true, 
                'message' => 'Vehicle deleted successfully'
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Delete validation error: ' . json_encode($e->errors()));
            return response()->json([
                'success' => false, 
                'message' => 'Validation error', 
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Vehicle delete error: ' . $e->getMessage() . ' ' . $e->getFile() . ':' . $e->getLine());
            return response()->json([
                'success' => false, 
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }
}