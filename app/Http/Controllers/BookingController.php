<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingVehicle;
use App\Models\Vehicle;
use App\Models\Client;
use App\Models\Driver;
use App\Models\BookingStatus;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Display booking management dashboard
     */
    public function index()
    {
        // Get statistics
        $stats = [
            'total' => Booking::count(),
            'active' => Booking::whereIn('status_id', [1, 2, 3])->count(), // Pending, Confirmed, Ongoing
            'pending' => Booking::where('status_id', 1)->count(),
            'todayRevenue' => Booking::whereDate('created_at', today())
                ->whereIn('status_id', [2, 3, 4]) // Confirmed, Ongoing, Completed
                ->sum('total_price')
        ];

        // Get bookings with pagination
        $bookings = Booking::with(['client', 'vehicles.vehicle', 'driver', 'status'])
            ->orderBy('start_datetime', 'desc')
            ->paginate(10);

        $statuses = BookingStatus::all();
        $clients = Client::all();
        $vehicles = Vehicle::all();
        $drivers = Driver::all();

        return view('admin.booking', compact('bookings', 'statuses', 'stats', 'clients', 'vehicles', 'drivers'));
    }

    /**
     * Show form to create new booking
     */
    public function create()
    {
        $clients = Client::where('status_id', 1)->orderBy('first_name')->get();
        $vehicles = Vehicle::where('is_available', true)
            ->with('driverInfo')
            ->orderBy('brand')
            ->orderBy('model')
            ->get();
        $drivers = Driver::orderBy('full_name')->get();
        $statuses = BookingStatus::all();

        return view('admin.booking.create', compact('clients', 'vehicles', 'drivers', 'statuses'));
    }

    /**
     * Store new booking
     */
    public function store(Request $request)
    {
        // Validate request data
        $validated = $request->validate([
            'client_first_name' => 'required|string|max:255',
            'client_last_name' => 'required|string|max:255',
            'client_contact' => 'required|string|max:50',
            'client_email' => 'required|email|max:255',
            'client_license' => 'nullable|string|max:255',
            'client_address' => 'nullable|string|max:500',
            'start_datetime' => 'required|date_format:Y-m-d\TH:i',
            'end_datetime' => 'required|date_format:Y-m-d\TH:i|after:start_datetime',
            'pickup_location' => 'required|string|max:255',
            'dropoff_location' => 'required|string|max:255',
            'pickup_type' => 'nullable|in:self_drive,with_driver',
            'driver_id' => 'nullable|exists:drivers,id',
            'vehicle_ids' => 'required|array|min:1',
            'vehicle_ids.*' => 'exists:vehicles,vehicle_id',
            'total_price' => 'required|numeric|min:0',
            'status_id' => 'required|exists:bookingstatus,status_id',
            'payment_method' => 'nullable|string',
            'payment_receipt' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'special_requests' => 'nullable|string'
        ]);

        DB::beginTransaction();

        try {
            // Handle client creation or update
            $client = Client::updateOrCreate(
                [
                    'email' => $validated['client_email']
                ],
                [
                    'first_name' => $validated['client_first_name'],
                    'last_name' => $validated['client_last_name'],
                    'contact_number' => $validated['client_contact'],
                    'address' => $validated['client_address'],
                    'license_number' => $validated['client_license']
                ]
            );

            // Parse start and end datetime
            $start = Carbon::parse($validated['start_datetime']);
            $end = Carbon::parse($validated['end_datetime']);

            // Handle pickup_type logic
            $pickupType = $validated['pickup_type'] ?? 'with_driver';
            $driverId = null;

            // If pickup_type is with_driver, validate driver assignment
            if ($pickupType === 'with_driver') {
                if (!empty($validated['driver_id'])) {
                    $driver = Driver::find($validated['driver_id']);
                    if (!$driver) {
                        return back()->with('error', 'Selected driver not found.');
                    }
                    if ($driver->status === 'inactive') {
                        return back()->with('error', 'Cannot assign inactive driver to booking.');
                    }
                    $driverId = $validated['driver_id'];
                }
            }
            // If self_drive, driver_id must be null (enforced below)

            // Check vehicle availability
            foreach ($validated['vehicle_ids'] as $vehicleId) {
                $isAvailable = $this->checkVehicleAvailability($vehicleId, $start, $end);
                if (!$isAvailable) {
                    $vehicle = Vehicle::where('vehicle_id', $vehicleId)->first();
                    return back()->with('error', "Vehicle {$vehicle->plate_num} is not available for the selected dates.");
                }
            }

            // Handle payment receipt upload
            $paymentReceiptPath = null;
            if ($request->hasFile('payment_receipt')) {
                $file = $request->file('payment_receipt');
                $filename = time() . '_' . Str::slug($client->first_name . '-' . $client->last_name) . '.' . $file->getClientOriginalExtension();
                $paymentReceiptPath = $file->storeAs('public/bookings/receipts', $filename);
                // Store relative path
                $paymentReceiptPath = 'bookings/receipts/' . $filename;
            }

            // Create booking
            $booking = Booking::create([
                'client_id' => $client->client_id,
                'start_datetime' => $start,
                'end_datetime' => $end,
                'pickup_location' => $validated['pickup_location'],
                'dropoff_location' => $validated['dropoff_location'],
                'pickup_type' => $pickupType,
                'driver_id' => $driverId, // Will be null for self_drive
                'total_price' => $validated['total_price'],
                'status_id' => $validated['status_id'],
                'payment_method' => $validated['payment_method'] ?? null,
                'payment_receipt' => $paymentReceiptPath,
                'special_requests' => $validated['special_requests'] ?? null,
                'created_by' => auth()->id()
            ]);

            // Assign vehicles
            foreach ($validated['vehicle_ids'] as $vehicleId) {
                BookingVehicle::create([
                    'booking_id' => $booking->boarding_id,
                    'vehicle_id' => $vehicleId,
                    'assigned_by' => auth()->id(),
                    'assigned_at' => now(),
                    'remarks' => 'Created new booking'
                ]);
                // Set vehicle as unavailable if status is Confirmed or Ongoing
                if (in_array($validated['status_id'], [2, 3])) {
                    Vehicle::where('vehicle_id', $vehicleId)->update(['is_available' => false]);
                }
            }

            DB::commit();

            return redirect()->route('admin.booking')->with('success', 'Booking created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating booking', ['error' => $e->getMessage()]);
            return back()->with('error', 'Error creating booking: ' . $e->getMessage());
        }
    }
    public function update(Request $request, $id)
    {
        // Admin edit should ONLY allow: Assign Driver, Booking Status, Payment Method, Notes.
        // Fleet Assistant can also edit pickup/dropoff fields for self-drive bookings
        $validated = $request->validate([
            'driver_id' => 'nullable|exists:drivers,id',
            'status_id' => 'required|exists:bookingstatus,status_id',
            'payment_method' => 'nullable|string|max:50',
            'payment_receipt' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'special_requests' => 'nullable|string|max:2000',
            'sent_by' => 'nullable|exists:users,id',
            'received_by' => 'nullable|string|max:255',
            'collected_by' => 'nullable|exists:users,id',
            'returned_by' => 'nullable|string|max:255',
        ]);

        $booking = Booking::with('vehicles')->findOrFail($id);

        DB::beginTransaction();
        try {
            // Handle driver assignment with inactive check
            $driverId = $validated['driver_id'] ?? null;
            if ($driverId && $booking->pickup_type !== 'self_drive') {
                $driver = Driver::find($driverId);
                if ($driver && $driver->status === 'inactive') {
                    DB::rollBack();
                    return back()->with('error', 'Cannot assign inactive driver to booking.');
                }
            }
            
            // If self_drive, ensure driver_id is null
            if ($booking->pickup_type === 'self_drive') {
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
                $client = $booking->client;
                $filename = time() . '_' . Str::slug($client->first_name . '-' . $client->last_name) . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/bookings/receipts', $filename);
                $paymentReceiptPath = 'bookings/receipts/' . $filename;
            }

            $updateData = [
                'driver_id' => $driverId,
                'status_id' => $validated['status_id'],
                'payment_method' => $validated['payment_method'] ?? null,
                'payment_receipt' => $paymentReceiptPath,
                'special_requests' => $validated['special_requests'] ?? null,
                'updated_by' => auth()->id(),
            ];
            
            // Only allow Fleet Assistant to update pickup/dropoff fields for self-drive bookings
            if (auth()->user()->role === 'fleet_assistant' && $booking->pickup_type === 'self_drive') {
                if (isset($validated['sent_by'])) {
                    $updateData['sent_by'] = $validated['sent_by'];
                }
                if (isset($validated['received_by'])) {
                    $updateData['received_by'] = $validated['received_by'];
                }
                if (isset($validated['collected_by'])) {
                    $updateData['collected_by'] = $validated['collected_by'];
                }
                if (isset($validated['returned_by'])) {
                    $updateData['returned_by'] = $validated['returned_by'];
                }
            }
            
            $booking->update($updateData);

            // Keep vehicle availability in sync with status changes
            $vehicleIds = $booking->vehicles?->pluck('vehicle_id')->filter()->values() ?? collect();
            if ($vehicleIds->isNotEmpty()) {
                if (in_array((int) $validated['status_id'], [2, 3], true)) {
                    Vehicle::whereIn('vehicle_id', $vehicleIds)->update(['is_available' => false]);
                }
                if (in_array((int) $validated['status_id'], [4, 5], true)) {
                    Vehicle::whereIn('vehicle_id', $vehicleIds)->update(['is_available' => true]);
                }
            }

            DB::commit();
            return redirect()->route('admin.booking')->with('success', 'Booking updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating booking', ['error' => $e->getMessage()]);
            return back()->with('error', 'Error updating booking: ' . $e->getMessage());
        }
    }

    /**
     * Display booking details with comprehensive information
     */
    public function show($id)
    {
        $booking = Booking::with([
            'client', 
            'vehicles.vehicle.driverInfo',
            'vehicles.assignedBy',
            'driver',
            'status',
            'createdBy',
            'updatedBy'
        ])->findOrFail($id);

        // Calculate duration and additional details
        $start = $booking->start_datetime;
        $end = $booking->end_datetime;
        $durationHours = $start->diffInHours($end);
        $durationDays = $start->diffInDays($end);
        
        // Calculate pricing breakdown
        $vehicleCount = $booking->vehicles->count();
        $pricePerVehicle = $vehicleCount > 0 ? $booking->total_price / $vehicleCount : 0;

        // Return JSON if requested
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'boarding_id' => $booking->boarding_id,
                    'client_id' => $booking->client_id,
                    'boarding_date' => $booking->boarding_date,
                    'start_datetime' => $start->format('Y-m-d\TH:i'),
                    'end_datetime' => $end->format('Y-m-d\TH:i'),
                    'duration' => [
                        'hours' => $durationHours,
                        'days' => $durationDays,
                        'formatted' => $durationDays . ' days, ' . ($durationHours % 24) . ' hours'
                    ],
                    'pickup_location' => $booking->pickup_location,
                    'dropoff_location' => $booking->dropoff_location,
                    'driver_id' => $booking->driver_id,
                    'total_price' => $booking->total_price,
                    'price_per_vehicle' => round($pricePerVehicle, 2),
                    'vehicle_count' => $vehicleCount,
                    'status_id' => $booking->status_id,
                    'special_requests' => $booking->special_requests,
                    'vehicle_ids' => $booking->vehicles->pluck('vehicle_id')->toArray(),
                    'client' => [
                        'id' => $booking->client->client_id,
                        'first_name' => $booking->client->first_name,
                        'last_name' => $booking->client->last_name,
                        'email' => $booking->client->email,
                        'contact_number' => $booking->client->contact_number,
                        'address' => $booking->client->address,
                        'identification_type' => $booking->client->identification_type,
                        'identification_number' => $booking->client->identification_number
                    ],
                    'vehicles' => $booking->vehicles->map(function($bv) {
                        return [
                            'vehicle_id' => $bv->vehicle->vehicle_id,
                            'plate_num' => $bv->vehicle->plate_num,
                            'brand' => $bv->vehicle->brand,
                            'model' => $bv->vehicle->model,
                            'body_type' => $bv->vehicle->body_type,
                            'year' => $bv->vehicle->year,
                            'price_rate' => $bv->vehicle->price_rate,
                            'assigned_at' => $bv->assigned_at,
                            'assigned_by' => $bv->assignedBy?->name,
                            'remarks' => $bv->remarks
                        ];
                    }),
                    'driver' => $booking->driver ? [
                        'id' => $booking->driver->id,
                        'full_name' => $booking->driver->full_name ?? $booking->driver->name ?? null,
                        'license_number' => $booking->driver->license_number ?? $booking->driver->license_num ?? null,
                        'contact_number' => $booking->driver->phone_number ?? $booking->driver->contact_number ?? null,
                        'email' => $booking->driver->email ?? null
                    ] : null,
                    'status' => [
                        'id' => $booking->status->status_id,
                        'name' => $booking->status->status_name,
                        'color' => $booking->status->color
                    ],
                    'created_by' => $booking->createdBy?->name,
                    'created_at' => $booking->created_at->format('Y-m-d H:i:s'),
                    'updated_by' => $booking->updatedBy?->name,
                    'updated_at' => $booking->updated_at->format('Y-m-d H:i:s')
                ]
            ]);
        }

        return view('admin.booking.show', compact('booking', 'durationHours', 'durationDays', 'pricePerVehicle'));
    }

    /**
     * Check vehicle availability
     */
    public function checkAvailability(Request $request)
    {
        $validated = $request->validate([
            'start_datetime' => 'required|date_format:Y-m-d\TH:i',
            'end_datetime' => 'required|date_format:Y-m-d\TH:i|after:start_datetime',
            'vehicle_type' => 'nullable|string'
        ]);

        $start = Carbon::parse($validated['start_datetime']);
        $end = Carbon::parse($validated['end_datetime']);

        // Get vehicles booked in this period
        $bookedVehicleIds = BookingVehicle::whereHas('booking', function($query) use ($start, $end) {
            $query->where(function($q) use ($start, $end) {
                $q->whereBetween('start_datetime', [$start, $end])
                  ->orWhereBetween('end_datetime', [$start, $end])
                  ->orWhere(function($q2) use ($start, $end) {
                      $q2->where('start_datetime', '<=', $start)
                         ->where('end_datetime', '>=', $end);
                  });
            })->whereNotIn('status_id', [5]); // Exclude cancelled bookings
        })->pluck('vehicle_id');

        // Get available vehicles
        $query = Vehicle::where('is_available', true)
            ->whereNotIn('vehicle_id', $bookedVehicleIds);

        if ($request->filled('vehicle_type')) {
            $query->where('body_type', $validated['vehicle_type']);
        }

        $vehicles = $query->with('driverInfo')->get();

        // Calculate pricing
        $duration = $start->diffInHours($end);
        $vehicles->each(function($vehicle) use ($duration) {
            $vehicle->estimated_price = $vehicle->price_rate * ceil($duration / 24); // Daily rate
        });

        return response()->json([
            'success' => true,
            'vehicles' => $vehicles,
            'count' => $vehicles->count(),
            'duration_hours' => $duration,
            'duration_days' => ceil($duration / 24)
        ]);
    }

    /**
     * Display calendar view
     */
    public function calendar()
    {
        $bookings = Booking::with(['client', 'vehicles.vehicle', 'status'])
            ->whereBetween('start_datetime', [now()->startOfMonth(), now()->endOfMonth()->addMonth()])
            ->get();

        $events = [];
        foreach ($bookings as $booking) {
            $vehicleNames = $booking->vehicles->pluck('vehicle.plate_num')->implode(', ');
            
            $events[] = [
                'id' => $booking->boarding_id,
                'title' => $booking->client->first_name . ' - ' . $vehicleNames,
                'start' => $booking->start_datetime->toIso8601String(),
                'end' => $booking->end_datetime->toIso8601String(),
                'color' => $booking->status->color ?? '#6B7280',
                'extendedProps' => [
                    'client' => $booking->client->full_name,
                    'status' => $booking->status->status_name ?? 'Unknown',
                    'vehicles' => $vehicleNames,
                    'total' => $booking->formatted_total,
                    'pickup' => $booking->pickup_location,
                    'dropoff' => $booking->dropoff_location
                ]
            ];
        }

        return view('admin.booking.calendar', compact('events'));
    }

    /**
     * Get booking statistics for dashboard
     */
    public function getStats(Request $request)
    {
        $period = $request->get('period', 'month'); // day, week, month, year
        
        $query = Booking::query();
        
        switch ($period) {
            case 'day':
                $query->whereDate('created_at', today());
                break;
            case 'week':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', now()->month);
                break;
            case 'year':
                $query->whereYear('created_at', now()->year);
                break;
        }

        $stats = [
            'total_bookings' => $query->count(),
            'total_revenue' => $query->whereIn('status_id', [2, 3, 4])->sum('total_price'),
            'confirmed_bookings' => $query->where('status_id', 2)->count(),
            'pending_bookings' => $query->where('status_id', 1)->count(),
            'completed_bookings' => $query->where('status_id', 4)->count()
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats,
            'period' => $period
        ]);
    }

    
    /**
     * Helper: Check vehicle availability
     */
    private function checkVehicleAvailability($vehicleId, $start, $end, $excludeBookingId = null)
    {
        $query = BookingVehicle::where('vehicle_id', $vehicleId)
            ->whereHas('booking', function($query) use ($start, $end) {
                $query->where(function($q) use ($start, $end) {
                    $q->whereBetween('start_datetime', [$start, $end])
                      ->orWhereBetween('end_datetime', [$start, $end])
                      ->orWhere(function($q2) use ($start, $end) {
                          $q2->where('start_datetime', '<=', $start)
                             ->where('end_datetime', '>=', $end);
                      });
                })->whereNotIn('status_id', [5]); // Not cancelled
            });
        
        if ($excludeBookingId) {
            $query->where('booking_id', '!=', $excludeBookingId);
        }

        return !$query->exists();
    }

    /**
     * View payment receipt
     */
    public function viewReceipt($id)
    {
        $booking = Booking::findOrFail($id);
        
        if (!$booking->payment_receipt) {
            abort(404, 'Payment receipt not found');
        }
        
        $filePath = storage_path('app/public/' . $booking->payment_receipt);
        
        if (!file_exists($filePath)) {
            abort(404, 'Payment receipt file not found');
        }
        
        $mimeType = mime_content_type($filePath);
        
        return response()->file($filePath, [
            'Content-Type' => $mimeType,
        ]);
    }

    /**
     * Download payment receipt
     */
    public function downloadReceipt($id)
    {
        $booking = Booking::findOrFail($id);
        
        if (!$booking->payment_receipt) {
            abort(404, 'Payment receipt not found');
        }
        
        $filePath = storage_path('app/public/' . $booking->payment_receipt);
        
        if (!file_exists($filePath)) {
            abort(404, 'Payment receipt file not found');
        }
        
        $filename = 'receipt_' . $booking->boarding_id . '_' . basename($booking->payment_receipt);
        
        return response()->download($filePath, $filename);
    }

    /**
     * Process payment for a booking
     */
    public function processPayment(Request $request, $id)
    {
        $booking = Booking::with(['client', 'vehicles.vehicle'])->findOrFail($id);
        
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|in:cash,gcash,bank_transfer,credit_card,online_transfer',
            'reference_number' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            // Update booking payment method
            $booking->update([
                'payment_method' => $validated['payment_method'],
                'updated_by' => auth()->id(),
            ]);

            // Create payment record
            try {
                \App\Models\Payment::create([
                    'booking_id' => $booking->boarding_id,
                    'amount' => $validated['amount'],
                    'payment_method_id' => null, // Optional - map payment method if needed
                    'payment_status_id' => null, // Optional - default to paid if status table exists
                    'reference_number' => $validated['reference_number'] ?? null,
                    'paid_at' => now(),
                    'issued_by' => auth()->id(),
                ]);
            } catch (\Exception $e) {
                \Log::warning('Payment record creation skipped: ' . $e->getMessage());
                // Continue even if payment record creation fails
            }

            DB::commit();

            // Generate receipt URL
            $receiptUrl = route('admin.booking.receipt.pdf', $booking->boarding_id);

            return response()->json([
                'success' => true,
                'message' => 'Payment processed successfully',
                'receipt_url' => $receiptUrl,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Payment processing error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error processing payment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate PDF receipt
     */
    public function generateReceiptPDF($id)
    {
        $booking = Booking::with(['client', 'vehicles.vehicle', 'driver', 'status'])->findOrFail($id);
        
        // Return the simple printable receipt view
        return view('bookings.receipt', compact('booking'));
    }
}