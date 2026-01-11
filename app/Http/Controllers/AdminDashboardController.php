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

    
    public function usermanagement()
    {
         $admins = User::where('role', 'admin')->get();
        return view('admin.users', compact('admins'));
    }

}
