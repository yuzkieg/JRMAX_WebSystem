<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use App\Models\BookingStatus;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class AdminDashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $stats = [
            'total' => Booking::count(),
            'active' => Booking::whereIn('status_id', [1, 2, 3])->count(), // Pending, Confirmed, Ongoing
            'pending' => Booking::where('status_id', 1)->count(),
            'completedBookings' => Booking::where('status_id', 4)->count(),
            'todayRevenue' => Booking::whereDate('created_at', today())
                ->whereIn('status_id', [2, 3, 4]) // Confirmed, Ongoing, Completed
                ->sum('total_price'),
            'monthlyRevenue' => Booking::whereBetween('created_at', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])->whereIn('status_id', [2, 3, 4])->sum('total_price'),
            'totalRevenue' => Booking::whereIn('status_id', [2, 3, 4])->sum('total_price'),
            'revenue_by_status' => [
                'confirmed' => Booking::where('status_id', 2)->sum('total_price'),
                'ongoing' => Booking::where('status_id', 3)->sum('total_price'),
                'completed' => Booking::where('status_id', 4)->sum('total_price'),
            ],
            'last_7_days_revenue' => $this->getLast7DaysRevenue()
        ];

        $admins = User::where('role', 'admin')->get();
        return view('admin.adminanalysis', compact('admins', 'stats'));
    }

    /**
     * Get last 7 days revenue data
     */
    private function getLast7DaysRevenue()
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $revenue = Booking::whereDate('created_at', $date)
                ->whereIn('status_id', [2, 3, 4])
                ->sum('total_price');
            $data[] = [
                'date' => $date->format('Y-m-d'),
                'revenue' => $revenue
            ];
        }
        return $data;
    }

    /**
     * Get statistics for chart data
     */
    public function stats(Request $request)
    {
        $period = $request->get('period', 'today');
        
        $stats = [
            'total' => Booking::count(),
            'todayRevenue' => Booking::whereDate('created_at', today())
                ->whereIn('status_id', [2, 3, 4])
                ->sum('total_price'),
            'monthlyRevenue' => Booking::whereBetween('created_at', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])->whereIn('status_id', [2, 3, 4])->sum('total_price'),
            'totalRevenue' => Booking::whereIn('status_id', [2, 3, 4])->sum('total_price'),
            'revenue_by_status' => [
                'confirmed' => Booking::where('status_id', 2)->sum('total_price'),
                'ongoing' => Booking::where('status_id', 3)->sum('total_price'),
                'completed' => Booking::where('status_id', 4)->sum('total_price'),
            ],
            'last_7_days_revenue' => $this->getLast7DaysRevenue()
        ];

        // Generate chart data based on period
        $chartData = [];
        
        if ($period === 'today') {
            // Last 24 hours in 4-hour intervals
            $labels = [];
            $data = [];
            for ($i = 5; $i >= 0; $i--) {
                $start = Carbon::today()->addHours($i * 4);
                $end = $start->copy()->addHours(4);
                
                $hourLabel = $start->format('g A');
                $labels[] = $hourLabel;
                
                $revenue = Booking::whereBetween('created_at', [$start, $end])
                    ->whereIn('status_id', [2, 3, 4])
                    ->sum('total_price');
                $data[] = $revenue;
            }
            $chartData = [
                'labels' => $labels,
                'data' => $data
            ];
        } elseif ($period === 'monthly') {
            // Last 30 days
            $labels = [];
            $data = [];
            for ($i = 29; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $labels[] = $date->format('M d');
                
                $revenue = Booking::whereDate('created_at', $date)
                    ->whereIn('status_id', [2, 3, 4])
                    ->sum('total_price');
                $data[] = $revenue;
            }
            $chartData = [
                'labels' => $labels,
                'data' => $data
            ];
        } else {
            // Last 12 months
            $labels = [];
            $data = [];
            for ($i = 11; $i >= 0; $i--) {
                $month = Carbon::now()->subMonths($i);
                $labels[] = $month->format('M Y');
                
                $revenue = Booking::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->whereIn('status_id', [2, 3, 4])
                    ->sum('total_price');
                $data[] = $revenue;
            }
            $chartData = [
                'labels' => $labels,
                'data' => $data
            ];
        }

        $stats['chart_data'] = $chartData;

        return response()->json($stats);
    }

    
    public function usermanagement()
    {
         $admins = User::where('role', 'admin')->get();
        return view('admin.users', compact('admins'));
    }

    /**
     * Get dashboard report with date range filter
     */
    public function getReport(Request $request)
    {
        $validated = $request->validate([
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
        ]);

        $fromDate = $validated['from_date'] ? Carbon::parse($validated['from_date'])->startOfDay() : null;
        $toDate = $validated['to_date'] ? Carbon::parse($validated['to_date'])->endOfDay() : null;

        // Build base query
        $query = Booking::query();
        
        if ($fromDate && $toDate) {
            $query->whereBetween('created_at', [$fromDate, $toDate]);
        } elseif ($fromDate) {
            $query->where('created_at', '>=', $fromDate);
        } elseif ($toDate) {
            $query->where('created_at', '<=', $toDate);
        }

        // Total bookings
        $totalBookings = (clone $query)->count();

        // Total revenue (from confirmed, ongoing, and completed bookings)
        $totalRevenue = (clone $query)
            ->whereIn('status_id', [2, 3, 4]) // Confirmed, Ongoing, Completed
            ->sum('total_price');

        // Booking count grouped by status
        $bookingsByStatus = (clone $query)
            ->select('status_id', DB::raw('count(*) as count'))
            ->groupBy('status_id')
            ->get()
            ->mapWithKeys(function ($item) {
                $status = BookingStatus::find($item->status_id);
                return [
                    $status ? $status->status_name : 'Unknown' => $item->count
                ];
            })
            ->toArray();

        // Get status details for better response
        $statusDetails = (clone $query)
            ->join('BookingStatus', 'bookings.status_id', '=', 'BookingStatus.status_id')
            ->select(
                'BookingStatus.status_id',
                'BookingStatus.status_name',
                DB::raw('count(*) as count'),
                DB::raw('sum(case when bookings.status_id in (2, 3, 4) then bookings.total_price else 0 end) as revenue')
            )
            ->groupBy('BookingStatus.status_id', 'BookingStatus.status_name')
            ->get()
            ->map(function ($item) {
                return [
                    'status_id' => $item->status_id,
                    'status_name' => $item->status_name,
                    'count' => (int) $item->count,
                    'revenue' => (float) $item->revenue,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'total_bookings' => $totalBookings,
                'total_revenue' => (float) $totalRevenue,
                'bookings_by_status' => $bookingsByStatus,
                'status_details' => $statusDetails,
                'date_range' => [
                    'from_date' => $fromDate ? $fromDate->format('Y-m-d') : null,
                    'to_date' => $toDate ? $toDate->format('Y-m-d') : null,
                ],
            ],
        ]);
    }

}
