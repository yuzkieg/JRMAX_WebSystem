@extends('layouts.app')

@section('content')
@vite('resources/css/app.css')

<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Booking Details - #{{ str_pad($booking->boarding_id, 6, '0', STR_PAD_LEFT) }}</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="p-4 bg-white shadow rounded">
            <h2 class="font-semibold mb-2">Client</h2>
            <p><strong>Name:</strong> {{ $booking->client->full_name ?? 'N/A' }}</p>
            <p><strong>Email:</strong> {{ $booking->client->email ?? 'N/A' }}</p>
            <p><strong>Phone:</strong> {{ $booking->client->phone_number ?? 'N/A' }}</p>
            <p><strong>Address:</strong> {{ $booking->client->address ?? 'N/A' }}</p>
            <p><strong>{{ $booking->client->identification_type ?? 'ID Type' }}:</strong> {{ $booking->client->identification_number ?? 'N/A' }}</p>
        </div>

        <div class="p-4 bg-white shadow rounded">
            <h2 class="font-semibold mb-2">Booking Info</h2>
            <p><strong>Pickup:</strong> {{ $booking->pickup_location }}</p>
            <p><strong>Dropoff:</strong> {{ $booking->dropoff_location }}</p>
            <p><strong>Start:</strong> {{ optional($booking->start_datetime)->format('Y-m-d H:i') }}</p>
            <p><strong>End:</strong> {{ optional($booking->end_datetime)->format('Y-m-d H:i') }}</p>
            <p><strong>Duration:</strong> {{ $booking->duration ?? 'N/A' }}</p>
            <p><strong>Status:</strong> <span class="status-pill {{ strtolower($booking->status->status_name ?? '') }}">{{ $booking->status->status_name ?? 'Unknown' }}</span></p>
        </div>
    </div>

    <div class="mt-6 p-4 bg-white shadow rounded">
        <h2 class="font-semibold mb-2">Vehicles</h2>
        @if($booking->vehicles && $booking->vehicles->count())
            <table class="w-full text-left">
                <thead>
                    <tr>
                        <th class="px-2 py-1">Plate</th>
                        <th class="px-2 py-1">Brand</th>
                        <th class="px-2 py-1">Model</th>
                        <th class="px-2 py-1">Rate</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($booking->vehicles as $bv)
                        @php $v = $bv->vehicle; @endphp
                        <tr>
                            <td class="px-2 py-1">{{ $v->plate_num ?? 'N/A' }}</td>
                            <td class="px-2 py-1">{{ $v->brand ?? 'N/A' }}</td>
                            <td class="px-2 py-1">{{ $v->model ?? 'N/A' }}</td>
                            <td class="px-2 py-1">{{ isset($v->price_rate) ? '₱'.number_format($v->price_rate,2) : 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No vehicles assigned.</p>
        @endif
    </div>

    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="p-4 bg-white shadow rounded">
            <h2 class="font-semibold mb-2">Driver</h2>
            @if($booking->driver)
                <p><strong>Name:</strong> {{ $booking->driver->full_name }}</p>
                <p><strong>Phone:</strong> {{ $booking->driver->phone_number ?? 'N/A' }}</p>
                <p><strong>Email:</strong> {{ $booking->driver->user?->email ?? 'N/A' }}</p>
            @else
                <p>No driver assigned.</p>
            @endif
        </div>

        <div class="p-4 bg-white shadow rounded">
            <h2 class="font-semibold mb-2">Administrative</h2>
            <p><strong>Total Price:</strong> {{ $booking->formatted_total ?? '₱'.number_format($booking->total_price,2) }}</p>
            <p><strong>Created By:</strong> {{ $booking->createdBy?->name ?? 'N/A' }}</p>
            <p><strong>Created At:</strong> {{ optional($booking->created_at)->format('Y-m-d H:i:s') }}</p>
            <p><strong>Updated By:</strong> {{ $booking->updatedBy?->name ?? 'N/A' }}</p>
            <p><strong>Updated At:</strong> {{ optional($booking->updated_at)->format('Y-m-d H:i:s') }}</p>
        </div>
    </div>

    <div class="mt-6 p-4 bg-white shadow rounded">
        <h2 class="font-semibold mb-2">Special Requests / Notes</h2>
        <p>{{ $booking->special_requests ?? 'None' }}</p>
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.booking.index') }}" class="action-edit">Back to bookings</a>
    </div>
</div>

@endsection
