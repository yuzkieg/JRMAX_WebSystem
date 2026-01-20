<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Driver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class FleetVehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::with('driverInfo')->get();
        $drivers = Driver::all();
        $clients = \App\Models\Client::where('status_id', 1)->orderBy('first_name')->get();
        
        return view('employee.fleet.vehicles', compact('vehicles', 'drivers', 'clients'));
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
            \Log::info('Fleet vehicle store request:', $request->all());

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

            // Handle driver field
            if (!isset($validated['driver']) || $validated['driver'] === '' || $validated['driver'] === 'null') {
                $validated['driver'] = null;
            } else {
                $validated['driver'] = (int) $validated['driver'];
            }
            
            // Cast numeric fields
            $validated['year'] = (int) $validated['year'];
            $validated['seat_cap'] = (int) $validated['seat_cap'];
            $validated['price_rate'] = (float) $validated['price_rate'];
            
            // Set added_by to current fleet user
            $validated['added_by'] = auth()->id();

            Vehicle::create($validated);

            return response()->json([
                'success' => true, 
                'message' => 'Vehicle added successfully'
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('Fleet vehicle store validation error: ' . json_encode($e->errors()));
            return response()->json([
                'success' => false, 
                'message' => 'Validation error', 
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Fleet vehicle store error: ' . $e->getMessage() . ' ' . $e->getFile() . ':' . $e->getLine());
            return response()->json([
                'success' => false, 
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
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

            // Handle driver field
            if (!isset($validated['driver']) || $validated['driver'] === '' || $validated['driver'] === 'null') {
                $validated['driver'] = null;
            } else {
                $validated['driver'] = (int) $validated['driver'];
            }
            
            // Cast numeric fields
            $validated['year'] = (int) $validated['year'];
            $validated['seat_cap'] = (int) $validated['seat_cap'];
            $validated['price_rate'] = (float) $validated['price_rate'];

            // Set updated_by to current fleet user
            $validated['updated_by'] = auth()->id();

            $vehicle->update($validated);

            return response()->json([
                'success' => true, 
                'message' => 'Vehicle updated successfully'
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('Fleet vehicle update validation error: ' . json_encode($e->errors()));
            return response()->json([
                'success' => false, 
                'message' => 'Validation error', 
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Fleet vehicle update error: ' . $e->getMessage() . ' ' . $e->getFile() . ':' . $e->getLine());
            return response()->json([
                'success' => false, 
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
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

            // Verify password is correct (fleet user's own password)
            $password = $request->input('admin_password');
            if (!Hash::check($password, $user->password)) {
                \Log::warning('Password mismatch for fleet vehicle delete attempt: ' . $user->email);
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
            \Log::warning('Fleet vehicle delete validation error: ' . json_encode($e->errors()));
            return response()->json([
                'success' => false, 
                'message' => 'Validation error', 
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Fleet vehicle delete error: ' . $e->getMessage() . ' ' . $e->getFile() . ':' . $e->getLine());
            return response()->json([
                'success' => false, 
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handover vehicle to client (self-drive)
     */
    public function handover(Request $request, $vehicle_id)
    {
        try {
            $vehicle = Vehicle::findOrFail($vehicle_id);
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Please login again.'
                ], 401);
            }

            $validated = $request->validate([
                'password' => 'required|string',
                'client_id' => 'required|exists:Client,Editor_id',
                'notes' => 'nullable|string|max:1000',
            ]);

            // Validate password against authenticated user
            if (!Hash::check($validated['password'], $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Incorrect password. Please try again.'
                ], 422);
            }

            // Check if vehicle is available
            if (!$vehicle->is_available) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vehicle is not available for handover. It may already be with a client.'
                ], 422);
            }

            DB::beginTransaction();

            // Create rental record
            $rental = \App\Models\SelfDriverRentedVehicle::create([
                'vehicle_id' => $vehicle->vehicle_id,
                'booking_id' => null, // Can be linked to booking if needed
                'released_by' => $user->id,
                'picked_up_by_client_id' => $validated['client_id'],
                'released_at' => now(),
                'status' => 'on_client',
                'release_notes' => $validated['notes'] ?? null,
            ]);

            // Update vehicle status
            $vehicle->update([
                'is_available' => false,
                'released_by' => $user->id,
                'picked_up_by_client_id' => $validated['client_id'],
                'released_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Vehicle successfully handed over to client.'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Vehicle handover error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error processing handover: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Receive vehicle from client (return)
     */
    public function returnVehicle(Request $request, $vehicle_id)
    {
        try {
            $vehicle = Vehicle::findOrFail($vehicle_id);
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Please login again.'
                ], 401);
            }

            $validated = $request->validate([
                'password' => 'required|string',
                'client_id' => 'required|exists:Client,Editor_id',
                'notes' => 'nullable|string|max:1000',
            ]);

            // Validate password against authenticated user
            if (!Hash::check($validated['password'], $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Incorrect password. Please try again.'
                ], 422);
            }

            // Check if vehicle is with client
            if ($vehicle->is_available) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vehicle is already available. It cannot be returned.'
                ], 422);
            }

            DB::beginTransaction();

            // Update rental record
            $rental = \App\Models\SelfDriverRentedVehicle::where('vehicle_id', $vehicle->vehicle_id)
                ->where('status', 'on_client')
                ->latest('released_at')
                ->first();

            if ($rental) {
                $rental->update([
                    'received_by' => $user->id,
                    'dropped_off_by_client_id' => $validated['client_id'],
                    'returned_at' => now(),
                    'status' => 'available',
                    'return_notes' => $validated['notes'] ?? null,
                ]);
            }

            // Update vehicle status
            $vehicle->update([
                'is_available' => true,
                'received_by' => $user->id,
                'dropped_off_by_client_id' => $validated['client_id'],
                'returned_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Vehicle successfully returned and marked as available.'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Vehicle return error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error processing return: ' . $e->getMessage()
            ], 500);
        }
    }
}