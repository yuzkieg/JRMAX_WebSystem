<?php

use App\Http\Controllers\DriverRecord;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\SuperAdminMiddleware;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FleetController;
use App\Http\Controllers\BookingOfficerController;
use App\Http\Controllers\EmployeeRecord;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\VehicleMaintenanceController;
use App\Http\Controllers\FleetVehicleController;
use App\Http\Controllers\FleetMaintenanceController;
use App\Http\Controllers\BookingController;


// Public homepage
Route::get('/', fn() => view('webdefault'));

// Login routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/layout', fn() => view('superadmin.layout'));

Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');

Route::middleware(['web', 'superadmin'])->group(function () {
    Route::get('/superadmin/dashboard', [AdminController::class, 'index'])
         ->name('superadmin.dashboard');

    Route::post('/superadmin/admins', [AdminController::class, 'store'])
        ->name('superadmin.admins.store');
    
    Route::put('/superadmin/admins/{id}', [AdminController::class, 'update'])
        ->name('superadmin.admins.update');
    
    Route::delete('/superadmin/admins/{id}', [AdminController::class, 'destroy'])
        ->name('superadmin.admins.destroy');
});

Route::middleware(['web', 'admin'])->group(function () {
    Route::get('/admin/adminanalysis', [AdminDashboardController::class, 'index'])
        ->name('admin.adminanalysis');
    Route::get('/admin/analysis/stats', [AdminDashboardController::class, 'stats'])
        ->name('admin.analysis.stats');
    Route::get('/admin/analysis/report', [AdminDashboardController::class, 'getReport'])
        ->name('admin.analysis.report');
    Route::get('/admin/users', [EmployeeController::class, 'usermanagement'])
        ->name('admin.users');
    Route::get('/admin/adminhr', [EmployeeRecord::class, 'index'])
        ->name('admin.adminhr');

      // Employee Record CRUD routes
    Route::post('/admin/hr', [EmployeeRecord::class, 'store'])
        ->name('admin.hr.store');
    Route::put('/admin/hr/{id}', [EmployeeRecord::class, 'update'])
        ->name('admin.hr.update');
    Route::delete('/admin/hr/{id}', [EmployeeRecord::class, 'destroy'])
        ->name('admin.hr.destroy');    
    
    // Driver CRUD routes
    Route::get('/admin/drivers', [DriverRecord::class, 'index'])
        ->name('admin.drivers.index');
    Route::post('/admin/drivers', [DriverRecord::class, 'store'])
        ->name('admin.drivers.store');
    Route::put('/admin/drivers/{id}', [DriverRecord::class, 'update'])
        ->name('admin.drivers.update');
    Route::delete('/admin/drivers/{id}', [DriverRecord::class, 'destroy'])
        ->name('admin.drivers.destroy');
    
    // Vehicle CRUD routes
    Route::get('/admin/vehicles', [VehicleController::class, 'index'])
        ->name('admin.vehicles.index');
    Route::get('/admin/vehicles/data', [VehicleController::class, 'getVehiclesData'])
    ->name('admin.vehicles.data');
    Route::post('/admin/vehicles', [VehicleController::class, 'store'])
        ->name('admin.vehicles.store');
    Route::put('/admin/vehicles/{vehicle}', [VehicleController::class, 'update'])
        ->name('admin.vehicles.update');
    Route::delete('/admin/vehicles/{vehicle_id}', [VehicleController::class, 'destroy'])
    ->name('admin.vehicles.destroy');
    
    Route::get('/admin/maintenance', fn() => view('admin.maintenance'));


    // Employee User CRUD routes
    Route::post('/admin/employees', [EmployeeController::class, 'store'])
        ->name('admin.employees.store');
    Route::put('/admin/employees/{id}', [EmployeeController::class, 'update'])
        ->name('admin.employees.update');
    Route::delete('/admin/employees/{id}', [EmployeeController::class, 'destroy'])
        ->name('admin.employees.destroy');

    // Maintenance CRUD routes
Route::get('/admin/maintenance', [VehicleMaintenanceController::class, 'index'])
    ->name('admin.maintenance');
Route::post('/admin/maintenance', [VehicleMaintenanceController::class, 'store'])
    ->name('admin.maintenance.store');
Route::put('/admin/maintenance/{id}', [VehicleMaintenanceController::class, 'update'])
    ->name('admin.maintenance.update');
Route::delete('/admin/maintenance/{id}', [VehicleMaintenanceController::class, 'destroy'])
    ->name('admin.maintenance.destroy');
Route::patch('/admin/maintenance/{id}/status', [VehicleMaintenanceController::class, 'updateStatus'])
    ->name('admin.maintenance.status');
Route::get('/admin/maintenance/{id}/edit', [VehicleMaintenanceController::class, 'edit'])
    ->name('admin.maintenance.edit');


    // Admin Booking Management Routes
    Route::get('/admin/booking', [BookingController::class, 'index'])->name('admin.booking');
    Route::post('/admin/booking', [BookingController::class, 'store'])->name('admin.booking.store');
    Route::get('/admin/booking/{id}', [BookingController::class, 'show'])->name('admin.booking.show');
    Route::get('/admin/booking/{id}/edit', [BookingController::class, 'edit'])->name('admin.booking.edit');
    Route::put('/admin/booking/{id}', [BookingController::class, 'update'])->name('admin.booking.update');
    Route::get('/admin/booking/{id}/receipt/view', [BookingController::class, 'viewReceipt'])->name('admin.booking.receipt.view');
    Route::get('/admin/booking/{id}/receipt/download', [BookingController::class, 'downloadReceipt'])->name('admin.booking.receipt.download');
    Route::post('/admin/booking/{id}/process-payment', [BookingController::class, 'processPayment'])->name('admin.booking.process-payment');
    Route::get('/admin/booking/{id}/receipt/pdf', [BookingController::class, 'generateReceiptPDF'])->name('admin.booking.receipt.pdf');
    Route::get('/admin/booking/calendar', [BookingController::class, 'calendar'])->name('admin.booking.calendar');
    Route::post('/admin/booking/check-availability', [BookingController::class, 'checkAvailability'])->name('admin.booking.check-availability');
    Route::get('/admin/booking/stats', [BookingController::class, 'getStats'])->name('admin.booking.stats');
    
    // AJAX routes
    Route::post('/booking/calculate-price', [BookingController::class, 'calculatePrice'])->name('admin.booking.calculate-price');

     // Main audit log page
    Route::get('/audit-logs', [AuditController::class, 'index'])->name('admin.audit.index');
    
    // View specific audit log details (AJAX)
    Route::get('/audit-logs/{id}', [AuditController::class, 'show'])->name('admin.audit.show');
    
    // Get audit logs data (for AJAX filtering)
    Route::get('/audit-logs-data', [AuditController::class, 'getData'])->name('admin.audit.data');
    
    // Export audit logs to CSV
    Route::get('/audit-logs-export', [AuditController::class, 'export'])->name('admin.audit.export');
    
    // Get audit statistics
    Route::get('/audit-stats', [AuditController::class, 'getStats'])->name('admin.audit.stats');
    
    // Cleanup old logs (admin utility)
    Route::post('/audit-cleanup', [AuditController::class, 'cleanup'])->name('admin.audit.cleanup');
    
    // User activity timeline
    Route::get('/audit-user/{userId}', [AuditController::class, 'userActivity'])->name('admin.audit.user');
    
    // Record-specific activity
    Route::post('/audit-record-activity', [AuditController::class, 'recordActivity'])->name('admin.audit.record');
});


