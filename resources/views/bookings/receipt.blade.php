<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Receipt - {{ $booking->boarding_id ?? 'N/A' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background: white;
            color: #000;
            padding: 40px;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .receipt-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #dc2626;
            padding-bottom: 20px;
        }
        
        .company-name {
            font-size: 28px;
            font-weight: bold;
            color: #dc2626;
            margin-bottom: 5px;
        }
        
        .receipt-title {
            font-size: 20px;
            color: #333;
            margin-top: 10px;
        }
        
        .receipt-info {
            margin-bottom: 30px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .info-label {
            font-weight: 600;
            color: #555;
            width: 40%;
        }
        
        .info-value {
            color: #000;
            width: 60%;
            text-align: right;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #dc2626;
            margin: 25px 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #dc2626;
        }
        
        .vehicle-list {
            margin: 15px 0;
        }
        
        .vehicle-item {
            padding: 10px;
            background: #f9fafb;
            margin-bottom: 10px;
            border-left: 4px solid #dc2626;
        }
        
        .price-breakdown {
            margin: 20px 0;
        }
        
        .price-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 14px;
        }
        
        .price-row.total {
            font-size: 18px;
            font-weight: bold;
            border-top: 2px solid #dc2626;
            border-bottom: 2px solid #dc2626;
            padding: 15px 0;
            margin-top: 10px;
        }
        
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            color: #666;
            font-size: 12px;
        }
        
        @media print {
            body {
                padding: 20px;
            }
            
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-header">
        <div class="company-name">JRMAX Vehicle Rental</div>
        <div style="font-size: 14px; color: #666; margin-top: 5px;">Official Booking Receipt</div>
    </div>

    <div class="receipt-info">
        <div class="info-row">
            <span class="info-label">Booking Reference:</span>
            <span class="info-value">#{{ str_pad($booking->boarding_id ?? 0, 6, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Date Issued:</span>
            <span class="info-value">{{ $booking->created_at ? $booking->created_at->format('F d, Y h:i A') : now()->format('F d, Y h:i A') }}</span>
        </div>
    </div>

    <div class="section-title">Client Information</div>
    <div class="receipt-info">
        <div class="info-row">
            <span class="info-label">Client Name:</span>
            <span class="info-value">{{ ($booking->client->first_name ?? '') . ' ' . ($booking->client->last_name ?? '') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Email:</span>
            <span class="info-value">{{ $booking->client->email ?? 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Contact Number:</span>
            <span class="info-value">{{ $booking->client->contact_number ?? 'N/A' }}</span>
        </div>
    </div>

    <div class="section-title">Rental Details</div>
    <div class="receipt-info">
        <div class="info-row">
            <span class="info-label">Pickup Location:</span>
            <span class="info-value">{{ $booking->pickup_location ?? 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Drop-off Location:</span>
            <span class="info-value">{{ $booking->dropoff_location ?? 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Start Date & Time:</span>
            <span class="info-value">{{ $booking->start_datetime ? $booking->start_datetime->format('F d, Y h:i A') : 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">End Date & Time:</span>
            <span class="info-value">{{ $booking->end_datetime ? $booking->end_datetime->format('F d, Y h:i A') : 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Duration:</span>
            <span class="info-value">{{ $booking->duration ?? 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Rental Type:</span>
            <span class="info-value">{{ ucfirst(str_replace('_', ' ', $booking->pickup_type ?? 'with_driver')) }}</span>
        </div>
    </div>

    <div class="section-title">Vehicle Details</div>
    <div class="vehicle-list">
        @if($booking->vehicles && $booking->vehicles->count() > 0)
            @foreach($booking->vehicles as $bookingVehicle)
                @php
                    $vehicle = $bookingVehicle->vehicle ?? null;
                @endphp
                @if($vehicle)
                <div class="vehicle-item">
                    <div style="font-weight: bold; margin-bottom: 5px;">
                        {{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->year }})
                    </div>
                    <div style="font-size: 14px; color: #666;">
                        Plate Number: {{ $vehicle->plate_num }} | 
                        Type: {{ $vehicle->body_type }} | 
                        Rate: ₱{{ number_format($vehicle->price_rate ?? 0, 2) }}/day
                    </div>
                </div>
                @endif
            @endforeach
        @else
            <div class="vehicle-item">
                <div style="color: #999;">No vehicles assigned</div>
            </div>
        @endif
    </div>

    <div class="section-title">Price Breakdown</div>
    <div class="price-breakdown">
        @php
            $vehicleCount = $booking->vehicles ? $booking->vehicles->count() : 1;
            $days = 1;
            if ($booking->start_datetime && $booking->end_datetime) {
                $days = max(1, $booking->start_datetime->diffInDays($booking->end_datetime) + 1);
            }
            $subtotal = $booking->total_price ?? 0;
        @endphp
        
        <div class="price-row">
            <span>Number of Vehicles:</span>
            <span>{{ $vehicleCount }}</span>
        </div>
        <div class="price-row">
            <span>Rental Days:</span>
            <span>{{ $days }} day(s)</span>
        </div>
        <div class="price-row">
            <span>Subtotal:</span>
            <span>₱{{ number_format($subtotal, 2) }}</span>
        </div>
        <div class="price-row total">
            <span>TOTAL AMOUNT:</span>
            <span>₱{{ number_format($booking->total_price ?? 0, 2) }}</span>
        </div>
        
        @if($booking->payment_method)
        <div class="price-row" style="margin-top: 10px;">
            <span>Payment Method:</span>
            <span>{{ ucfirst(str_replace('_', ' ', $booking->payment_method)) }}</span>
        </div>
        @endif
    </div>

    @if($booking->special_requests)
    <div class="section-title">Special Requests</div>
    <div style="padding: 15px; background: #f9fafb; border-left: 4px solid #dc2626; margin-bottom: 20px;">
        {{ $booking->special_requests }}
    </div>
    @endif

    <div class="footer">
        <div style="margin-bottom: 10px;">
            <strong>Thank you for choosing JRMAX Vehicle Rental!</strong>
        </div>
        <div>This is a computer-generated receipt. No signature required.</div>
        <div style="margin-top: 15px;">For inquiries, please contact our customer service.</div>
    </div>
</body>
</html>
