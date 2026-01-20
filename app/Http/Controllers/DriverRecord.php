<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;
use Illuminate\Support\Facades\Hash;

class DriverRecord extends Controller
{
    public function index()
    {
        $drivers = Driver::all();
        return view('admin.driverrecord', compact('drivers'));
    }
    
    public function store(Request $request)
    {
        // Accept either 'license_no' (UI) or 'license_num' (older forms)
        $license = $request->input('license_no', $request->input('license_num'));
        $request->merge(['license_num' => $license]);
        
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:drivers,email',
            'license_num' => 'required|string|max:13|unique:drivers,license_num',
            'dateadded' => 'nullable|date',
            'address' => 'nullable|string|max:500',
            'contact_number' => ['required', 'string', 'max:20', 'regex:/^(\+63|0)?[9]\d{9}$|^(\+63|0)?[2-8]\d{7,9}$/'],
            'status' => 'nullable|in:active,inactive',
        ], [
            'contact_number.regex' => 'Please enter a valid contact number (e.g., 09123456789 or +639123456789)',
        ]);

        $dateadded = $request->input('dateadded', now());
        
        Driver::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'license_num' => $license,
            'dateadded' => $dateadded,
            'address' => $request->input('address'),
            'contact_number' => $request->input('contact_number'),
            'status' => $request->input('status', 'active'),
        ]);

        return redirect()->back()->with('success', 'Driver added successfully.');
    }

    public function update(Request $request, $id)
    {
        $driver = Driver::findOrFail($id);
        
        // Accept either 'license_no' (UI) or 'license_num' (older forms)
        $license = $request->input('license_no', $request->input('license_num'));
        
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:drivers,email,' . $id,
            'license_num' => 'required|string|max:13|unique:drivers,license_num,' . $id,
            'dateadded' => 'nullable|date',
            'address' => 'nullable|string|max:500',
            'contact_number' => ['required', 'string', 'max:20', 'regex:/^(\+63|0)?[9]\d{9}$|^(\+63|0)?[2-8]\d{7,9}$/'],
            'status' => 'nullable|in:active,inactive',
        ], [
            'contact_number.regex' => 'Please enter a valid contact number (e.g., 09123456789 or +639123456789)',
        ]);

        $driver->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'license_num' => $license,
            'dateadded' => $request->input('dateadded', $driver->dateadded),
            'address' => $request->input('address'),
            'contact_number' => $request->input('contact_number'),
            'status' => $request->input('status', $driver->status ?? 'active'),
        ]);

        return redirect()->back()->with('success', 'Driver updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        // Validate password
        $request->validate([
            'admin_password' => 'required|string',
        ], [
            'admin_password.required' => 'Password is required to confirm deletion.',
        ]);

        // Check if password is correct
        if (!Hash::check($request->input('admin_password'), auth()->user()->password)) {
            return response()->json(['message' => 'Incorrect password. Please try again.'], 422);
        }

        $driver = Driver::findOrFail($id);
        $driver->delete();
        
        return response()->json(['message' => 'Driver deleted successfully.']);
    }
}