// Fleet user routes
Route::middleware(['web', 'fleet_assistant'])->group(function () {
    // Vehicle Management (Full CRUD)
    Route::get('/employee/fleet/vehicles', [FleetVehicleController::class, 'index'])
        ->name('employee.fleet.vehicles');
    Route::get('/employee/fleet/vehicles/data', [FleetVehicleController::class, 'getVehiclesData'])
        ->name('employee.fleet.vehicles.data');
    Route::post('/employee/fleet/vehicles', [FleetVehicleController::class, 'store'])
        ->name('employee.fleet.vehicles.store');
    Route::put('/employee/fleet/vehicles/{vehicle}', [FleetVehicleController::class, 'update'])
        ->name('employee.fleet.vehicles.update');
    Route::delete('/employee/fleet/vehicles/{vehicle_id}', [FleetVehicleController::class, 'destroy'])
        ->name('employee.fleet.vehicles.destroy');
    Route::post('/employee/fleet/vehicles/{vehicle_id}/handover', [FleetVehicleController::class, 'handover'])
        ->name('employee.fleet.vehicles.handover');
    Route::post('/employee/fleet/vehicles/{vehicle_id}/return', [FleetVehicleController::class, 'returnVehicle'])
        ->name('employee.fleet.vehicles.return');
    
    // Maintenance Management (Full CRUD)
    Route::get('/employee/fleet/maintenance', [FleetMaintenanceController::class, 'index'])
        ->name('employee.fleet.maintenance');
    Route::get('/employee/fleet/maintenance/{id}/edit', [FleetMaintenanceController::class, 'edit'])
        ->name('employee.fleet.maintenance.edit');
    Route::post('/employee/fleet/maintenance', [FleetMaintenanceController::class, 'store'])
        ->name('employee.fleet.maintenance.store');
    Route::put('/employee/fleet/maintenance/{id}', [FleetMaintenanceController::class, 'update'])
        ->name('employee.fleet.maintenance.update');
    Route::delete('/employee/fleet/maintenance/{id}', [FleetMaintenanceController::class, 'destroy'])
        ->name('employee.fleet.maintenance.destroy');
    Route::patch('/employee/fleet/maintenance/{id}/status', [FleetMaintenanceController::class, 'updateStatus'])
        ->name('employee.fleet.maintenance.status');
});

