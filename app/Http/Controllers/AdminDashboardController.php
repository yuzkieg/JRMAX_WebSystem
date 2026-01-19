<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
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

}
