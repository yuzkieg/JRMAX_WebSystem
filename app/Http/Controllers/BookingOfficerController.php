<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Client;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\BookingStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BookingOfficerController extends Controller
{
    public function __construct()
    {
        \Log::info('BookingOfficerController accessed', [
            'full_url' => request()->fullUrl(),
            'path' => request()->path(),
            'method' => request()->method(),
            'route_name' => \Route::currentRouteName() ?? 'unknown'
        ]);
    }

    public function index()
    {
        // Get bookings for the booking officer
        $bookings = Booking::with(['client', 'vehicles.vehicle', 'status', 'driver'])
            ->latest()
            ->paginate(10);

        // Get statistics
        $stats = $this->getStats();

        // Get clients, vehicles, drivers, and statuses for the create form
        $clients = Client::where('status_id', '1')->get();
        $vehicles = Vehicle::where('is_available', '1')->get();
        $drivers = Driver::all(); // Fixed: removed incorrect where clause
        $statuses = BookingStatus::all();

        return view('employee.booking.bookingdash', compact(
            'bookings', 
            'stats', 
            'clients', 
            'vehicles', 
            'drivers', 
            'statuses'
        ));
    }

    public function show($id)
    {
        \Log::info('Show method called with ID:', ['id' => $id, 'type' => gettype($id)]);
        
        // If it's 'bookingdash', redirect to index
        if ($id === 'bookingdash') {
            \Log::warning('Bookingdash accessed via show method, redirecting to index');
            return redirect()->route('employee.booking.index');
        }
        
        // Ensure $id is numeric
        if (!is_numeric($id)) {
            \Log::error('Non-numeric ID provided to show method', ['id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'Invalid booking ID. ID must be numeric.'
            ], 400);
        }
        
        try {
            $booking = Booking::with(['client', 'vehicles.vehicle', 'status', 'driver'])
                ->findOrFail($id);

            return response()->json([
                'booking' => $booking,
                'success' => true
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('Booking not found', ['id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }
    }

    public function edit($id)
    {
        $booking = Booking::with(['client', 'vehicles', 'status', 'driver'])
            ->findOrFail($id);

        return response()->json([
            'booking' => $booking,
            'success' => true
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'client_id' => 'required|exists:Client,Editor_id',
                'boarding_date' => 'required|date',
                'start_datetime' => 'required|date',
                'end_datetime' => 'required|date|after:start_datetime',
                'pickup_location' => 'required|string|max:255',
                'dropoff_location' => 'required|string|max:255',
                'pickup_type' => 'nullable|in:self_drive,with_driver',
                'total_price' => 'required|numeric|min:0',
                'status_id' => 'required|exists:BookingStatus,status_id',
                'payment_method' => 'nullable|string|in:cash,credit_card,online_transfer',
                'driver_id' => 'nullable|exists:Driver,id',
                'payment_receipt' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
                'special_requests' => 'nullable|string',
                'vehicle_ids' => 'required|array|min:1',
                'vehicle_ids.*' => 'exists:Vehicle,vehicle_id',
            ]);

            // Check vehicle availability
            $unavailableVehicles = [];
            foreach ($validated['vehicle_ids'] as $vehicleId) {
                $hasOverlap = Booking::whereHas('vehicles', function($query) use ($vehicleId) {
                        $query->where('vehicle_id', $vehicleId);
                    })
                    ->where(function($query) use ($validated) {
                        $query->whereBetween('start_datetime', [$validated['start_datetime'], $validated['end_datetime']])
                            ->orWhereBetween('end_datetime', [$validated['start_datetime'], $validated['end_datetime']])
                            ->orWhere(function($query) use ($validated) {
                                $query->where('start_datetime', '<', $validated['start_datetime'])
                                    ->where('end_datetime', '>', $validated['end_datetime']);
                            });
                    })
                    ->whereIn('status_id', [1, 2, 3])
                    ->exists();

                if ($hasOverlap) {
                    $vehicle = Vehicle::find($vehicleId);
                    $unavailableVehicles[] = $vehicle->brand . ' ' . $vehicle->model . ' (' . $vehicle->plate_num . ')';
                }
            }

            if (!empty($unavailableVehicles)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Some vehicles are not available for the selected dates: ' . implode(', ', $unavailableVehicles)
                ], 400);
            }

            // Handle pickup_type logic
            $pickupType = $validated['pickup_type'] ?? 'with_driver';
            $driverId = null;

            // If pickup_type is with_driver, validate driver assignment
            if ($pickupType === 'with_driver') {
                if (!empty($validated['driver_id'])) {
                    $driver = Driver::find($validated['driver_id']);
                    if (!$driver) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Selected driver not found.'
                        ], 400);
                    }
                    if ($driver->status === 'inactive') {
                        return response()->json([
                            'success' => false,
                            'message' => 'Cannot assign inactive driver to booking.'
                        ], 400);
                    }
                    $driverId = $validated['driver_id'];
                }
            }
            // If self_drive, driver_id must be null

            // Handle payment receipt upload
            $paymentReceiptPath = null;
            if ($request->hasFile('payment_receipt')) {
                $file = $request->file('payment_receipt');
                $client = Client::find($validated['client_id']);
                $filename = time() . '_' . ($client ? Str::slug($client->first_name . '-' . $client->last_name) : 'client') . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/bookings/receipts', $filename);
                $paymentReceiptPath = 'bookings/receipts/' . $filename;
            }

            // Create the booking
            $booking = Booking::create([
                'client_id' => $validated['client_id'],
                'boarding_date' => $validated['boarding_date'],
                'start_datetime' => $validated['start_datetime'],
                'end_datetime' => $validated['end_datetime'],
                'pickup_location' => $validated['pickup_location'],
                'dropoff_location' => $validated['dropoff_location'],
                'pickup_type' => $pickupType,
                'total_price' => $validated['total_price'],
                'status_id' => $validated['status_id'],
                'payment_method' => $validated['payment_method'] ?? 'cash',
                'driver_id' => $driverId, // Will be null for self_drive
                'payment_receipt' => $paymentReceiptPath,
                'special_requests' => $validated['special_requests'] ?? null,
                'created_by' => Auth::id(),
            ]);

            // Attach vehicles
            $booking->vehicles()->attach($validated['vehicle_ids']);

            // Update vehicle is_available to 0 if status is confirmed or ongoing
            if (in_array($validated['status_id'], [2, 3])) {
                Vehicle::whereIn('vehicle_id', $validated['vehicle_ids'])
                    ->update(['is_available' => 0]);
            }

            // Update revenue statistics
            $this->updateRevenueStats();

            \Log::info('Booking created successfully', [
                'booking_id' => $booking->id,
                'client_id' => $booking->client_id,
                'total_price' => $booking->total_price
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully!',
                'booking' => $booking,
                'revenue_updated' => true
            ]);

        } catch (\Exception $e) {
            \Log::error('Booking creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $booking = Booking::findOrFail($id);
            $oldTotalPrice = $booking->total_price; // Store old price for comparison

            $validated = $request->validate([
                'client_id' => 'required|exists:Client,Editor_id',
                'boarding_date' => 'required|date',
                'start_datetime' => 'required|date',
                'end_datetime' => 'required|date|after:start_datetime',
                'pickup_location' => 'required|string|max:255',
                'dropoff_location' => 'required|string|max:255',
                'pickup_type' => 'nullable|in:self_drive,with_driver',
                'total_price' => 'required|numeric|min:0',
                'status_id' => 'required|exists:BookingStatus,status_id',
                'payment_method' => 'nullable|string|in:cash,credit_card,online_transfer',
                'driver_id' => 'nullable|exists:Driver,id',
                'payment_receipt' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
                'special_requests' => 'nullable|string',
                'vehicle_ids' => 'required|array|min:1',
                'vehicle_ids.*' => 'exists:Vehicle,vehicle_id',
            ]);

            // Handle driver assignment with inactive check
            $pickupType = $validated['pickup_type'] ?? $booking->pickup_type ?? 'with_driver';
            $driverId = null;

            if ($pickupType === 'with_driver') {
                if (!empty($validated['driver_id'])) {
                    $driver = Driver::find($validated['driver_id']);
                    if ($driver && $driver->status === 'inactive') {
                        return response()->json([
                            'success' => false,
                            'message' => 'Cannot assign inactive driver to booking.'
                        ], 400);
                    }
                    $driverId = $validated['driver_id'];
                }
            }
            // If self_drive, ensure driver_id is null
            if ($pickupType === 'self_drive') {
                $driverId = null;
            }

            // Handle payment receipt upload
            $paymentReceiptPath = $booking->payment_receipt;
            if ($request->hasFile('payment_receipt')) {
                // Delete old receipt if exists
                if ($paymentReceiptPath) {
                    Storage::delete('public/' . $paymentReceiptPath);
                }
                
                $file = $request->file('payment_receipt');
                $client = Client::find($validated['client_id']);
                $filename = time() . '_' . ($client ? Str::slug($client->first_name . '-' . $client->last_name) : 'client') . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/bookings/receipts', $filename);
                $paymentReceiptPath = 'bookings/receipts/' . $filename;
            }

            // Get old vehicle IDs before update
            $oldVehicleIds = $booking->vehicles()->pluck('vehicle_id')->toArray();

            // Update booking
            $booking->update([
                'client_id' => $validated['client_id'],
                'boarding_date' => $validated['boarding_date'],
                'start_datetime' => $validated['start_datetime'],
                'end_datetime' => $validated['end_datetime'],
                'pickup_location' => $validated['pickup_location'],
                'dropoff_location' => $validated['dropoff_location'],
                'pickup_type' => $pickupType,
                'total_price' => $validated['total_price'],
                'status_id' => $validated['status_id'],
                'payment_method' => $validated['payment_method'] ?? 'cash',
                'driver_id' => $driverId,
                'payment_receipt' => $paymentReceiptPath,
                'special_requests' => $validated['special_requests'] ?? null,
                'updated_by' => Auth::id(),
            ]);

            // Sync vehicles
            $booking->vehicles()->sync($validated['vehicle_ids']);

            // Update vehicle statuses
            $this->updateVehicleStatuses($oldVehicleIds, $validated['vehicle_ids'], $validated['status_id']);

            // If total price changed, update revenue stats
            $revenueUpdated = false;
            if ($oldTotalPrice != $validated['total_price']) {
                $this->updateRevenueStats();
                $revenueUpdated = true;
            }

            \Log::info('Booking updated successfully', [
                'booking_id' => $booking->id,
                'old_price' => $oldTotalPrice,
                'new_price' => $validated['total_price'],
                'revenue_updated' => $revenueUpdated
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Booking updated successfully!',
                'booking' => $booking,
                'revenue_updated' => $revenueUpdated
            ]);

        } catch (\Exception $e) {
            \Log::error('Booking update failed', [
                'booking_id' => $id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status_id' => 'required|exists:BookingStatus,status_id'
            ]);

            $booking = Booking::with('vehicles')->findOrFail($id);
            $oldStatusId = $booking->status_id;
            $newStatusId = $request->status_id;

            // Check if the status transition is valid
            $validTransitions = [
                1 => [2, 5], // Pending can go to Confirmed or Cancelled
                2 => [3, 5], // Confirmed can go to Ongoing or Cancelled
                3 => [4, 5], // Ongoing can go to Completed or Cancelled
                4 => [],     // Completed is terminal
                5 => [],     // Cancelled is terminal
            ];

            if (!in_array($newStatusId, $validTransitions[$oldStatusId] ?? [])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status transition.'
                ], 400);
            }

            // Update booking status
            $booking->update([
                'status_id' => $newStatusId,
                'updated_by' => Auth::id(),
            ]);

            // Update vehicle statuses based on booking status change
            $this->updateVehicleStatusOnBookingStatusChange(
                $booking->vehicles->pluck('vehicle_id')->toArray(),
                $oldStatusId,
                $newStatusId
            );

            // Update revenue stats when status changes (especially when cancelled or completed)
            $revenueUpdated = false;
            if (in_array($oldStatusId, [2, 3]) && in_array($newStatusId, [4, 5])) {
                // Moving from active (confirmed/ongoing) to completed/cancelled
                $this->updateRevenueStats();
                $revenueUpdated = true;
            } elseif (in_array($oldStatusId, [5]) && in_array($newStatusId, [2, 3])) {
                // Moving from cancelled back to active
                $this->updateRevenueStats();
                $revenueUpdated = true;
            }

            $statusNames = [
                1 => 'Pending',
                2 => 'Confirmed',
                3 => 'Ongoing',
                4 => 'Completed',
                5 => 'Cancelled'
            ];

            \Log::info('Booking status updated', [
                'booking_id' => $booking->id,
                'old_status' => $oldStatusId,
                'new_status' => $newStatusId,
                'revenue_updated' => $revenueUpdated
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Booking status updated to ' . $statusNames[$newStatusId] . ' successfully!',
                'booking' => $booking,
                'revenue_updated' => $revenueUpdated
            ]);

        } catch (\Exception $e) {
            \Log::error('Booking status update failed', [
                'booking_id' => $id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function updateVehicleStatuses($oldVehicleIds, $newVehicleIds, $bookingStatusId)
    {
        // Vehicles to be removed from booking
        $removedVehicleIds = array_diff($oldVehicleIds, $newVehicleIds);
        
        // Vehicles to be added to booking
        $addedVehicleIds = array_diff($newVehicleIds, $oldVehicleIds);

        // Update removed vehicles back to available if they're not in other active bookings
        if (!empty($removedVehicleIds)) {
            foreach ($removedVehicleIds as $vehicleId) {
                $vehicle = Vehicle::find($vehicleId);
                // Check if vehicle is not in any other confirmed/ongoing booking
                $hasOtherActiveBooking = Booking::whereHas('vehicles', function($query) use ($vehicleId) {
                        $query->where('vehicle_id', $vehicleId);
                    })
                    ->whereIn('status_id', [2, 3]) // Confirmed or Ongoing
                    ->exists();

                if (!$hasOtherActiveBooking) {
                    $vehicle->update(['is_available' => 1]); // Set to available
                }
            }
        }

        // Update added vehicles based on booking status
        if (!empty($addedVehicleIds)) {
            $isAvailable = in_array($bookingStatusId, [2, 3]) ? 0 : 1; // 0 = not available, 1 = available
            Vehicle::whereIn('vehicle_id', $addedVehicleIds)
                ->update(['is_available' => $isAvailable]);
        }
    }

    private function updateVehicleStatusOnBookingStatusChange($vehicleIds, $oldStatusId, $newStatusId)
    {
        // Define status mappings
        $activeStatuses = [2, 3]; // Confirmed, Ongoing
        $inactiveStatuses = [1, 4, 5]; // Pending, Completed, Cancelled

        // Moving from active to inactive status
        if (in_array($oldStatusId, $activeStatuses) && in_array($newStatusId, $inactiveStatuses)) {
            foreach ($vehicleIds as $vehicleId) {
                $vehicle = Vehicle::find($vehicleId);
                // Check if vehicle is not in any other active booking
                $hasOtherActiveBooking = Booking::whereHas('vehicles', function($query) use ($vehicleId) {
                        $query->where('vehicle_id', $vehicleId);
                    })
                    ->whereIn('status_id', [2, 3])
                    ->where('boarding_id', '!=', $vehicle->boarding_id ?? null)
                    ->exists();

                if (!$hasOtherActiveBooking) {
                    $vehicle->update(['is_available' => 1]); // Set to available
                }
            }
        }
        // Moving from inactive to active status
        elseif (in_array($oldStatusId, $inactiveStatuses) && in_array($newStatusId, $activeStatuses)) {
            Vehicle::whereIn('vehicle_id', $vehicleIds)
                ->update(['is_available' => 0]); // Set to not available
        }
    }

    private function getStats()
    {
        $today = Carbon::today();
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;

        return [
            'total' => Booking::count(),
            'pending' => Booking::where('status_id', 1)->count(),
            'active' => Booking::whereIn('status_id', [2, 3])->count(),
            'todayRevenue' => Booking::whereDate('created_at', $today)
                ->where('status_id', '!=', 5) // Exclude cancelled
                ->sum('total_price'),
            'monthlyRevenue' => Booking::whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->where('status_id', '!=', 5)
                ->sum('total_price'),
            'totalRevenue' => Booking::where('status_id', '!=', 5)
                ->sum('total_price'),
            'completedBookings' => Booking::where('status_id', 4)->count(),
            'cancelledBookings' => Booking::where('status_id', 5)->count(),
        ];
    }

    private function updateRevenueStats()
    {
        $today = Carbon::today();
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;

        // You can store these in cache for quick access
        $stats = [
            'todayRevenue' => Booking::whereDate('created_at', $today)
                ->where('status_id', '!=', 5)
                ->sum('total_price'),
            'monthlyRevenue' => Booking::whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->where('status_id', '!=', 5)
                ->sum('total_price'),
            'totalRevenue' => Booking::where('status_id', '!=', 5)
                ->sum('total_price'),
            'updated_at' => now()->toDateTimeString()
        ];

        // Cache the stats for 5 minutes
        \Cache::put('booking_revenue_stats', $stats, 300);
        
        return $stats;
    }

    public function getRevenueStats(Request $request)
    {
        $period = $request->get('period', 'today'); // today, monthly, total
        
        switch ($period) {
            case 'today':
                $revenue = Booking::whereDate('created_at', Carbon::today())
                    ->where('status_id', '!=', 5)
                    ->sum('total_price');
                break;
            case 'monthly':
                $revenue = Booking::whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->where('status_id', '!=', 5)
                    ->sum('total_price');
                break;
            case 'total':
            default:
                $revenue = Booking::where('status_id', '!=', 5)
                    ->sum('total_price');
                break;
        }

        return response()->json([
            'success' => true,
            'period' => $period,
            'revenue' => $revenue,
            'formatted_revenue' => '₱' . number_format($revenue, 2)
        ]);
    }

    public function calendar()
    {
        // Calendar view logic
        return view('employee.booking-calendar');
    }

    public function checkAvailability(Request $request)
    {
        try {
            $validated = $request->validate([
                'start_datetime' => 'required|date',
                'end_datetime' => 'required|date|after:start_datetime',
                'vehicle_ids' => 'required|array|min:1',
                'vehicle_ids.*' => 'exists:Vehicle,vehicle_id',
            ]);

            $start = Carbon::parse($validated['start_datetime']);
            $end = Carbon::parse($validated['end_datetime']);

            // Check for overlapping bookings for each vehicle
            $unavailableVehicles = [];
            foreach ($validated['vehicle_ids'] as $vehicleId) {
                $hasOverlap = Booking::whereHas('vehicles', function($query) use ($vehicleId) {
                        $query->where('vehicle_id', $vehicleId);
                    })
                    ->where(function($query) use ($start, $end) {
                        $query->whereBetween('start_datetime', [$start, $end])
                            ->orWhereBetween('end_datetime', [$start, $end])
                            ->orWhere(function($query) use ($start, $end) {
                                $query->where('start_datetime', '<', $start)
                                    ->where('end_datetime', '>', $end);
                            });
                    })
                    ->whereIn('status_id', [1, 2, 3]) // Pending, Confirmed, Ongoing
                    ->exists();

                if ($hasOverlap) {
                    $vehicle = Vehicle::find($vehicleId);
                    $unavailableVehicles[] = $vehicle->brand . ' ' . $vehicle->model . ' (' . $vehicle->plate_num . ')';
                }
            }

            return response()->json([
                'available' => empty($unavailableVehicles),
                'unavailable_vehicles' => $unavailableVehicles,
                'message' => empty($unavailableVehicles) 
                    ? 'All selected vehicles are available.' 
                    : 'Some vehicles are not available for the selected dates.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function calculatePrice(Request $request)
    {
        try {
            $validated = $request->validate([
                'start_datetime' => 'required|date',
                'end_datetime' => 'required|date|after:start_datetime',
                'vehicle_ids' => 'required|array|min:1',
                'vehicle_ids.*' => 'exists:Vehicle,vehicle_id',
            ]);

            $start = Carbon::parse($validated['start_datetime']);
            $end = Carbon::parse($validated['end_datetime']);
            
            // Calculate duration in hours
            $hours = $end->diffInHours($start);
            $days = ceil($hours / 24); // Round up to nearest day

            // Get vehicles and calculate total price
            $vehicles = Vehicle::whereIn('vehicle_id', $validated['vehicle_ids'])->get();
            $totalPrice = 0;
            $vehicleDetails = [];

            foreach ($vehicles as $vehicle) {
                $vehiclePrice = $vehicle->price_rate * $days;
                $totalPrice += $vehiclePrice;
                
                $vehicleDetails[] = [
                    'name' => $vehicle->brand . ' ' . $vehicle->model,
                    'plate' => $vehicle->plate_num,
                    'daily_rate' => $vehicle->price_rate,
                    'days' => $days,
                    'subtotal' => $vehiclePrice
                ];
            }

            return response()->json([
                'success' => true,
                'total_price' => $totalPrice,
                'days' => $days,
                'hours' => $hours,
                'vehicle_details' => $vehicleDetails,
                'formatted_total' => '₱' . number_format($totalPrice, 2)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function customers()
    {
        // Customer management logic
        $customers = Client::with(['bookings'])->paginate(15);
        return view('employee.customers', compact('customers'));
    }

    public function getStatsJson()
    {
        $stats = $this->getStats();
        
        // Get additional revenue breakdown
        $stats['revenue_by_status'] = [
            'confirmed' => Booking::where('status_id', 2)->sum('total_price'),
            'ongoing' => Booking::where('status_id', 3)->sum('total_price'),
            'completed' => Booking::where('status_id', 4)->sum('total_price'),
        ];
        
        // Get revenue trends (last 7 days)
        $last7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dailyRevenue = Booking::whereDate('created_at', $date)
                ->where('status_id', '!=', 5)
                ->sum('total_price');
            
            $last7Days[] = [
                'date' => $date->format('Y-m-d'),
                'day' => $date->format('D'),
                'revenue' => $dailyRevenue
            ];
        }
        
        $stats['last_7_days_revenue'] = $last7Days;
        
        return response()->json($stats);
    }
}