// Booking Officer routes
Route::middleware(['web', 'booking_officer'])->group(function () {
    // Booking Management Dashboard - MUST COME FIRST
    Route::get('/employee/bookingdash', [BookingOfficerController::class, 'index'])
        ->name('employee.booking.index');
    
    // Additional Booking Officer Features (specific routes before generic ones)
    Route::get('/employee/booking/calendar', [BookingOfficerController::class, 'calendar'])
        ->name('employee.booking.calendar');
    Route::post('/employee/booking/check-availability', [BookingOfficerController::class, 'checkAvailability'])
        ->name('employee.booking.check-availability');
    Route::get('/employee/booking/stats', [BookingOfficerController::class, 'getStatsJson'])
        ->name('employee.booking.stats');
    // Inside Booking Officer routes group (after line 174 in your web.php)
    Route::get('/employee/booking/revenue-stats', [BookingOfficerController::class, 'getRevenueStats'])
    ->name('employee.booking.revenue-stats');
    // Customer Management
    Route::get('/employee/booking/customers', [BookingOfficerController::class, 'customers'])
        ->name('employee.booking.customers');
    
    // AJAX routes
    Route::post('/employee/booking/calculate-price', [BookingOfficerController::class, 'calculatePrice'])
        ->name('employee.booking.calculate-price');
    
    // Booking CRUD Operations - Put generic {id} routes LAST
    Route::post('/employee/booking', [BookingOfficerController::class, 'store'])
        ->name('employee.booking.store');
    Route::get('/employee/booking/{id}', [BookingOfficerController::class, 'show'])
        ->name('employee.booking.show');
    Route::get('/employee/booking/{id}/edit', [BookingOfficerController::class, 'edit'])
        ->name('employee.booking.edit');
    Route::put('/employee/booking/{id}', [BookingOfficerController::class, 'update'])
        ->name('employee.booking.update');
    
    // Status update route (keep only ONE definition)
    Route::put('/employee/booking/{id}/status', [BookingOfficerController::class, 'updateStatus'])
        ->name('employee.booking.update-status');
});
Route::middleware(['web', 'user'])->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'index'])
        ->name('user.dashboard');
});